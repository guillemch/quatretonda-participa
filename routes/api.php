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
Route::post('/precheck', 'BoothController@precheck');
Route::post('/request_sms', 'BoothController@requestSms');
Route::post('/cast_ballot', 'BoothController@castBallot');

/* Front page blocks */
Route::get('/sidebar', 'HomeController@sidebar');
Route::get('/option/{option}', 'HomeController@option');

/* Ballot helpers */
Route::get('/ballot', 'BallotController@ballotJSON');
Route::get('/ballot/qr/{ref}', 'BallotController@ballotQR');

/* Admin area */
Route::post('/anull_ballot', 'AdminController@anullBallot')->middleware('auth.api');
Route::post('/id_lookup', 'AdminController@lookUp')->middleware('auth.api');
Route::get('/results', 'AdminController@results')->middleware('auth.api');
