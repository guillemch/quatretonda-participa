<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ballot extends Model
{
    /**
     * Get the edition that the ballot belongs to.
     */
    public function edition()
    {
        return $this->belongsTo('App\Edition');
    }

    /**
     * Get the voter that the ballot belongs to.
     * Provided anonymous voting is not turned off.
     */
    public function voter()
    {
        if(config('participa.anonymous_voting') === false) {
            return $this->belongsTo('App\Voter');
        }

        return $this;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'ref';
    }

    /**
     * Compose a valid ballot from the input provided by the front-end
     * Input should have been previously validated.
     */
    public function createBallot($ballot)
    {
        $ballotToEncrypt = [];

        foreach($ballot as $question) {
            $options = [];
            foreach($question['options'] as $option) {
                $ballotToEncrypt[$question['id']][$option['id']] = 1.000;
            }
        }

        return encrypt($ballotToEncrypt);
    }

    /**
     * Decode an encrypted ballot
     */
    public function decrypt()
    {
        return decrypt($this->ballot);
    }

    /**
     * Decode an encrypted ballot and attach option values
     */
    public function decryptWithOptions()
    {
        $ballot = $this->decrypt();
        $withOptions = [];

        foreach($ballot as $questionId => $options) {
            $option_keys = array_keys($options);
            $withOptions[$questionId] = [
                    'question' => \App\Question::where('id', $questionId)->first(),
                    'options' => \App\Option::whereIn('id', $option_keys)->get(),
                    'points' => $options
                ];
        }

        return $withOptions;
    }

    /**
     * Generate a random ref for a new ballot
     */
    public function createRef()
    {
        $newRef = str_random(10);
        $exists = Self::where('ref', $newRef)->count();
        if($exists) return $this->createRef();
        return $newRef;
    }

    /**
     * Sign a cast ballot using a SHA-256 hash
     */
    public function createSignature()
    {
        $signature = $this->ref . $this->ballot . config('app.key');
        return hash('sha256', $signature);
    }

    /**
     * Check ballot integrity
     */
    public function check()
    {
        return $this->signature === $this->createSignature();
    }

    /**
     * Cast a ballot
     */
    public function cast($request, $voter)
    {
        $userId = ($request->user()) ? $request->user()->id : null;

        $this->edition_id = $request->get('edition_id');
        $this->ref = $this->createRef();
        $this->ballot = $this->createBallot($request->input('ballot'));
        $this->signature = $this->createSignature();
        $this->by_user_id = $userId;

        /* Prevent identifiable information about voter from being saved */
        if(config('participa.anonymous_voting') === false) {
            $this->cast_at = date("Y-m-d H:i:s");
            $this->voter_id = $voter->id;
            $this->ip_address = $request->ip();
            $this->user_agent = $request->header('User-Agent');
        }

        return $this->save();
    }
}
