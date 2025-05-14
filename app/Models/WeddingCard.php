<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeddingCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'groom_name',
        'bride_name',
        'wedding_date',
        'ceremony_time',
        'venue_name',
        'venue_address',
        'rsvp_contact_name',
        'rsvp_contact_info',
        'custom_message',
        'template_id',
        'uuid',
        'user_id'
    ];

    protected $casts = [
        'wedding_date' => 'date',
        'ceremony_time' => 'datetime:H:i',
    ];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->uuid = \Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(WeddingCardComment::class);
    }
} 