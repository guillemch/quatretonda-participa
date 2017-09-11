<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Requests\VoteRequest;
use App\Voter;
use App\Limit;
use App\Edition;
use App\Ballot;
use App\Question;
use App\Option;

class VoteServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        /**
         * Checks if ID exists on the current edition census
         * If an ID does not exist, it logs the error to prevent
         * brutce force attacks to guess valid IDs
         */
        Validator::extend('on_census', function($attribute, $value, $params) {
            $exists = Voter::findBySID($value, $params[0]);

            if(!$exists) {
                Limit::logAction($this->app->request, 'IDFailedLookUp');
                return false;
            } else {
                return true;
            }
        });

        /**
         * Checks if ID has already cast a ballot on the current edition
         */
        Validator::extend('has_not_voted', function($attribute, $value, $params) {
            $voter = Voter::findBySID($value, $params[0]);

            if(!$voter) return true;
            return ($voter->ballot_cast === 0);
        });

        /**
         * Checks if user has entered their phone correctly
         */
        Validator::extend('phone_format', function($attribute, $value) {
            if(!is_numeric($value)) return false;

            /* Alert a Spanish user if mobile phone is not exactly 9 numbers */
            /* Consider switching to more robust solution for international users */
            $dialCode = explode('.', $value);
            if($dialCode == '34' && strlen($value) != 9) return false;

            return true;
        });

        /**
         * Checks if the phone provided has already been associated
         * with a vote on the current eidition
         */
        Validator::extend('phone_not_used', function($attribute, $value, $params) {
            $used = Voter::where('SMS_phone', $value)
                         ->where('SMS_verified', 1)
                         ->where('edition_id', $params[0])->count();

            return !$used;
        });

        /**
         * Checks that the ballot submitted by the user does not contain
         * any illegal values, such responses to non-existing questions
         * or selecting more options than allowed by the rules
         */
        Validator::extend('ballot_validity', function($attribute, $questions, $params) {
            foreach($questions as $questionKey => $question) {
                $checkQuestion = Question::where('id', $question['id'])->where('edition_id', $params[0])->first();
                if(!$checkQuestion) return false;

                if(count($question['options']) > $checkQuestion->max_options
                || count($question['options']) < $checkQuestion->min_options) return false;

                foreach($question['options'] as $optionKey => $option) {
                    $checkOption = Option::where('id', '=', $option['id'])->where('question_id', '=', $question['id'])->first();
                    if(!$checkOption) return false;
                }
            }

            return true;
        });

        /**
         * Checks if the SMS code is the same as the code sent to the voter
         */
        Validator::extend('sms_code', function($attribute, $value, $params) {
            $voter = Voter::findBySID($params[0], $params[1]);
            $voterToken = ($voter) ? $voter->SMS_token : null;
            $providedToken = $value;
            return ($voterToken == $providedToken);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
