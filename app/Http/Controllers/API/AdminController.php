<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Edition;
use App\Voter;
use App\Report;
use App\Limit;

class AdminController extends Controller
{
    /**
     * Annul a ballot
     *
     * @return \Illuminate\Http\Response
     */
    public function annulBallot(Request $request)
    {

        if (config('participa.anonymous_voting') === true)
            abort(422, 'Anonymous voting is not disabed');

        $user = Auth::user();
        $edition = Edition::current();
        $confirm = $request->get('confirm');

        $rules['SID'] = 'required|min:5';

        if ($confirm) {
            $rules['reason'] = 'required';
        }

        $this->validate($request, $rules);

        /* Find the voter */
        $SID = (config('participa.hashed_SIDs'))
            ? hash('sha256', $request->input('SID'))
            : $request->input('SID');
        $voter = Voter::findBySID($SID, $edition->id);

        if (!$voter) {
            return response()->json([
                'SID' => ['L\'identificador no s\'ha trobat al cens']
            ], 422);
        }

        /* Retreive ballot submitted by the voter */
        $ballot = $voter->ballot()->first();

        if (!$ballot) {
            return response()->json([
                'SID' => ['L\'identificador no ha emés cap vot.']
            ], 422);
        }

        if ($ballot->by_user_id) {
            return response()->json([
                'SID' => ['Aquesta papereta no es pot anul·lar perquè s\'ha emés de manera presencial.']
            ], 422);
        }

        /* Do not submit report and delete ballot if not double confirmed */
        if (!$confirm) {
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

        if (time() > $nextTally || $request->get('force')) {
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

    /**
     * Display results
     *
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request)
    {
        $full = $request->input('full', false);

        $edition = Edition::current();

        // Get annuled ballots and error reports
        $reports = $edition->reports()
                        ->with('user')
                        ->get()
                        ->map(function ($item) {
                            $item['type'] = 'report';
                            $item['data'] = json_decode($item['attachment']);
                            unset($item['attachment']);
                            return $item;
                        })
                        ->toArray();

        // Get IPs over limit
        $voteLimit = Limit::getReports($edition->id, 'vote');
        $lookupLimit = Limit::getReports($edition->id, 'IDFailedLookUp');

        // Combine all the info into one array and sort it by date
        $combined = collect([$reports, $voteLimit, $lookupLimit])
                        ->collapse()
                        ->sortByDesc(function ($item) {
                            return strtotime($item['created_at']);
                        })->values()->all();

        return response()->json(['reports' => $combined]);
    }

    /**
     * Unblock an IP
     *
     * @return \Illuminate\Http\Response
     */
    public function unblock(Request $request)
    {
        $user = Auth::user();
        $edition = Edition::current();
        $ip = $request->input('ip');

        /* Unblock the IP */
        $unblock = Limit::unblock($ip, $edition->id);

        /* Create an automatic report */
        $report = new Report();
        $report->edition_id = $edition->id;
        $report->user_id = $user->id;
        $report->report = 'IP desbloquejada';
        $report->reason = '';
        $report->attachment = json_encode(['ip' => $ip]);
        $report->ip_address = $request->ip();
        $report->user_agent = $request->header('User-Agent');
        $report->save();

        return response()->json(['ip' => $ip, 'deleted' => $unblock]);
    }
}
