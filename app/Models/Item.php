<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
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
        'category_id',
    ];

    /**
     * Get the category that owns the item.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the package items for the item.
     */
    public function packageItems()
    {
        return $this->hasMany(PackageItem::class);
    }

    /**
     * Get the packages that contain this item.
     */
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_items')
            ->withPivot('description')
            ->withTimestamps();
    }
}