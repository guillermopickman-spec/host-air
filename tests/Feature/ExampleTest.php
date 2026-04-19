<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

test('api returns successful response', function () {
    $response = $this->get('/api/bookings');
    $response->assertOk();
});
