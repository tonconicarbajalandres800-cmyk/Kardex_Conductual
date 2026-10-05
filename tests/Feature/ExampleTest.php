<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true; // Ejecuta los seeders automáticamente

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->followingRedirects()->get('/login');
        $response->assertStatus(200);
    }
}