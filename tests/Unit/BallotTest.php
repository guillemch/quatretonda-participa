<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Ballot;

class BallotTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_encrypt_and_decrypt_a_ballot()
    {

    }

    public function test_it_can_create_ballot_refs()
    {
        $ballot = new Ballot();
        $ref = $ballot->createRef();

        $this->assertDatabaseMissing('ballots', ['ref' => $ref]);
    }

    public function test_it_can_sign_and_check_a_ballot()
    {

    }

    public function test_it_can_cast_a_ballot()
    {

    }
}
