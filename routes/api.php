<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Booth funcitonality */
Route::post('/precheck', 'API\BoothController@precheck');
Route::post('/request_sms', 'API\BoothController@requestSms');
Route::post('/cast_ballot', 'API\BoothController@castBallot');

/* Front page blocks */
Route::get('/sidebar', 'HomeController@sidebar');
Route::get('/option/{option}', 'HomeController@option');

/* Ballot helpers */
Route::get('/ballot', 'API\BallotController@ballotJSON');
Route::get('/ballot/qr/{ref}', 'API\BallotController@ballotQR');

/* Admin area */
Route::post('/annul_ballot', 'API\AdminController@annulBallot')->middleware('auth.api');
Route::post('/id_lookup', 'API\AdminController@lookUp')->middleware('auth.api');
Route::post('/unblock', 'API\AdminController@unblock')->middleware('auth.api');
Route::get('/results', 'API\AdminController@results')->middleware('auth.api');
Route::get('/reports', 'API\AdminController@reports')->middleware('auth.api');
