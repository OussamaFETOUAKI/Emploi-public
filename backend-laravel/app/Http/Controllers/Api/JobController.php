<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;

class JobController extends Controller
{
    // LISTE DES OFFRES (USER)
    public function index()
    {
        return response()->json(
            Job::orderBy('created_at', 'desc')->get()
        );
    }
}
