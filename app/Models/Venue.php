<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
    ];

    /**
     * Get the full address of the venue.
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address_line_1;
        
        if ($this->address_line_2) {
            $address .= ', ' . $this->address_line_2;
        }
        
        return $address . ', ' . $this->city . ', ' . $this->state . ' ' . $this->postal_code;
    }

    // Add this method to app/Models/Venue.php inside the Venue class

/**
 * Get the gallery images for the venue.
 */
public function galleries()
{
    return $this->hasMany(Gallery::class);
}
}