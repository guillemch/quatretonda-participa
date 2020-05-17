<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SMSVerify implements Rule
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
     * Checks if the SMS code is the same as the code sent to the voter
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $voterToken = ($this->voter) ? $this->voter->SMS_token : null;
        $providedToken = $value;
        return ($voterToken === $providedToken && !empty($voterToken));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.SMS_code.sms_code');
    }
}
