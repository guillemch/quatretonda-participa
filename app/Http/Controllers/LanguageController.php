<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Swtiches the user's language
     *
     * @return Redirect back
     */
    public function switchLanguage(Request $request, $language)
    {
        $cookie = cookie()->forever('language', $language);

        if (!is_null($request->input('home'))) {
            return redirect('/')->withCookie($cookie);
        } else {
            return back()->withCookie($cookie);
        }
    }
}
