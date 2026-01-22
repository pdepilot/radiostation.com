<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            $intendedUrl = $request->session()->pull('url.intended', route('home', absolute: false));
            return redirect($intendedUrl, navigate: true);
        }
        
        return view('auth.verify-email');
    }
}
