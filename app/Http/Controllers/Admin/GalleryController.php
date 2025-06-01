<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Venue;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * The Cloudinary storage service instance.
     *
     * @var \App\Services\CloudinaryService
     */
    protected $cloudinaryService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\CloudinaryService $cloudinaryService
     * @return void
     */
    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
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
        
        $search = $request->get('search', '');
        $venue_id = $request->get('venue_id', '');
        $featured = $request->get('featured', '');
        
        // Base query for galleries
        $query = Gallery::with('venue');
        
        // Filter by search term
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('venue', function($venueQuery) use ($search) {
                      $venueQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Filter by venue if requested
        if ($venue_id) {
            $query->where('venue_id', $venue_id);
        }
        
        // Filter by featured status
        if ($featured !== '') {
            if ($featured === '1') {
                $query->whereRaw('is_featured = true');
            } else {
                $query->whereRaw('is_featured = false');
            }
        }
        
        // Get galleries with sorting
        $galleries = $query->orderBy('venue_id')
                           ->orderBy('display_order')
                           ->orderBy('created_at', 'desc')
                           ->paginate(12)
                           ->appends($request->query());
        
        // Get venues for the filter dropdown
        $venues = Venue::orderBy('name')->get();
        
        // Calculate some statistics
        $stats = [
            'total' => Gallery::count(),
            'local' => Gallery::where('source', 'local')->count(),
            'external' => Gallery::where('source', 'external')->count(),
            'featured' => Gallery::whereRaw('is_featured = true')->count(),
        ];
        
        return view('admin.galleries.index', compact('galleries', 'venues', 'stats', 'search', 'venue_id', 'featured'));
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

                // Upload the image to Cloudinary
                $result = $this->cloudinaryService->uploadFile($image, 'venues/gallery');
                
                if (!$result) {
                    Log::error('Failed to upload image to Cloudinary', [
                        'venue_id' => $venue_id,
                        'file_name' => $image->getClientOriginalName(),
                        'file_size' => $image->getSize()
                    ]);
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
                    'image_path' => $result['url'], // Store the URL from Cloudinary
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
                ->with('error', 'No images were uploaded. Please select at least one image or check the storage configuration.')
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
                // Delete old image if it exists in Cloudinary
                if ($gallery->image_path && $gallery->source === 'local') {
                    // Extract public_id from Cloudinary URL
                    $public_id = $this->extractPublicIdFromUrl($gallery->image_path);
                    if ($public_id) {
                        $this->cloudinaryService->deleteFile($public_id);
                    }
                }
                
                // Upload new image to Cloudinary
                $result = $this->cloudinaryService->uploadFile($request->file('image'), 'gallery');
                
                if ($result) {
                    $data['image_path'] = $result['url'];
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
                // Extract public_id from Cloudinary URL
                $public_id = $this->extractPublicIdFromUrl($gallery->image_path);
                if ($public_id) {
                    $this->cloudinaryService->deleteFile($public_id);
                }
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
        
        // Delete the image file if it's a local image from Cloudinary
        if ($gallery->source === 'local' && $gallery->image_path) {
            // Extract public_id from Cloudinary URL
            $public_id = $this->extractPublicIdFromUrl($gallery->image_path);
            if ($public_id) {
                $this->cloudinaryService->deleteFile($public_id);
            }
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

    /**
     * Extract public_id from Cloudinary URL.
     *
     * @param string $url
     * @return string|null
     */
    private function extractPublicIdFromUrl($url)
    {
        // Example Cloudinary URL: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/public_id.jpg
        if (preg_match('/\/v\d+\/(.+)\.\w+$/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Bulk feature or unfeature selected gallery items.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkFeature(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        $validator = Validator::make($request->all(), [
            'gallery_ids' => ['required', 'array'],
            'gallery_ids.*' => ['required', 'exists:galleries,id'],
            'action' => ['required', 'in:feature,unfeature'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.galleries.index')
                ->with('error', 'Invalid selection. Please try again.');
        }

        $gallery_ids = $request->gallery_ids;
        $action = $request->action;
        
        // Update featured status based on action
        $featureValue = ($action === 'feature') ? true : false;
        
        // Update all selected galleries
        $updated = Gallery::whereIn('id', $gallery_ids)->update(['is_featured' => $featureValue]);
        
        $message = ($action === 'feature') 
            ? $updated . ' ' . Str::plural('image', $updated) . ' marked as featured.' 
            : $updated . ' ' . Str::plural('image', $updated) . ' removed from featured.';
            
        return redirect()->route('admin.galleries.index')->with('success', $message);
    }
}