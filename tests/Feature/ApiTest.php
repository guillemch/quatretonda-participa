<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Edition;
use App\Voter;
use App\User;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        Parent::setUp();

        $this->artisan('db:seed');
    }

    public function test_it_fetches_the_ballot()
    {
        $response = $this->json('GET', '/api/ballot');

        $response->assertSuccessful()
                 ->assertJson([
                     'id' => true,
                     'name' => true
                 ]);
    }

    public function test_it_verifies_voter_information()
    {
        $response = $this->json('POST', '/api/precheck', [
            'SID' => 'Invalid SID'
        ]);
        $response->assertStatus(422);

        $voter = $this->newVoter();
        $response = $this->json('POST', '/api/precheck', [
            'SID' => $voter['SID']
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);
    }

    public function test_it_requests_an_sms_code()
    {
        $voter = $this->newVoter();;
        $response = $this->json('POST', '/api/request_sms', [
            'SID' => $voter['SID'],
            'country_code' => 34,
            'phone' => rand(600000000, 799999999)
        ]);

        $response->assertSuccessful()
                 ->assertJson([
                     'success' => true
                 ]);
    }

    public function test_it_casts_a_ballot_as_online_voter()
    {
        $newVoter = $this->newVoter();

        $voter = Voter::find($newVoter['object']->id);
        $voter->SMS_phone = '34.612345678';
        $voter->SMS_token = '123456';
        $voter->SMS_attempts = 1;
        $voter->SMS_time = Carbon::now();
        $voter->save();

        $response = $this->json('POST', '/api/cast_ballot', [
            'SID' => $newVoter['SID'],
            'country_code' => '34',
            'phone' => '612345678',
            'SMS_code' => $voter->SMS_token,
            'ballot' => $this->fakeValidBallot()
        ]);

        $response->assertSuccessful()
                 ->assertJson([
                     'success' => true
                 ]);
    }

    public function test_it_casts_a_ballot_as_offline_voter()
    {
        $admin = User::first();
        $voter = $this->newVoter();

        $response = $this->actingAs($admin)->json('POST', '/api/cast_ballot', [
            'SID' => $voter['SID'],
            'ballot' => $this->fakeValidBallot()
        ]);

        $response->assertSuccessful()
                 ->assertJson([
                     'success' => true
                 ]);
    }

    public function test_it_fails_when_ballot_is_invalid()
    {
        $newVoter = $this->newVoter();

        $voter = Voter::find($newVoter['object']->id);
        $voter->SMS_phone = '34.612345678';
        $voter->SMS_token = '123456';
        $voter->SMS_attempts = 1;
        $voter->SMS_time = Carbon::now();
        $voter->save();

        $response = $this->json('POST', '/api/cast_ballot', [
            'SID' => $newVoter['SID'],
            'country_code' => '34',
            'phone' => '612345678',
            'sms_code' => $voter->SMS_token,
            'ballot' => $this->fakeInvalidBallot()
        ]);

        $response->assertStatus(422);
    }

    public function test_it_fails_when_SMS_code_is_invalid()
    {
        $newVoter = $this->newVoter();

        $voter = Voter::find($newVoter['object']->id);
        $voter->SMS_phone = '34.612345678';
        $voter->SMS_token = '123456';
        $voter->SMS_attempts = 1;
        $voter->SMS_time = Carbon::now();
        $voter->save();

        $response = $this->json('POST', '/api/cast_ballot', [
            'SID' => $newVoter['SID'],
            'country_code' => '34',
            'phone' => '612345678',
            'sms_code' => '654321'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Generates a fake ballot that should pass the tests
     *
     * @return array
     */
    private function fakeValidBallot()
    {
        $ballot = Edition::current('ballot');
        $validBallot = [];
        $questionKey = 0;

        foreach ($ballot->questions as $question) {
            $questionKey++;
            $times = rand($question->min_options, $question->max_options);
            $validBallot[$questionKey] = ['id' => $question->id, 'options' => array()];

            for($i = 0; $i < $times; $i++) {
                $randomOption = $question->options->slice($i, 1)->first();
                $validBallot[$questionKey]['options'][$i]['id'] = $randomOption->id;
            }
        }

        return $validBallot;
    }

    /**
     * Generates a fake, invalid ballot that should not pass the tests
     *
     * @return array
     */
    private function fakeInvalidBallot()
    {
        $ballot = Edition::current('ballot');
        $invalidBallot = [];
        $questionKey = 0;

        foreach ($ballot->questions as $question) {
            $questionKey++;
            $times = $question->max_options + 1;
            $invalidBallot[$questionKey] = ['id' => $question->id, 'options' => array()];

            for($i = 1; $i <= $times; $i++) {
                $randomOption = $question->options->shuffle()->first();
                $invalidBallot[$questionKey]['options'][$i - 1]['id'] = $randomOption->id;
            }
        }

        return $invalidBallot;
    }

    /**
     * Creates a new voter
     *
     * @return array
     */
    private function newVoter()
    {
      $SID = rand(100000, 999999);
      $editionId = Edition::current()->id;
      $voter = new Voter();
      $voter->edition_id = $editionId;
      $voter->SID = config('participa.hashed_SIDs') ? hash('sha256', $SID) : $SID;
      $voter->save();

      return [
        'SID' => $SID,
        'object' => $voter
      ];
    }
}
