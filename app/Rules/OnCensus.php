<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Limit;

class OnCensus implements Rule
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
     * Checks if ID exists on the current edition census
     * If an ID does not exist, it logs the error to prevent
     * brutce force attacks to guess valid IDs
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->voter) {
            Limit::logAction('IDFailedLookUp', null, $value);
            return false;
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
        return __('validation.custom.SID.on_census');
    }
}
