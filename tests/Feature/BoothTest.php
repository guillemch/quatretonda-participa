<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\User;
use App\Edition;

class BoothTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        Parent::setUp();

        $this->artisan('db:seed');
    }

    public function test_online_voters_dont_get_booth_mode()
    {
        $response = $this->get('/');
        $response->assertSee('BoothMode = false')->assertSuccessful();
    }

    public function test_offline_voters_do_get_booth_mode()
    {
        $admin = User::first();
        $response = $this->actingAs($admin)->get('/');
        $response->assertSee('BoothMode = true')->assertSuccessful();
    }
}
