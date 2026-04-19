<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\BookingStatus;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'checkin_at',
        'checkout_at',
        'status',
    ];

    /**
     * The guests that belong to the booking.
     */
    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(Guest::class, 'booking_guest');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checkin_at' => 'datetime',
            'checkout_at' => 'datetime',
            'status' => BookingStatus::class,
        ];
    }
}
