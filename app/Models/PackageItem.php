<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'package_id',
        'item_id',
        'description',
    ];

    /**
     * Get the package that owns the package item.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the item that owns the package item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}