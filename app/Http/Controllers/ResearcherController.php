<?php

namespace App\Http\Controllers;

use App\Models\Researcher;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ResearcherController extends Controller
{
    public function index()
    {
        return Inertia::render('Researchers/Index', [
            'researchers' => Researcher::with(['user', 'institution'])->get()
        ]);
    }
}