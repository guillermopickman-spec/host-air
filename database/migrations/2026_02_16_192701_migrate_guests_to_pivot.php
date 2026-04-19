<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing data: move booking_id from guests to pivot
        // This handles guests that already have a booking_id
        $guests = DB::table('guests')->whereNotNull('booking_id')->get();
        foreach ($guests as $guest) {
            DB::table('booking_guest')->insert([
                'booking_id' => $guest->booking_id,
                'guest_id' => $guest->id,
                'created_at' => now(),
            ]);
        }

        // Now drop the booking_id column from guests
        Schema::table('guests', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add booking_id column back
        Schema::table('guests', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
        });

        // Drop pivot table
        Schema::dropIfExists('booking_guest');
    }
};
