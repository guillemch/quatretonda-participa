<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Question;

class BallotValidity implements Rule
{
    /* The edition ID */
    protected $editionId;

    /* The error message to display */
    protected $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($editionId)
    {
        $this->editionId = $editionId;
        $this->errorMessage = __('validation.custom.ballot.ballot_validity');
    }

    /**
     * Checks that the ballot submitted by the user does not contain
     * any illegal values, such responses to non-existing questions
     * or selecting more options than allowed by the rules
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $ballotQuestions)
    {
        /* The value given must be an array */
        if (!is_array($ballotQuestions)) {
            return false;
        }

        /* Fetch the edition's questions to ensure */
        $validQuestions = Question::where('edition_id', $this->editionId)->with('options')->get();

        foreach ($ballotQuestions as $ballotQuestion) {
            /* The question must contain an ID and options key */
            if (!isset($ballotQuestion['id']) || !isset($ballotQuestion['options'])){
                return false;
            }

            /* Find the user's input question on the edition questions */
            $question = $validQuestions->filter(function ($validQuestion, $key) use ($ballotQuestion) {
                return $validQuestion->id === $ballotQuestion['id'];
            })->first();

            /* If a question is not found, the ballot is invalid */
            if (empty($question)) {
                return false;
            }

            /* If a question has more or fewer answers than allowed,
               inform the user via a more specific error message */
            if (count($ballotQuestion['options']) > $question->max_options) {
                $this->errorMessage = __('validation.custom.ballot.ballot_max');
                return false;
            }

            if (count($ballotQuestion['options']) < $question->min_options) {
                $this->errorMessage = trans_choice('validation.custom.ballot.ballot_min', $question->min_options, ['min_options' => $question->min_options , 'question' => $question->question]);
                return false;
            }

            foreach ($ballotQuestion['options'] as $ballotOption) {
                /* Find the selected options among the question's valid answers */
                $option = $question->options->filter(function ($validOption, $key) use ($ballotOption) {
                    return $validOption->id === $ballotOption['id'];
                })->first();

                /* If a selected option is not found, the ballot is invalid */
                if (empty($option)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
