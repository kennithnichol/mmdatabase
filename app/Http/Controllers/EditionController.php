<?php

namespace App\Http\Controllers;

use App\Models\Edition;

class EditionController extends Controller
{
    public function create()
    {
        return view('edition.create');
    }

    public function index()
    {
        
    }

    public function show(Edition $edition)
    {
        
    }
}
