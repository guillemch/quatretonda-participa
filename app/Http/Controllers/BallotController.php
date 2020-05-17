<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Edition;
use App\Ballot;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BallotController extends Controller
{
    /**
     * Page to look up a ballot on the system
     *
     * @return \Illuminate\View\View
     */
    public function ballot($ballotRef)
    {
        $ballot = Ballot::where('ref', $ballotRef)->with('edition')->first();

        return view('ballot', compact('ballot', 'ballotRef'));
    }

    /**
     * Page to look up a ballot on the system
     *
     * @return \Illuminate\View\View
     */
    public function ballotLookUp(Request $request)
    {
        if ($request->get('ref')) {
            return redirect('ballot/' . $request->get('ref'));
        }

        return redirect('/');
    }
}
