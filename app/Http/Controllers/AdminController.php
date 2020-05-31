<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Edition;
use App\Voter;
use App\Report;
use App\Limit;

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
        $editionIsOpen = Edition::current()->isOpen();
        return view('admin.dashboard', compact('user', 'editionIsOpen'));
    }
}
