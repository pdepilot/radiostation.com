<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function privacy()
    {
        return view('frontend.policies.privacy');
    }

    public function terms()
    {
        return view('frontend.policies.terms');
    }

    public function faq()
    {
        return view('frontend.policies.faq');
    }

    public function feedback()
    {
        return view('frontend.policies.feedback');
    }
}
