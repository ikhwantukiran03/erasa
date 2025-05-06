<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'package_item_id',
        'customization',
        'status',
        'staff_notes',
        'handled_by',
        'handled_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'handled_at' => 'datetime',
    ];

    /**
     * Get the booking that owns the customization request.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the package item that is being customized.
     */
    public function packageItem()
    {
        return $this->belongsTo(PackageItem::class);
    }

    /**
     * Get the staff member who handled this customization request.
     */
    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Scope a query to only include pending customization requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved customization requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected customization requests.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}