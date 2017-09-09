<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Edition;
use App\Voter;
use App\Report;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        $editionIsOpen = Edition::current()->isOpen();
        return view('admin.dashboard', compact('user', 'token', 'editionIsOpen'));
    }

    /**
     * Anull a ballot
     *
     * @return \Illuminate\Http\Response
     */
    public function anullBallot(Request $request)
    {

        if(config('participa.anonymous_voting') === true) abort(422, 'Anonymous voting is not disabed');

        $user = Auth::user();
        $edition = Edition::current();
        $confirm = $request->get('confirm');

        $rules['SID'] = 'required|min:5';

        if($confirm) {
            $rules['reason'] = 'required';
        }

        $this->validate($request, $rules);

        /* Find the voter */
        $voter = Voter::findBySID($request->input('SID'), $edition->id);

        if(!$voter) {
            return response()->json(['SID' => ['L\'identificador no s\'ha trobat al cens']], 422);
        }

        /* Retreive ballot submitted by the voter */
        $ballot = $voter->ballot()->first();

        if(!$ballot) {
            return response()->json(['SID' => ['L\'identificador no ha emés cap vot.']], 422);
        }

        if($ballot->by_user_id) {
            return response()->json(['SID' => ['Aquesta papereta no es pot anul·lar perquè s\'ha emés de manera presencial.']], 422);
        }

        /* Do not submit report and delete ballot if not double confirmed */
        if(!$confirm) {
            return response()->json(['success' => true]);
        }

        /* Create a report */
        $report = new Report();
        $report->edition_id = $edition->id;
        $report->user_id = $user->id;
        $report->report = 'Papereta anul·lada';
        $report->reason = $request->input('reason');
        $report->attachment = json_encode(['ballot' => $ballot, 'voter' => $voter]);
        $report->ip_address = $request->ip();
        $report->user_agent = $request->header('User-Agent');
        $report->save();

        /* Unmark voter */
        $voter->rollback();

        /* Delete the ballot */
        $ballot->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Look up an SID
     *
     * @return \Illuminate\Http\Response
     */
    public function lookUp(Request $request)
    {
        $edition = Edition::current();

        $this->validate($request, [
            'SID' => 'required|min:2'
        ]);

        $foundSIDs = Voter::select('SID')
                    ->where('SID', 'like', '%' . $request->input('SID') . '%')
                    ->where('edition_id', $edition->id)
                    ->orderBy('SID', 'ASC')
                    ->take(10)
                    ->get();

        return response()->json($foundSIDs);
    }

    /**
     * Display results
     *
     * @return \Illuminate\Http\Response
     */
    public function results(Request $request)
    {
        $edition = Edition::current();

        /* Update and cache the results */
        $lastTally = cache('last_tally_finished_' . $edition->id);
        $nextTally = $lastTally + (60 * 30); // 30 minutes

        if(time() > $nextTally || $request->get('force')) {
            Artisan::call('results:cache');
            $output = Artisan::output();
            $integrity = stripos($output, 'Result integrity check failed');
            $integrity = ($integrity !== false) ? false : true;
            $time = time();
        } else {
            $integrity = cache('last_tally_integrity_' . $edition->id);
            $time = cache('last_tally_finished_' . $edition->id);
        }

        /* Retreive the results */
        $results = $edition->fullResults();

        /* Retreive the turnout */
        $turnout = $edition->turnout()->count();
        $census = $edition->voters()->count();

        $response = [
            'census' => $census,
            'turnout' => $turnout,
            'results' => $results,
            'integrity' => $integrity,
            'time' => date('d-m-Y H:i', $time)
        ];

        return response()->json($response);
    }

}
