<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    
    public function switch($locale)
    {
        // Validate the locale
        if (in_array($locale, ['en', 'lv'])) {
            // Set the locale in the session
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }

        // Redirect back with locale changed
        return redirect()->back();
    }

}
