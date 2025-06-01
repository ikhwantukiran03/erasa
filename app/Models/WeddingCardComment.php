<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeddingCardComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_card_id',
        'commenter_name',
        'commenter_email',
        'comment',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'integer'
    ];

    public function weddingCard()
    {
        return $this->belongsTo(WeddingCard::class);
    }
}
