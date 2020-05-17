<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Ballot;

class BallotTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_encrypt_and_decrypt_a_ballot()
    {
        $ballot = new Ballot();
        $encryptedBallot = $ballot->createBallot($this->fakeBallot());
        $ballot->ballot = $encryptedBallot;
        $decryptedBallot = $ballot->decrypt();

        $this->assertEquals($decryptedBallot[1][3], 1.0);
    }

    public function test_it_can_create_ballot_refs()
    {
        $ballot = new Ballot();
        $ref = $ballot->createRef();
        $refLength = (strlen($ref) === 10);

        $this->assertDatabaseMissing('ballots', ['ref' => $ref])
             ->assertTrue($refLength);
    }

    public function test_it_can_sign_and_check_a_ballot()
    {
        $ballot = new Ballot();
        $ballot->ref = $ballot->createRef();
        $ballot->ballot = $ballot->createBallot($this->fakeBallot());
        $ballot->signature = $ballot->createSignature();

        $check = $ballot->check();

        $this->assertTrue($check);
    }

    /**
     * Generates a fake ballot that should pass the tests
     *
     * @return array
     */
    private function fakeBallot()
    {
        return [
                    [
                        'id' => 1,
                        'options' => [
                            ['id' => 3]
                        ]
                    ],
                    [
                        'id' => 2,
                        'options' => [
                            ['id' => 4]
                        ]
                    ]
                ];
    }
}
