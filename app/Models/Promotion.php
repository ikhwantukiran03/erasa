<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'discount',
        'start_date',
        'end_date',
        'cloudinary_image_id',
        'cloudinary_image_url',
        'package_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount' => 'decimal:2'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
