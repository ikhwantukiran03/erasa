<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
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
        'venue_id'
    ];

    /**
     * Get the venue associated with the package.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get the prices for the package.
     */
    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    /**
     * Get the package items for the package.
     */
    public function packageItems()
    {
        return $this->hasMany(PackageItem::class);
    }

    /**
     * Get the items included in this package.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'package_items')
            ->withPivot('description')
            ->withTimestamps();
    }

    /**
     * Get the minimum price for this package.
     * 
     * @return float
     */
    public function getMinPriceAttribute()
    {
        return $this->prices->min('price');
    }

    /**
     * Get the maximum price for this package.
     * 
     * @return float
     */
    public function getMaxPriceAttribute()
    {
        return $this->prices->max('price');
    }
}