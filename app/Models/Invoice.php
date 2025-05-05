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
        'invoice_path',
        'invoice_submitted_at',
        'invoice_verified_at',
        'invoice_verified_by',
        'invoice_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'invoice_submitted_at' => 'datetime',
        'invoice_verified_at' => 'datetime',
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
}