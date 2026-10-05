<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PadreDashboardController extends Controller
{
    public function index()
{
    return view('padre.dashboard');
}
}
