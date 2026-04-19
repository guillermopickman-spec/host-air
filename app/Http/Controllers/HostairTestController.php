<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HostairTestController extends Controller
{
    public function getBookings(): JsonResponse
    {
        try {
            // Optimized with eager loading for guests to prevent N+1 issues
            $bookings = Booking::with('guests')->get();

            return response()->json($bookings);
        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching bookings'], 500);
        }
    }
}