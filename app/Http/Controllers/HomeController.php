<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Feedback;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class HomeController extends Controller
{
    /**
     * Display the homepage for Enak Rasa Wedding Hall.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get 5 random gallery images
        $galleryImages = Gallery::inRandomOrder()
            ->take(5)
            ->get();
            
        // If there are fewer than 5 images, create placeholder items
        if ($galleryImages->count() < 5) {
            // Create default gallery items with placeholder URLs
            $defaults = [
                ['title' => 'Wedding Venue', 'description' => 'Our elegant venue for your perfect day', 'image_url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=3270&auto=format&fit=crop'],
                ['title' => 'Wedding Ceremony', 'description' => 'Beautiful settings for your ceremony', 'image_url' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=3269&auto=format&fit=crop'],
                ['title' => 'Wedding Decorations', 'description' => 'Stunning decorations for your event', 'image_url' => 'https://images.unsplash.com/photo-1529636798458-92182e662485?q=80&w=3269&auto=format&fit=crop'],
                ['title' => 'Catering Excellence', 'description' => 'Delicious food for your reception', 'image_url' => 'https://images.unsplash.com/photo-1470204639138-9b335f10beec?q=80&w=3270&auto=format&fit=crop'],
                ['title' => 'Happy Couples', 'description' => 'Celebrating love and happiness', 'image_url' => 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?q=80&w=2940&auto=format&fit=crop'],
            ];
            
            // Add only as many default items as needed to reach 5
            $neededDefaults = 5 - $galleryImages->count();
            
            for ($i = 0; $i < $neededDefaults; $i++) {
                $default = $defaults[$i];
                $galleryItem = new \stdClass();
                $galleryItem->title = $default['title'];
                $galleryItem->description = $default['description'];
                $galleryItem->image_url = $default['image_url'];
                $galleryImages->push($galleryItem);
            }
        }
        
        // Get top 5 published feedback with highest ratings
        $topFeedback = Feedback::where('status', 'published')
            ->with(['booking.user', 'booking.venue'])
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get active promotions for banner
        $activePromotions = Promotion::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->take(3) // Limit to 3 promotions for banner rotation
            ->get();
        
        return view('home', compact('galleryImages', 'topFeedback', 'activePromotions'));
    }
}