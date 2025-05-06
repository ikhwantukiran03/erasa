<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Venue;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * The Supabase storage service instance.
     *
     * @var \App\Services\SupabaseStorageService
     */
    protected $supabaseStorage;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\SupabaseStorageService $supabaseStorage
     * @return void
     */
    public function __construct(SupabaseStorageService $supabaseStorage)
    {
        $this->supabaseStorage = $supabaseStorage;
    }

    /**
     * Display a listing of the gallery items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if(!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.'); 
        }
        
        // Base query for galleries
        $query = Gallery::with('venue');
        
        // Filter by venue if requested
        if ($request->has('venue_id') && $request->venue_id) {
            $query->where('venue_id', $request->venue_id);
        }
        
        // Get galleries with sorting
        $galleries = $query->orderBy('venue_id')
                           ->orderBy('display_order')
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        // Get venues for the filter dropdown
        $venues = Venue::orderBy('name')->get();
        
        // Calculate some statistics
        $stats = [
            'total' => $galleries->count(),
            'local' => $galleries->where('source', 'local')->count(),
            'external' => $galleries->where('source', 'external')->count(),
            'featured' => $galleries->where('is_featured', true)->count(),
        ];
        
        return view('admin.galleries.index', compact('galleries', 'venues', 'stats'));
    }

    /**
     * Show the form for creating a new gallery item.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if(!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $venues = Venue::orderBy('name')->get();
        return view('admin.galleries.create', compact('venues'));
    }

    /**
     * Store newly created gallery items in storage.
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

        // Validate basic fields first
        $validator = Validator::make($request->all(), [
            'venue_id' => ['required', 'exists:venues,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $venue_id = $request->venue_id;
        $source_type = $request->source_type ?? 'local'; // Default to local
        $default_title = $request->default_title;
        $default_description = $request->default_description;
        $is_featured = $request->has('is_featured');
        $display_order = $request->display_order ?? 0;
        $count = 0;

        // Process local uploads
        if ($source_type === 'local' && $request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                // Validate image
                $validator = Validator::make(['image' => $image], [
                    'image' => ['required', 'image', 'max:5120'], // 5MB max
                ]);

                if ($validator->fails()) {
                    continue;
                }

                // Upload the image to Supabase
                $path = $this->supabaseStorage->uploadFile($image, 'venues/gallery');
                
                if (!$path) {
                    continue; // Skip if upload failed
                }
                
                // Get title and description for this image
                $title = $request->titles[$index] ?? $default_title ?? $image->getClientOriginalName();
                $description = $request->descriptions[$index] ?? $default_description;
                
                // Mark first image as featured if requested
                $is_image_featured = ($count === 0 && $is_featured) ? true : false;
                
                // Create gallery item
                Gallery::create([
                    'venue_id' => $venue_id,
                    'title' => $title,
                    'description' => $description,
                    'image_path' => $path,
                    'image_url' => null,
                    'is_featured' => $is_image_featured,
                    'display_order' => $display_order + $count,
                    'source' => 'local',
                ]);
                
                $count++;
            }
        }
        // Process external URLs
        elseif ($source_type === 'external' && !empty($request->image_urls)) {
            foreach ($request->image_urls as $index => $url) {
                if (empty($url)) continue;
                
                // Validate URL
                $validator = Validator::make(['url' => $url], [
                    'url' => ['required', 'url'],
                ]);

                if ($validator->fails()) {
                    continue;
                }
                
                // Get title and description for this URL
                $title = $request->url_titles[$index] ?? $default_title ?? 'Gallery Image';
                $description = $request->url_descriptions[$index] ?? $default_description;
                
                // Mark first image as featured if requested
                $is_image_featured = ($count === 0 && $is_featured) ? true : false;
                
                // Create gallery item
                Gallery::create([
                    'venue_id' => $venue_id,
                    'title' => $title,
                    'description' => $description,
                    'image_path' => null,
                    'image_url' => $url,
                    'is_featured' => $is_image_featured,
                    'display_order' => $display_order + $count,
                    'source' => 'external',
                ]);
                
                $count++;
            }
        }

        if ($count > 0) {
            return redirect()->route('admin.galleries.index')
                ->with('success', $count . ' image(s) added successfully.');
        } else {
            return redirect()->back()
                ->with('error', 'No images were uploaded. Please select at least one image.')
                ->withInput();
        }
    }

    /**
     * Display the specified gallery item.
     *
     * @param \App\Models\Gallery $gallery
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Gallery $gallery)
    {
        if (!auth()->user()->isAdmin()) {
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
        if (!auth()->user()->isAdmin()) {
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
                // Delete old image if it exists in Supabase
                if ($gallery->image_path && $gallery->source === 'local') {
                    $this->supabaseStorage->deleteFile($gallery->image_path);
                }
                
                // Upload new image to Supabase
                $path = $this->supabaseStorage->uploadFile($request->file('image'), 'venues/gallery');
                
                if ($path) {
                    $data['image_path'] = $path;
                }
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
                $this->supabaseStorage->deleteFile($gallery->image_path);
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
        
        // Delete the image file if it's a local image from Supabase
        if ($gallery->source === 'local' && $gallery->image_path) {
            $this->supabaseStorage->deleteFile($gallery->image_path);
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