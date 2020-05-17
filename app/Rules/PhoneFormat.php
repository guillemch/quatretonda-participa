<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneFormat implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Checks if user has entered their phone correctly
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_numeric($value)) return false;

        /* Alert a Spanish user if mobile phone is not exactly 9 numbers */
        /* Consider switching to more robust solution for international users */
        $dialCode = explode('.', $value);
        if ($dialCode == '34' && strlen($value) != 9) return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.phone.phone_format');
    }
}
