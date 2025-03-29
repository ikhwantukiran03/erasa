<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    /*
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'whatsapp',
        'email',
        'venue_id',
        'package_id',
        'event_date',
        'message',
        'status'
    ];
    /*
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */

     /**
      * Get the venue associated with the request.
      */

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }   

    /**
     * Get the package associated with the request.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
