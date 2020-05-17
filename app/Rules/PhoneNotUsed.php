<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Voter;

class PhoneNotUsed implements Rule
{
    /* The edition ID */
    protected $editionId;

    /* The properly formatted phone */
    protected $phone;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($editionId, $phone)
    {
        $this->editionId = $editionId;
        $this->phone = $phone;
    }

    /**
     * Checks if the phone provided has already been associated
     * with a vote on the current eidition
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $used = Voter::where('SMS_phone', $this->phone)
                     ->where('SMS_verified', 1)
                     ->where('edition_id', $this->editionId)->count();

        return !$used;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.phone.phone_not_used');
    }
}
