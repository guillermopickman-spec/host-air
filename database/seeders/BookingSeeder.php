<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['pending', 'confirmed', 'cancelled', 'checked_in', 'checked_out'];
        $now = Carbon::now();

        for ($i = 0; $i < 20; $i++) {
            // Random check-in date: from 30 days ago to 60 days in future
            $checkinDays = rand(-30, 60);
            $checkinAt = $now->copy()->addDays($checkinDays);

            // Random stay duration: 1-14 days
            $stayDuration = rand(1, 14);
            $checkoutAt = $checkinAt->copy()->addDays($stayDuration);

            // Random status
            $status = $statuses[array_rand($statuses)];

            Booking::create([
                'checkin_at' => $checkinAt,
                'checkout_at' => $checkoutAt,
                'status' => $status,
            ]);
        }
    }
}
