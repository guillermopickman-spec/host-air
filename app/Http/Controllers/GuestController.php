<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Guest;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GuestController extends Controller
{
    /**
     * Display a listing of all guests.
     */
    public function index(): JsonResponse
    {
        // Return all guests with their bookings relationship
        $guests = Guest::with('bookings')->get();
        return response()->json($guests);
    }

    /**
     * Store a newly created guest in storage.
     * Optionally attach to a booking if booking_id provided.
     */
    public function store(StoreGuestRequest $request): JsonResponse
    {
        try {
            $guest = Guest::create($request->validated());

            // If booking_id is provided, attach guest to that booking
            if ($request->has('booking_id')) {
                $booking = Booking::find($request->booking_id);
                if ($booking) {
                    $booking->guests()->attach($guest);
                    $guest->load('bookings'); // Reload with relationships
                }
            }

            return response()->json([
                'message' => 'Guest created successfully',
                'guest' => $guest->load('bookings')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating guest: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create guest'], 500);
        }
    }

    /**
     * Display the specified guest.
     */
    public function show(Guest $guest): JsonResponse
    {
        return response()->json($guest->load('bookings'));
    }

    /**
     * Update the specified guest in storage.
     */
    public function update(UpdateGuestRequest $request, Guest $guest): JsonResponse
    {
        try {
            $guest->update($request->validated());
            return response()->json([
                'message' => 'Guest updated successfully',
                'guest' => $guest->load('bookings')
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating guest: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update guest'], 500);
        }
    }

    /**
     * Remove the specified guest from storage.
     * This also detaches from all bookings (cascade on pivot).
     */
    public function destroy(Guest $guest): JsonResponse
    {
        try {
            $guest->delete();
            return response()->json(['message' => 'Guest deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting guest: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete guest'], 500);
        }
    }

    /**
     * Attach a guest to a booking.
     * POST /api/guests/{guest}/bookings/{booking}
     */
    public function attachToBooking(Guest $guest, Booking $booking): JsonResponse
    {
        try {
            // Check if already attached
            if ($guest->bookings()->where('booking_id', $booking->id)->exists()) {
                return response()->json([
                    'message' => 'Guest is already associated with this booking'
                ], 200);
            }

            $guest->bookings()->attach($booking);
            
            return response()->json([
                'message' => 'Guest attached to booking successfully',
                'guest' => $guest->load('bookings')
            ]);
        } catch (\Exception $e) {
            Log::error('Error attaching guest to booking: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to attach guest to booking'], 500);
        }
    }

    /**
     * Detach a guest from a booking.
     * DELETE /api/guests/{guest}/bookings/{booking}
     */
    public function detachFromBooking(Guest $guest, Booking $booking): JsonResponse
    {
        try {
            $guest->bookings()->detach($booking);
            
            return response()->json([
                'message' => 'Guest detached from booking successfully',
                'guest' => $guest->load('bookings')
            ]);
        } catch (\Exception $e) {
            Log::error('Error detaching guest from booking: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to detach guest from booking'], 500);
        }
    }
}
