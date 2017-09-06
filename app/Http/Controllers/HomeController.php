<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Edition;
use App\Option;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class HomeController extends Controller
{

    /**
     * The active edition.
     *
     * @var object
     */
    protected $edition;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->edition = Edition::current('ballot');
    }

    /**
     * Determine what page to show on the frontpage.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $now = time();
        $edition = $this->edition;
        $pastEditions = Edition::pastEditions();
        $forceOpen = $request->get('force_open');

        // If within voting window dates, show voting booth
        if($edition->isOpen() || $forceOpen){
            $user = $request->user();
            $inPerson = ($user) ? true : false;
            $token = ($inPerson) ? JWTAuth::fromUser($user) : null;
            return view('booth', compact('edition', 'token', 'inPerson', 'pastEditions'));
        }

        // If in limbo (after end_date and before publish_results), show placeholder
        if($edition->isAwaitingResults()){
            return view('placeholder', compact('edition', 'pastEditions'));
        }

        // If after end_date AND publish_results, show results
        if($edition->resultsPublished()){
            $results = $edition->fullResults();
            $turnout = $edition->turnout()->count();
            $census = $edition->voters()->count();

            return view('results', compact('edition', 'results', 'turnout', 'census', 'pastEditions'));
        }

        // If none of the previous conditions are met
        // display the About page as a placeholder before the vote.
        $options = view('components.options', compact('edition'));

        return view('about', compact('edition', 'pastEditions', 'options'));

    }

    /**
     * Placeholder page with instructions.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        $edition = $this->edition;
        $pastEditions = Edition::pastEditions();
        $options = view('components.options', compact('edition'));

        return view('about', compact('edition', 'pastEditions', 'options'));
    }

    /**
     * Placeholder page with instructions.
     *
     * @return \Illuminate\View\View
     */
    public function propose()
    {
        $edition = $this->edition;
        $pastEditions = Edition::pastEditions();

        return view('propose', compact('edition', 'pastEditions'));
    }

    /**
     * Returns the stand-alone sidebar to inject via AJAX
     *
     * @return \Illuminate\View\View
     */
    public function sidebar()
    {
        $edition = $this->edition;
        $pastEditions = Edition::pastEditions();

        return view('components.sidebar', compact('edition', 'pastEditions'));
    }

    /**
     * Display option information
     *
     * @return \Illuminate\View\View
     */
    public function option(Option $option)
    {
        if($option->attachments) $option->attachments = explode("\n", $option->attachments);
        if($option->pictures) $option->pictures = explode("\n", $option->pictures);

        return view('components.option', compact('option'));
    }

    /**
     * Show a user's IP address to assist Support
     * in troubleshooting problems with IP limits.
     *
     * @return \Illuminate\View\View
     */
    public function myIpAddress(Request $request)
    {
        $ip = $request->ip();

        return view('ip_address')->withIp($ip);
    }
}
