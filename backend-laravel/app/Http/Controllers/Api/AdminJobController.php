<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class AdminJobController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'city' => 'required',
            'type' => 'required',
            'description' => 'required',
            'deadline' => 'required|date',
        ]);

        return response()->json(
            Job::create($validated),
            201
        );
    }

    public function destroy(Request $request, $id)
{
    if ($request->role !== 'admin') {
        return response()->json([
            'message' => 'Accès refusé'
        ], 403);
    }

    $job = Job::findOrFail($id);
    $job->delete();

    return response()->json([
        'message' => 'Offre supprimée'
    ]);
}

}
