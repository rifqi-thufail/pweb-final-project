<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the inventory management tips page.
     */
    public function index()
    {
        return view('tips');
    }
}
