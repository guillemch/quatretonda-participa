<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class ApiTest extends TestCase
{

    public function setUp()
    {
        //$this->artisan('migrate:refresh', ['--seed' => true]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_fetches_the_ballot()
    {
        /*$response = $this->json('GET', '/ballot');

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => true,
                     'name' => true,
                     'questions' => true,
                 ]);*/
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_requests_an_sms_code()
    {

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_casts_a_ballot_as_online_voter()
    {

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_casts_a_ballot_as_offline_voter()
    {

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_verifies_voter_information()
    {

    }

}
