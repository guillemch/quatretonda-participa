<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Browser\Pages\Booth;
use App\Voter;
use App\User;

class BoothTest extends DuskTestCase
{

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_a_voter_can_cast_a_ballot_online()
    {
        $this->artisan('migrate:fresh', ['--seed' => true]);

        // Get a voter that hasn't voted yet
        $voter = Voter::where('ballot_cast', 0)->first();

        $this->browse(function (Browser $browser) use ($voter) {
            $browser->visit(new Booth)
                    ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $voter->SID) // Voter SID
                    ->press('@vote')
                    ->waitFor('.ballot-verify')
                    ->type('phone', rand(600000000, 799999999)) // Random Spanish phone
                    ->click('@smsRequest')
                    ->waitFor('@cast');

            $smsCode = Voter::find($voter->id)->SMS_token;

            $browser->type('sms_code', $smsCode)
                    ->click('@cast')
                    ->waitFor('.ballot-confirmation')
                    ->assertVisible('.receipt'); // Change to CSS selector
        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_a_voter_can_cast_a_ballot_in_person()
    {
        $admin = User::find(1);

        // Get voter with ballot_cast = 0
        $voter = Voter::where('ballot_cast', 0)->first();

        $this->browse(function (Browser $browser) use ($voter, $admin) {
            $browser->loginAs($admin)
                    ->visit(new Booth)
                    ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $voter->SID) // Voter SID
                    ->press('@vote')
                    ->waitFor('.verify-in-person')
                    ->click('@cast')
                    ->waitFor('.ballot-confirmation')
                    ->assertVisible('.receipt')
                    ->logout();
        });
    }

    public function test_IDs_can_be_entered_with_spaces_or_other_characters()
    {
        // Get a voter that hasn't voted yet
        $voter = Voter::where('ballot_cast', 0)->first();

        $this->browse(function (Browser $browser) use ($voter) {
            $SID = $voter->SID;
            $SID = substr($SID, 0, 8) . '-' . substr($SID, 8);

            $browser->visit(new Booth)
                    ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $SID)
                    ->press('@vote')
                    ->waitFor('.ballot-verify')
                    ->type('phone', rand(600000000, 799999999)) // Random Spanish phone
                    ->click('@smsRequest')
                    ->waitFor('@cast');

            $smsCode = Voter::find($voter->id)->SMS_token;

            $browser->type('sms_code', $smsCode)
                    ->click('@cast')
                    ->waitFor('.ballot-confirmation')
                    ->assertVisible('.receipt'); // Change to CSS selector
        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_an_invalid_ID_displays_an_error()
    {
        $faker = \Faker\Factory::create();

        $this->browse(function (Browser $browser) use ($faker) {
            $browser->visit(new Booth)
                    ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $faker->numberBetween(10000000,99999999) . $faker->randomLetter)
                    ->press('@vote')
                    ->waitFor('#errorsModal')
                    ->assertVisible('#errorsModal');
        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_a_single_ID_cannot_vote_more_than_once()
    {
        // Get a voter that has voted
        $voter = Voter::where('ballot_cast', 1)->first();

        $this->browse(function (Browser $browser) use ($voter) {
            $browser->visit(new Booth)
                    ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $voter->SID) // Voter SID
                    ->press('@vote')
                    ->waitFor('#errorsModal')
                    ->assertVisible('#errorsModal');
        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_a_single_phone_cannot_vote_more_than_once()
    {
        $voter = Voter::where('SMS_verified', 1)->first();
        $anotherVoter = Voter::where('ballot_cast', 0)->first();

        $this->browse(function (Browser $browser) use ($voter, $anotherVoter) {
            $phone = explode('.', $voter->SMS_phone);
            $phone = $phone[1];

            $browser->visit(new Booth)
                    ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $anotherVoter->SID) // Voter SID
                    ->press('@vote')
                    ->waitFor('.ballot-verify')
                    ->type('phone', $phone)
                    ->press('@smsRequest')
                    ->waitFor('#errorsModal')
                    ->assertVisible('#errorsModal');
        });
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_a_phone_can_be_reused_if_it_has_not_cast_a_ballot()
    {
        // Request an SMS code but do not go through with voting
        $firstVoter = Voter::where('ballot_cast', 0)->first();
        $secondVoter = Voter::where('ballot_cast', 0)->where('SID', '!=', $firstVoter->SID)->first();
        $phone = rand(600000000, 799999999);

        $this->browse(function (Browser $first, Browser $second) use ($firstVoter, $secondVoter, $phone) {
            $first->visit(new Booth)
                   ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $firstVoter->SID)
                    ->press('@vote')
                    ->waitFor('.ballot-verify')
                    ->type('phone', $phone) // Random Spanish phone
                    ->click('@smsRequest')
                    ->waitFor('@cast');

            $second->visit(new Booth)
                    ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $secondVoter->SID)
                    ->press('@vote')
                    ->waitFor('.ballot-verify')
                    ->type('phone', $phone) // Same as first voter
                    ->click('@smsRequest')
                    ->waitFor('@cast');

            $firstSmsCode = Voter::find($firstVoter->id)->SMS_token;
            $secondSmsCode = Voter::find($secondVoter->id)->SMS_token;

            $second->type('sms_code', $secondSmsCode)
                    ->click('@cast')
                    ->waitFor('.ballot-confirmation')
                    ->assertVisible('.receipt');

            $first->type('sms_code', $firstSmsCode)
                    ->click('@cast')
                    ->waitFor('#errorsModal')
                    ->assertVisible('#errorsModal');
        });

    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_a_voter_cannot_vote_without_a_valid_sms_code_online()
    {
        // Get a voter that hasn't voted yet
        $voter = Voter::where('ballot_cast', 0)->first();

        $this->browse(function (Browser $browser) use ($voter) {
            $browser->visit(new Booth)
                    ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $voter->SID)
                    ->press('@vote')
                    ->waitFor('.ballot-verify')
                    ->type('phone', rand(600000000, 799999999)) // Random Spanish phone
                    ->click('@smsRequest')
                    ->waitFor('@cast');

            $fakeSmsCode = rand(100000, 999999);

            $browser->type('sms_code', $fakeSmsCode)
                    ->click('@cast')
                    ->waitFor('#errorsModal')
                    ->assertVisible('#errorsModal');
        });
       }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_a_single_ip_cannot_vote_more_than_x_times()
    {
        $maxPerIP = config('participa.max_per_ip', 3);

        for($i = 1; $i <= $maxPerIP; $i++) {
            \App\Limit::logAction('vote');
        }

        $voter = Voter::where('ballot_cast', 0)->first();

        $this->browse(function (Browser $browser) use ($voter) {
            $browser->visit(new Booth)
                    ->waitFor('.booth')
                    ->fillOutBallot()
                    ->type('identification', $voter->SID)
                    ->press('@vote')
                    ->waitFor('#errorsModal')
                    ->assertVisible('#errorsModal');
        });
    }
}
