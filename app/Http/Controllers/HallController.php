<?php

namespace App\Http\Controllers;

use App\Models\Hall;

class HallController extends Controller
{
    public function index()
    {
        $halls = Hall::query()->with('studio')->orderBy('name')->get();

        return view('halls.index', compact('halls'));
    }

    public function show(Hall $hall)
    {
        $hall->load('studio');

        return view('halls.show', compact('hall'));
    }
}
