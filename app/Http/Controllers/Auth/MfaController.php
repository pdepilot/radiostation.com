<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class MfaController extends Controller
{
    /**
     * Show the MFA verification form.
     */
    public function show(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login', navigate: true);
        }

        $user = Auth::user();
        
        if (!$user->hasMfaEnabled()) {
            // Redirect to intended URL or home page using SPA navigation
            $intendedUrl = $request->session()->pull('url.intended', route('home', absolute: false));
            return redirect($intendedUrl, navigate: true);
        }

        // Store intended destination if not already set
        if (!$request->session()->has('url.intended')) {
            $request->session()->put('url.intended', $request->get('intended', route('home', absolute: false)));
        }

        return view('auth.verify-mfa');
    }

    /**
     * Verify the MFA code.
     */
    public function verify(Request $request)
    {
        // Handle both array input (from OTP inputs) and string input
        $code = $request->input('code');
        if (is_array($code)) {
            $code = implode('', $code);
        }

        // Also check for code[] array input from the form
        if (empty($code) && $request->has('code')) {
            $codeArray = $request->input('code');
            if (is_array($codeArray)) {
                $code = implode('', array_filter($codeArray));
            }
        }

        $request->merge(['code' => $code]);

        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ]);

        $user = Auth::user();

        if (!$user->hasMfaEnabled()) {
            // Redirect to intended URL or home page using SPA navigation
            $intendedUrl = $request->session()->pull('url.intended', route('home', absolute: false));
            return redirect($intendedUrl, navigate: true);
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->mfa_secret, $code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => ['The provided code is invalid.'],
            ]);
        }

        // Store MFA verification in session
        $request->session()->put('mfa_verified', true);

        // Redirect to intended destination or home page using SPA navigation
        $intendedUrl = $request->session()->pull('url.intended', route('home', absolute: false));
        return redirect($intendedUrl, navigate: true);
    }
}
