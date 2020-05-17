<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NotificationChannels\Messagebird\Exceptions\CouldNotSendNotification;

use App\Http\Requests;
use App\Http\Requests\VoteRequest;
use App\Voter;
use App\Ballot;
use App\Limit;

class BoothController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('booth');
    }

    /**
     * Validate that a user can proceed to Step 2 (SMS Validation or Review)
     *
     * @return \Illuminate\Http\Response
     */
    public function precheck(VoteRequest $request)
    {
        return response()->json(['success' => true]);
    }

    /**
     * Request SMS
     *
     * @return \Illuminate\Http\Response
     */
    public function requestSms(VoteRequest $request)
    {
        $editionId  = $request->get('edition_id');
        $inPerson   = $request->user();
        $flag       = null;
        $SID        = $request->input('SID');
        $phone      = $request->input('phone');
        $voter      = Voter::findBySID($SID, $editionId);

        if (!$inPerson) {
            // Check if SMS code has already been sent for phone number
            if ($smsAlreadySent = $voter->smsAlreadySent($phone)) {
                // if sent: redirect & attach warning
                $flag = ['name' => 'SMS_already_sent', 'info' => $smsAlreadySent];
            } elseif ($smsExceeded = $voter->smsExceeded()) {
                // if exceeded: redirect & attach warning
                $flag = ['name' => 'SMS_exceeded', 'info' => $smsExceeded];
            } else {

                try {
                    $voter->smsSubmit($phone);
                } catch(CouldNotSendNotification $e) {
                    $voter->smsRollback();

                    return response()->json([
                        'SMS' => [__('participa.error_SMS')]
                    ], 422);
                }
            }
        }

        return response()->json([
            'success' => true,
            'flag' => $flag
        ]);
    }

    /**
     * Cast Ballot
     *
     * @return \Illuminate\Http\Response
     */
    public function castBallot(VoteRequest $request)
    {
        $editionId  = $request->get('edition_id');
        $SID        = $request->input('SID');
        $voter      = Voter::findBySID($SID, $editionId);

        // Mark voter
        $marked = $voter->mark($request);

        // Submit ballot
        if ($marked) {

            $ballot = new Ballot;
            $cast = $ballot->cast($request, $voter);

            if (!$cast) {
                // If an error occurred during the casting process,
                // Unmark voter and display error
                $voter->rollback();
                return response()->json(['success' => false, 'error' => 'Error sistema']);
            } else {
                if (!$request->user()) Limit::logAction('vote', $editionId);
            }
        } else {
            return response()->json(['success' => false, 'error' => 'Error sistema']);
        }

        return response()->json(['success' => true, 'ballot' => $ballot]);
    }

}
