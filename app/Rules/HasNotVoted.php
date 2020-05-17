<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class HasNotVoted implements Rule
{
    /* The voter instance */
    protected $voter;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($voter)
    {
        $this->voter = $voter;
    }

    /**
     * Checks if ID has already cast a ballot on the current edition
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->voter) return true;

        return ($this->voter->ballot_cast === 0);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.SID.has_not_voted');
    }
}
