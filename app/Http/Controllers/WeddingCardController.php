<?php

namespace App\Http\Controllers;

use App\Models\WeddingCard;
use App\Models\WeddingCardComment;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WeddingCardController extends Controller
{
    /**
     * Get wedding image URLs from the config file
     */
    protected function getWeddingImageUrls()
    {
        $imagePath = public_path('assets/wedding-image-urls.php');
        if (file_exists($imagePath)) {
            return include $imagePath;
        }
        
        // Fallback to default URLs if the file doesn't exist
        return [
            'backgrounds' => [
                'floral-bg' => 'https://images.unsplash.com/photo-1520052203542-d3095f1b6cf0',
                'rustic-bg' => 'https://images.unsplash.com/photo-1510081885740-2a5ea1391175',
                'gold-bg' => 'https://images.unsplash.com/photo-1553893876-6067c8011856',
                'vintage-bg' => 'https://images.unsplash.com/photo-1566793474285-2decf0fc182a',
                'default-bg' => 'https://images.unsplash.com/photo-1546032996-6098e8f79aa4',
            ],
            'headers' => [
                '1-header' => 'https://images.unsplash.com/photo-1507482108497-8d06359455e5',
                '3-header' => 'https://images.unsplash.com/photo-1533158326339-7f3cf2404354',
                '5-header' => 'https://images.unsplash.com/photo-1550173479-951bc174dae6',
                '4-bg' => 'https://images.unsplash.com/photo-1571114293109-37399f183649',
            ],
            'templates' => [
                '1' => 'https://images.unsplash.com/photo-1522173388303-fd5cc5d69347',
                '2' => 'https://images.unsplash.com/photo-1459501462159-97d5bded1416',
                '3' => 'https://images.unsplash.com/photo-1519741347686-c1e0aadf4611',
                '4' => 'https://images.unsplash.com/photo-1498716483717-47010a7c9ade',
                '5' => 'https://images.unsplash.com/photo-1470750868571-8c79ced0a82b',
            ],
        ];
    }

    public function index()
    {
        $cards = Auth::user()->weddingCards()->latest()->paginate(10);
        return view('wedding_cards.index', compact('cards'));
    }

    public function create()
    {
        // Define available templates
        $templates = [
            1 => 'Floral Elegance',
            2 => 'Pink Romance'
        ];
        
        $imageUrls = [
            'templates' => [
                '1' => 'https://res.cloudinary.com/dwqzoq6lc/image/upload/v1748688655/flower_l3u88l.jpg',
                '2' => 'https://res.cloudinary.com/dwqzoq6lc/image/upload/v1748688644/pink_gjcsvt.jpg'
            ]
        ];

        // Get all venues for selection
        $venues = Venue::orderBy('name')->get();
        
        return view('wedding_cards.create', compact('templates', 'imageUrls', 'venues'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'wedding_date' => 'required|date|after:today',
            'ceremony_time' => 'required|date_format:H:i',
            'venue_id' => 'required|exists:venues,id',
            'rsvp_contact_name' => 'required|string|max:255',
            'rsvp_contact_info' => 'required|string|max:255',
            'custom_message' => 'nullable|string',
            'template_id' => 'required|integer|min:1|max:2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $venue = Venue::findOrFail($request->venue_id);
        
        $card = Auth::user()->weddingCards()->create([
            'groom_name' => $request->groom_name,
            'bride_name' => $request->bride_name,
            'wedding_date' => $request->wedding_date,
            'ceremony_time' => $request->ceremony_time,
            'venue_name' => $venue->name,
            'venue_address' => $venue->getFullAddressAttribute(),
            'rsvp_contact_name' => $request->rsvp_contact_name,
            'rsvp_contact_info' => $request->rsvp_contact_info,
            'custom_message' => $request->custom_message,
            'template_id' => $request->template_id,
        ]);
        
        return redirect()->route('wedding-cards.show', $card->uuid)
            ->with('success', 'Wedding card created successfully!');
    }

    public function show($uuid)
    {
        $card = WeddingCard::where('uuid', $uuid)->firstOrFail();
        $comments = $card->comments()->where('is_approved', 1)->latest()->get();
        $imageUrls = [
            'templates' => [
                '1' => 'https://res.cloudinary.com/dwqzoq6lc/image/upload/v1748688655/flower_l3u88l.jpg',
                '2' => 'https://res.cloudinary.com/dwqzoq6lc/image/upload/v1748688644/pink_gjcsvt.jpg',
                '3' => 'https://res.cloudinary.com/dwqzoq6lc/image/upload/v1748688635/Blush-Gold-Wedding-invitation-_suow1j.jpg'
            ]
        ];
        
        return view('wedding_cards.show', compact('card', 'comments', 'imageUrls'));
    }

    public function edit($uuid)
    {
        $card = WeddingCard::where('uuid', $uuid)->firstOrFail();
        
        // Check if the user owns this card
        if (Auth::id() !== $card->user_id) {
            return redirect()->route('wedding-cards.index')
                ->with('error', 'You are not authorized to edit this card.');
        }
        
        $templates = [
            1 => 'Floral Elegance',
            2 => 'Pink Romance'
        ];
        
        $imageUrls = [
            'templates' => [
                '1' => 'https://res.cloudinary.com/dwqzoq6lc/image/upload/v1748688655/flower_l3u88l.jpg',
                '2' => 'https://res.cloudinary.com/dwqzoq6lc/image/upload/v1748688644/pink_gjcsvt.jpg'
            ]
        ];

        // Get all venues for selection
        $venues = Venue::orderBy('name')->get();
        
        return view('wedding_cards.edit', compact('card', 'templates', 'imageUrls', 'venues'));
    }

    public function update(Request $request, $uuid)
    {
        $card = WeddingCard::where('uuid', $uuid)->firstOrFail();
        
        // Check if the user owns this card
        if (Auth::id() !== $card->user_id) {
            return redirect()->route('wedding-cards.index')
                ->with('error', 'You are not authorized to update this card.');
        }
        
        $validator = Validator::make($request->all(), [
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'wedding_date' => 'required|date',
            'ceremony_time' => 'required|date_format:H:i',
            'venue_id' => 'required|exists:venues,id',
            'rsvp_contact_name' => 'required|string|max:255',
            'rsvp_contact_info' => 'required|string|max:255',
            'custom_message' => 'nullable|string',
            'template_id' => 'required|integer|min:1|max:2',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $venue = Venue::findOrFail($request->venue_id);
        
        $card->update([
            'groom_name' => $request->groom_name,
            'bride_name' => $request->bride_name,
            'wedding_date' => $request->wedding_date,
            'ceremony_time' => $request->ceremony_time,
            'venue_name' => $venue->name,
            'venue_address' => $venue->getFullAddressAttribute(),
            'rsvp_contact_name' => $request->rsvp_contact_name,
            'rsvp_contact_info' => $request->rsvp_contact_info,
            'custom_message' => $request->custom_message,
            'template_id' => $request->template_id,
        ]);
        
        return redirect()->route('wedding-cards.show', $card->uuid)
            ->with('success', 'Wedding card updated successfully!');
    }

    public function destroy($uuid)
    {
        $card = WeddingCard::where('uuid', $uuid)->firstOrFail();
        
        // Check if the user owns this card
        if (Auth::id() !== $card->user_id) {
            return redirect()->route('wedding-cards.index')
                ->with('error', 'You are not authorized to delete this card.');
        }
        
        $card->delete();
        
        return redirect()->route('wedding-cards.index')
            ->with('success', 'Wedding card deleted successfully!');
    }

    public function addComment(Request $request, $uuid)
    {
        $card = WeddingCard::where('uuid', $uuid)->firstOrFail();
        
        $validator = Validator::make($request->all(), [
            'commenter_name' => 'required|string|max:255',
            'commenter_email' => 'nullable|email|max:255',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $card->comments()->create([
            'commenter_name' => $request->commenter_name,
            'commenter_email' => $request->commenter_email,
            'comment' => $request->comment,
            'is_approved' => 1, // Changed from true to 1
        ]);
        
        return redirect()->back()
            ->with('success', 'Comment added successfully!');
    }
} 