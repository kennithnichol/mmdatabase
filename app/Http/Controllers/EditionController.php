<?php

namespace App\Http\Controllers;

use App\Models\Edition;

class EditionController extends Controller
{
    public function create()
    {
        return view('edition.create');
    }

    public function edit(Edition $edition)
    {
        return view( 'edition.edit', compact('edition'));
    }

    public function index()
    {

    }

    public function show(Edition $edition)
    {

    }
}
