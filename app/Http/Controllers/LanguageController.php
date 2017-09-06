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
    public function switchLanguage($language)
    {
        $cookie = cookie()->forever('language', $language);
        return back()->withCookie($cookie);
    }
}
