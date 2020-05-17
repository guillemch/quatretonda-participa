<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifySMS;
use App\Edition;

class Voter extends Model
{
    use Notifiable;

    /**
     * Get the edition that the user belongs to.
     */
    public function edition()
    {
        return $this->belongsTo('App\Edition');
    }

    /**
     * Get the ballot cast by the voter.
     * Only if anonymous_voting is disabled
     */
    public function ballot()
    {
        if (config('participa.anonymous_voting') === false) {
            return $this->hasOne('App\Ballot');
        }

        return $this;
    }

    /**
     * Find a voter by its ID
     */
    public static function findBySID($SID, $editionId)
    {
        if (!$editionId) $editionId = Edition::current()->id;
        return Self::where('SID', $SID)->where('edition_id', $editionId)->first();
    }

    /**
     * Check if an SMS code has already been sent to a particular phone
     */
    public function smsAlreadySent($phone)
    {
        return ($this->SMS_phone === $phone) ? ['time' => $this->SMS_time] : false;
    }

    /**
     * Check if the voter has reached the maximum attempts allowed
     * to request a different number to different phones
     */
    public function smsExceeded()
    {
        if ($this->SMS_attempts >= config('participa.sms_max_attempts')) {
            $last_number = explode('.', $this->SMS_phone);
            return [
                'last_country_code' => $last_number[0],
                'last_number' => $last_number[1],
                'time' => $this->SMS_time
            ];
        }

        return FALSE;
    }

    /**
     * Generate a new SMS token
     */
    public function smsNewToken()
    {
        $code = random_int(100000,999999);
        return $code;
    }

    /**
     * Submit the SMS token to the provided phone number
     */
    public function smsSubmit($phone)
    {
        $token = $this->smsNewToken();

        $this->SMS_token = $token;
        $this->SMS_phone = $phone;
        $this->SMS_attempts++;
        $this->SMS_time = date('Y-m-d H:i:s');
        $this->save();

        return $this->notify(new VerifySMS());
    }

    /**
     * Rollback a voter's SMS status if SMS failed to send.
     */
    public function smsRollback()
    {
        $this->SMS_token = '';
        $this->SMS_attempts--;
        $this->SMS_time = null;
        $this->save();
    }

    /**
     * Generate a SHA-256 hash to prevent tampering with the database
     */
    public function createSignature()
    {
        $signature = $this->SID . $this->ip_address . $this->ballot_time . $this->by_user_id . config('app.key');
        return hash('sha256', $signature);
    }

    /**
     * Mark a voter when they vote to prevent ballot stuffing
     */
    public function mark($request)
    {
        $userId = ($request->user()) ? $request->user()->id : null;

        if (!$userId) $this->SMS_verified = 1;
        $this->ballot_cast = 1;
        $this->ballot_time = date('Y-m-d H:i:s');
        $this->ip_address = $request->ip();
        $this->user_agent = $request->header('User-Agent');
        $this->signature = $this->createSignature();
        $this->by_user_id = $userId;

        return $this->save();
    }

    /**
     * Reset a voter's status if an error occurs
     */
    public function rollback()
    {
        $this->ballot_cast = 0;
        $this->ballot_time = null;
        $this->signature = '';

        return $this->save();
    }


}
