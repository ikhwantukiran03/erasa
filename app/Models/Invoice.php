<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'type',
        'invoice_path',
        'invoice_public_id',
        'amount',
        'invoice_submitted_at',
        'invoice_verified_at',
        'invoice_verified_by',
        'verified_by',
        'invoice_notes',
        'admin_notes',
        'due_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'invoice_submitted_at' => 'datetime',
        'invoice_verified_at' => 'datetime',
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the booking that owns the invoice.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who verified the invoice.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'invoice_verified_by');
    }

    /**
     * Get the user who verified the invoice (alternative relationship name).
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope a query to only include pending invoices.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include verified invoices.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope a query to only include rejected invoices.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if the invoice is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the invoice is verified.
     *
     * @return bool
     */
    public function isVerified()
    {
        return $this->status === 'verified';
    }

    /**
     * Check if the invoice is rejected.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the invoice is overdue.
     *
     * @return bool
     */
    public function isOverdue()
    {
        // If no due date is set or invoice is already verified, it's not overdue
        if (!$this->due_date || $this->isVerified()) {
            return false;
        }
        
        // Add 24-hour grace period to the due date
        $gracePeriodEnd = $this->due_date->copy()->addDay();
        
        // Invoice is overdue only if current time is past the grace period
        return now()->gt($gracePeriodEnd);
    }
}