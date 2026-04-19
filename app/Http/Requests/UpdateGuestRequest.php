<?php

namespace App\Http\Requests;

use App\Models\Guest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        // Guests are standalone - only name, email, phone can be updated
        // booking association is managed separately via attach/detach endpoints
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'nullable|string|max:20',
        ];
    }
}