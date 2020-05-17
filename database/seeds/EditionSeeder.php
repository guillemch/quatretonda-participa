<?php

use Illuminate\Database\Seeder;

class EditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Edition::class, 1)->create()->each(function (App\Edition $edition) {
            $edition->voters()->saveMany(factory(App\Voter::class, 2000)->make());
            $questions = $edition->questions()->saveMany(factory(App\Question::class, 4)->make());

            foreach ($questions as $question){
                $randomOptions = rand(3,10);
                $question->options()->saveMany(factory(App\Option::class, $randomOptions)->make());
            }
        });
    }
}
