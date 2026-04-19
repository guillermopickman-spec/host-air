<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

class GuestModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest model mass assignment.
     *
     * @return void
     */
    public function test_guest_model_mass_assignment()
    {
        $booking = Booking::factory()->create();

        $guest = Guest::create([
            'name' => 'Test Guest',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
        ]);
        $guest->bookings()->attach($booking->id);

        $this->assertInstanceOf(Guest::class, $guest);
        $this->assertEquals('Test Guest', $guest->name);
        $this->assertEquals('test@example.com', $guest->email);
        $this->assertEquals('+1234567890', $guest->phone);
        $this->assertTrue($guest->bookings->contains($booking));
    }

    /**
     * Test guest belongs to booking relationship.
     *
     * @return void
     */
    public function test_guest_belongs_to_booking_relationship()
    {
        $booking = Booking::factory()->create();
        $guest = Guest::factory()->create();
        $guest->bookings()->attach($booking->id);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $guest->bookings);
        $this->assertTrue($guest->bookings->contains($booking));
    }

    /**
     * Test guest validation rules.
     *
     * @return void
     */
    public function test_guest_validation_rules()
    {
        // Test required fields
        $validator = Validator::make([
            'name' => '',
            'email' => 'test@example.com',
        ], Guest::rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->messages());

        $validator = Validator::make([
            'name' => 'Test Guest',
            'email' => '',
        ], Guest::rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->messages());

        // Test email format
        $validator = Validator::make([
            'name' => 'Test Guest',
            'email' => 'invalid-email',
        ], Guest::rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->messages());

        // Test valid data
        $booking = Booking::factory()->create(); // Still need a booking to create a guest, but not to validate directly
        $validator = Validator::make([
            'name' => 'Test Guest',
            'email' => 'test@example.com',
        ], Guest::rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Test guest phone field is optional.
     *
     * @return void
     */
    public function test_guest_phone_field_is_optional()
    {
        $booking = Booking::factory()->create();

        $guest = Guest::create([
            'name' => 'Test Guest',
            'email' => 'test@example.com',
        ]);
        $guest->bookings()->attach($booking->id);

        $this->assertNull($guest->phone);
        $this->assertDatabaseHas('guests', [
            'name' => 'Test Guest',
            'email' => 'test@example.com',
            'phone' => null,
        ]);
    }
}
