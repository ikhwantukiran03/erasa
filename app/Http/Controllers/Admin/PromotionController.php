<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = Promotion::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        $promotions = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());
        
        return view('admin.promotions.index', compact('promotions', 'search'));
    }

    public function create()
    {
        $packages = \App\Models\Package::orderBy('name')->get();
        return view('admin.promotions.create', compact('packages'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'discount' => 'required|numeric|min:0|max:20000',
                'package_id' => 'required|exists:packages,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
                return redirect()->back()
                    ->withErrors(['image' => 'Invalid image file'])
                    ->withInput();
            }

            $imageData = $this->cloudinaryService->uploadImage($request->file('image'));

            Promotion::create([
                'title' => $request->title,
                'description' => $request->description,
                'discount' => $request->discount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'cloudinary_image_id' => $imageData['image_id'],
                'cloudinary_image_url' => $imageData['image_url'],
                'package_id' => $request->package_id,
            ]);

            return redirect()->route('admin.promotions.index')
                ->with('success', 'Promotion created successfully.');
        } catch (\Exception $e) {
            \Log::error('Promotion creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create promotion. Please try again.'])
                ->withInput();
        }
    }

    public function edit(Promotion $promotion)
    {
        $packages = \App\Models\Package::orderBy('name')->get();
        return view('admin.promotions.edit', compact('promotion', 'packages'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'discount' => 'required|numeric|min:0|max:20000',
                'package_id' => 'required|exists:packages,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $request->except('image');
            $data['package_id'] = $request->package_id;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Delete old image from Cloudinary
                if ($promotion->cloudinary_image_id) {
                    $this->cloudinaryService->deleteImage($promotion->cloudinary_image_id);
                }

                // Upload new image
                $imageData = $this->cloudinaryService->uploadImage($request->file('image'));
                $data['cloudinary_image_id'] = $imageData['image_id'];
                $data['cloudinary_image_url'] = $imageData['image_url'];
            }

            $promotion->update($data);

            return redirect()->route('admin.promotions.index')
                ->with('success', 'Promotion updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Promotion update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update promotion. Please try again.'])
                ->withInput();
        }
    }

    public function destroy(Promotion $promotion)
    {
        if ($promotion->cloudinary_image_id) {
            $this->cloudinaryService->deleteImage($promotion->cloudinary_image_id);
        }

        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }
} 