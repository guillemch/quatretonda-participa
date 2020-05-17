<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Voter;
use App\Rules\BallotValidity;
use App\Rules\HasNotVoted;
use App\Rules\OnCensus;
use App\Rules\PhoneFormat;
use App\Rules\PhoneNotUsed;
use App\Rules\SMSVerify;

class VoteRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Modify input data before validation
     *
     * @return array
     */
    public function all($keys = null)
    {
        $attributes = parent::all($keys);

        $countryCode = (isset($attributes['country_code'])) ? $attributes['country_code'] : null;

        if (isset($attributes['SID'])) $attributes['SID'] = $this->hashSID($attributes['SID']);
        if (isset($attributes['phone'])) $attributes['phone'] = $this->cleanPhone($countryCode, $attributes['phone']);

        $this->replace($attributes);

        return $attributes;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $editionId = $this->get('edition_id');
        $SID = $this->input('SID');
        $voter = Voter::findBySID($SID, $editionId);

        $isRequestSMS = $this->is('api/request_sms');
        $isCastBallot = $this->is('api/cast_ballot');

        $smsIsDisabled = config('participa.disable_SMS_verification', false);
        $votingInPerson = ($this->user()) ? true : false;
        $verificationRequired = (!$votingInPerson && !$smsIsDisabled);

        // Rules
        $rules['SID'] = [
            'required',
            new OnCensus($voter),
            new HasNotVoted($voter)
        ];

        $rules['ballot'] = [
            new BallotValidity($editionId)
        ];

        $phoneRules = [
            'required',
            new PhoneFormat(),
            new PhoneNotUsed($editionId, $this->input('phone'))
        ];

        $smsRules = [
            'required',
            new SMSVerify($voter)
        ];

        // SMS verification rules. Only when applicable.
        if ($isRequestSMS || $isCastBallot) {
            $rules['phone'] = $verificationRequired ? $phoneRules : '';
            $rules['country_code'] = $verificationRequired ? 'required|numeric' : '';
        }

        if ($isCastBallot) {
            $rules['SMS_code'] = $verificationRequired ? $smsRules : '';
        }

        return $rules;
    }

    /**
     * Prepend international dial code to phone for further processing
     * and clean the phone input to prevent any silly errors like spaces or dashes
     *
     * @return string
     */
    public function cleanPhone($countryCode, $phone)
    {
        $countryCode = filter_var($countryCode, FILTER_SANITIZE_STRING);
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);

        $countryCode = ($countryCode) ? $countryCode : '34';
        $phone = $countryCode . '.' . $phone;

        // Improve this with regex?
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('-', '', $phone);

        return $phone;
    }

    /**
     * Clean the ID field to prevent silly not found errors
     * when users add unnecessary spaces, dashes or dots
     *
     * @return string
     */
    public static function hashSID($value)
    {
        $value = filter_var($value, FILTER_SANITIZE_STRING);

        // Improve this with regex?
        $value = str_replace(' ', '', $value);
        $value = str_replace('-', '', $value);
        $value = str_replace('.', '', $value);

        $value = strtoupper($value);

        if (config('participa.hashed_SIDs')) $value = hash('sha256', $value);

        return $value;
    }
}
