<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'venue_id',
        'package_id',
        'price_id',
        'booking_date',
        'session',
        'type',
        'status',
        'expiry_date',
        'handled_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'booking_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the venue associated with the booking.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get the package associated with the booking.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    
    /**
     * Get the price associated with the booking.
     */
    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    /**
     * Get the staff member who handled this booking.
     */
    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Get the invoices associated with the booking.
     */
    public function invoice()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the total amount paid for this booking.
     *
     * @return float
     */
    public function getTotalPaidAttribute()
    {
        // This is the corrected method that will work with your database structure
        return $this->invoice()
            ->where('status', 'verified')
            ->sum('amount') ?? 0;
    }

    /**
     * Check if all payments have been completed.
     *
     * @return bool
     */
    public function isFullyPaid()
    {
        $totalPrice = 0;
        
        if ($this->price_id) {
            $price = Price::find($this->price_id);
            if ($price) {
                $totalPrice = $price->price;
            }
        } elseif ($this->package) {
            $totalPrice = $this->package->min_price;
        }
        
        return $this->getTotalPaidAttribute() >= $totalPrice;
    }

    /**
     * Get the customization requests for the booking.
     */
    public function customizations()
    {
        return $this->hasMany(Customization::class);
    }

    /**
     * Scope a query to only include ongoing bookings.
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope a query to only include completed bookings.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include cancelled bookings.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    
    /**
     * Check if the booking is for a wedding.
     *
     * @return bool
     */
    public function isWedding()
    {
        return $this->type === 'wedding';
    }
    
    /**
     * Check if the booking is for a viewing.
     *
     * @return bool
     */
    public function isViewing()
    {
        return $this->type === 'viewing';
    }
    
    /**
     * Check if the booking is for a reservation.
     *
     * @return bool
     */
    public function isReservation()
    {
        return $this->type === 'reservation';
    }

    /**
     * Get formatted booking date.
     *
     * @return string
     */
    public function getFormattedBookingDateAttribute()
    {
        return $this->booking_date->format('d M Y');
    }
    
    /**
     * Get session display name.
     *
     * @return string
     */
    public function getSessionDisplayAttribute()
    {
        return ucfirst($this->session);
    }
    
    /**
     * Get status display name.
     *
     * @return string
     */
    public function getStatusDisplayAttribute()
    {
        return ucfirst($this->status);
    }
    
    /**
     * Get type display name.
     *
     * @return string
     */
    public function getTypeDisplayAttribute()
    {
        return ucfirst($this->type);
    }

    /**
     * Check if the booking has an invoice.
     *
     * @return bool
     */
    public function hasInvoice()
    {
        return $this->invoice()->exists();
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}