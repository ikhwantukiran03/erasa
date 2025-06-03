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
        'is_staff_reply' => 'boolean',
    ];

    /**
     * Set the is_staff_reply attribute.
     * Ensures proper boolean conversion for PostgreSQL.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setIsStaffReplyAttribute($value)
    {
        $this->attributes['is_staff_reply'] = $value ? 'true' : 'false';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 