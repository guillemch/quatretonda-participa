<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\User;
use App\Edition;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        Parent::setUp();

        $this->artisan('db:seed');
    }

    public function test_it_displays_booth_when_voting_is_open()
    {
        $edition = new Edition();
        $edition->name = 'Test Voting is Open';
        $edition->start_date = Carbon::today()->subWeeks(1);
        $edition->end_date = Carbon::today()->addWeeks(1);
        $edition->publish_results = Carbon::today()->addWeeks(1);
        $edition->published = 1;
        $edition->save();

        $response = $this->get('/')
                         ->assertSuccessful()
                         ->assertSee('window.BoothMode');
    }

    public function test_it_displays_about_page_when_voting_is_pending()
    {
        $edition = new Edition();
        $edition->name = 'Test Voting is Pending';
        $edition->start_date = Carbon::today()->addWeeks(1);
        $edition->end_date = Carbon::today()->addWeeks(2);
        $edition->publish_results = Carbon::today()->addWeeks(2);
        $edition->about = 'About Page';
        $edition->published = 1;
        $edition->save();

        $response = $this->get('/')
                         ->assertSuccessful()
                         ->assertSee('About Page');
    }

    public function test_it_displays_placeholder_when_results_are_pending()
    {
        $edition = new Edition();
        $edition->name = 'Test Results are Pending';
        $edition->start_date = Carbon::today()->subWeeks(2);
        $edition->end_date = Carbon::today()->subWeeks(1);
        $edition->publish_results = Carbon::today()->addWeeks(1);
        $edition->published = 1;
        $edition->save();

        $response = $this->get('/')
                         ->assertSuccessful()
                         ->assertSee('results-pending');
    }

    public function test_it_displays_results_when_results_are_in()
    {
        $edition = new Edition();
        $edition->name = 'Test Results Page';
        $edition->start_date = Carbon::today()->subWeeks(2);
        $edition->end_date = Carbon::today()->subWeeks(1);
        $edition->publish_results = Carbon::today()->subWeeks(1);
        $edition->published = 1;
        $edition->save();

        $response = $this->get('/')
                         ->assertSuccessful()
                         ->assertSee('results-pending');

        Cache::forever('last_tally_finished' . $edition->id, time());
        Cache::forever('last_tally_integrity' . $edition->id, true);

        $response->assertSee('results');
    }

}
