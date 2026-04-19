<?php

namespace App\Http\Requests;

use App\Models\Guest; // Import the Guest model
use Illuminate\Foundation\Http\FormRequest;

class StoreGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 1. MUST BE TRUE to allow the request to proceed
        return true; 
    }

    public function rules(): array
    {
        // Guests are standalone. booking_id is optional (if provided, will attach to that booking)
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'booking_id' => 'sometimes|exists:bookings,id',
        ];
    }
}