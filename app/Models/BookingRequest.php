<?php
// app/Models/BookingRequest.php updates
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'whatsapp_no',
        'email',
        'type',
        'status',
        'user_id',
        'package_id',
        'price_id', // Add price_id to fillable
        'venue_id',
        'event_date',
        'session', // Added the session field
        'message',
        'admin_notes',
        'handled_by',
        'handled_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'event_date' => 'date',
        'handled_at' => 'datetime',
    ];

    /**
     * Get the user that owns the booking request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package associated with the booking request.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    
    /**
     * Get the price associated with the booking request.
     */
    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    /**
     * Get the venue associated with the booking request.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Get the staff member who handled this request.
     */
    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Scope a query to only include pending booking requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved booking requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected booking requests.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include cancelled booking requests.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}