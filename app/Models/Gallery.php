<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $fillable = [
        'venue_id',
        'title',
        'description',
        'image_path',
        'image_url',
        'is_featured',
        'display_order',
        'source',
     ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
    ];

        /**
        * Get the venue that owns the gallery.
        */
        public function venue()
        {
            return $this->belongsTo(Venue::class);
        }

    /**
     * Scope a query to only include featured galleries.
     */
    public function scopeFeatured($query)
    {
        return $query->whereRaw('is_featured = true');
    }
}
