<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * Display a listing of the gallery items.
     *
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RediretResponse
     */

     public function index()
     {
         if(auth()->user()->isAdmin()){
             return redirect()->route('dashboard')
             -> with('error', 'You do not have permission to access this resource.'); 
             }
    $galleries = Gallery::with('venue')->orderBy("venue_id")->orderBy('display_order')->get();
    return view('admin.galleries.index', compact('galleries'));
     }

     /**
      * Show the form for creating a new gallery item.
      *
      * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
      */

    public function create()
    {
        if(auth()->user()->isAdmin()){
            return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access this resource.');
        }
        $venues = Venue::orderBy('name')->get();
        return view('admin.galleries.create', compact('venues'));
    }

    /**
     * Store a newly created gallery item in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access this resource.');
        }

        $validator = Validator::make($request->all(), [
            'venue_id' => ['required', 'exists:venues,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source' => ['required', 'in:local,external'],
            'image' => ['required_if:source,local', 'nullable', 'image', 'max:5120'], // 5MB max
            'image_url' => ['required_if:source,external', 'nullable', 'url'],
            'is_featured' => ['sometimes', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        $data=$request->except(['image']);

        // handle file upload
        if ($request->source==='local' && $request->hasFile('image')){
            $path = $request->file('image')->store('venues/gallery', 'public');
            $data['image_path'] = $path;
            $data['image_url'] = null;
        } else {
            $data['image_path'] = null;
        }

        Gallery::create($data);

        return redirect()->route('admin.galleries.index')
        ->with('success', 'Gallery item created successfully.');
    }

    /**
     *Display the specified gallery item.
     *
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */

    public function show(Gallery $gallery)
    {
        if (auth()->user()->isAdmin()){
            return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access this resource.');
        }
        return view('admin.galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified gallery item.
     *
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Gallery $gallery)
    {
        if (auth()->user()->isAdmin()){
            return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access this resource.');
        }
        $venues = Venue::orderBy('name')->get();
        return view('admin.galleries.edit', compact('gallery', 'venues'));
    }

    /**
     * Update the specified gallery item in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\Http\RedirectResponse
     */
     public function update(Request $request, Gallery $gallery)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $validator = Validator::make($request->all(), [
            'venue_id' => ['required', 'exists:venues,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source' => ['required', 'in:local,external'],
            'image' => ['nullable', 'image', 'max:5120'], // 5MB max
            'image_url' => ['required_if:source,external', 'nullable', 'url'],
            'is_featured' => ['sometimes', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['image']);
        
        // Handle file upload or source change
        if ($request->source === 'local') {
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($gallery->image_path) {
                    Storage::disk('public')->delete($gallery->image_path);
                }
                
                // Upload new image
                $path = $request->file('image')->store('venues/gallery', 'public');
                $data['image_path'] = $path;
            }
            
            // Clear image URL if switching from external to local
            if ($gallery->source === 'external') {
                if (!$request->hasFile('image')) {
                    return redirect()->back()
                        ->with('error', 'Please upload an image when switching from external URL to local image.')
                        ->withInput();
                }
                $data['image_url'] = null;
            }
        } elseif ($request->source === 'external') {
            // If switching from local to external
            if ($gallery->source === 'local' && $gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
                $data['image_path'] = null;
            }
        }

        $gallery->update($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item updated successfully.');
    }

    /**
     * Remove the specified gallery item from storage.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Gallery $gallery)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        // Delete the image file if it's a local image
        if ($gallery->source === 'local' && $gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item deleted successfully.');
    }

    /**
     * Update the display order of gallery items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:galleries,id'],
            'items.*.order' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->items as $item) {
            Gallery::where('id', $item['id'])->update(['display_order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle the featured status of the gallery item.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFeatured(Gallery $gallery)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $gallery->update([
            'is_featured' => !$gallery->is_featured,
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery item updated successfully.');
    }
}
