<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_user_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->name}");

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_view_settings(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/settings');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_settings(): void
    {
        $response = $this->get('/settings');

        $response->assertRedirect('/login');
    }
}
