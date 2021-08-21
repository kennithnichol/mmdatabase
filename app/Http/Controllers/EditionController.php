<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditionController extends Controller
{
    public function create()
    {
        return view('edition.create');
    }
}
