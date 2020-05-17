<?php

use Illuminate\Database\Seeder;
use App\Voter;
use App\Ballot;
use App\Question;

class BallotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get 30% of the census
        $census = Voter::all()->count();
        $thirtyPercent = 30 * $census / 100;

        $voters = Voter::orderByRaw('rand()')->limit($thirtyPercent)->get()->each(function ($voter) {
            $voter->ballot_cast = 1;
            $voter->ballot_time = date('Y-m-d H:i:s');
            $voter->signature = $voter->createSignature();
            $voter->by_user_id = 1;
            $voter->save();

            $ballot = new Ballot();
            $ballot->edition_id = $voter->edition_id;
            $ballot->ref = $ballot->createRef();
            $ballot->ballot = $this->generateRandomBallot($voter->edition_id);
            $ballot->cast_at = date("Y-m-d H:i:s");
            $ballot->signature = $ballot->createSignature();
            $ballot->by_user_id = 1;

            if (config('participa.anonymous_voting') === false) {
                $ballot->voter_id = $voter->id;
                $ballot->ip_address = '1.1.1.1';
                $ballot->user_agent = 'Seeder';
            }

            $ballot->save();

        });

    }

    private function generateRandomBallot($editionId)
    {
        $ballot = [];
        $faker = Faker\Factory::create();

        $questions = Question::where('edition_id', $editionId)->get();

        foreach ($questions as $question) {
            $maxToSelect = $question->max_options;
            $minToSelect = $question->min_options;

            $count = $faker->numberBetween($minToSelect, $maxToSelect);

            $options = $question->options()->get()->toArray();

            $selectedOptions = $faker->randomElements($options, $count);

            foreach ($selectedOptions as $option){
                $ballot[$question->id][] = $option['id'];
            }
        }
        return encrypt($ballot);
    }
}
