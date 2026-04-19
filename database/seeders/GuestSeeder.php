<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guest;
use App\Models\Booking;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        // Create 30 independent guests
        $guests = [];
        for ($i = 0; $i < 30; $i++) {
            $guests[] = [
                'name' => $this->generateRandomName(),
                'email' => $this->generateRandomEmail(),
                'phone' => $this->generateRandomPhone(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Guest::insert($guests);
        
        // Now attach random guests to bookings via pivot
        $bookings = Booking::all();
        $allGuests = Guest::all();
        
        foreach ($bookings as $booking) {
            // Random number of guests for this booking: 0, 1, 2, or 3
            $numGuests = rand(0, 3);
            if ($numGuests > 0 && $allGuests->count() > 0) {
                $selectedGuests = $allGuests->random($numGuests);
                $booking->guests()->attach($selectedGuests);
            }
        }
    }

    private function generateRandomName(): string
    {
        $firstNames = ['John', 'Jane', 'Bob', 'Alice', 'Charlie', 'Diana', 'Eve', 'Frank', 'Grace', 'Henry', 'Ivy', 'Jack', 'Kate', 'Leo', 'Mia', 'Noah', 'Olivia', 'Paul', 'Quinn', 'Rose'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function generateRandomEmail(): string
    {
        $domains = ['example.com', 'test.com', 'mail.com', 'email.com'];
        $name = strtolower(str_replace(' ', '.', $this->generateRandomName()));
        $domain = $domains[array_rand($domains)];
        return $name . '@' . $domain;
    }

    private function generateRandomPhone(): string
    {
        // Random US/International format
        $formats = [
            '+1-555-XXXX',
            '+44-XXXX-XXXX',
            '+33-XXXX-XXXX',
        ];
        $format = $formats[array_rand($formats)];
        return str_replace(['XXXX', 'xxx'], rand(1000, 9999), $format);
    }
}
