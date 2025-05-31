<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'is_staff_reply',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'is_staff_reply' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 