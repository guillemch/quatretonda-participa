<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class BoothTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_online_voters_dont_get_booth_mode()
    {
        $response = $this->get('/');
        $response->assertSee('BoothMode = false')->assertStatus(200);
    }

    public function test_offline_voters_do_get_booth_mode()
    {
        $admin = User::find(1);
        $response = $this->actingAs($admin)->get('/');
        $response->assertSee('BoothMode = true')->assertStatus(200);
    }
}
