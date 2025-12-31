<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\User; // ✅ IMPORT AJOUTÉ
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // USER → envoyer CV
    public function store(Request $request, $jobId)
    {
        if (!$request->hasFile('cv')) {
            return response()->json([
                'message' => 'CV file is required'
            ], 400);
        }

        // ⚠️ user_id obligatoire
        if (!$request->user_id) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        // 🔒 Bloquer ADMIN
        $user = User::find($request->user_id);
        if ($user && $user->role === 'admin') {
            return response()->json([
                'message' => 'Un administrateur ne peut pas postuler'
            ], 403);
        }

        // 🔒 Vérifier double candidature
        $alreadyApplied = Application::where('job_id', $jobId)
            ->where('user_id', $request->user_id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json([
                'message' => 'Vous avez déjà postulé à cette offre'
            ], 409);
        }

        $path = $request->file('cv')->store('cvs', 'public');

        $application = Application::create([
            'job_id'  => $jobId,
            'user_id' => $request->user_id,
            'cv_path' => $path,
        ]);

        return response()->json($application, 201);
    }

    // ADMIN → voir candidatures
    public function index()
    {
        return Application::with(['user', 'job'])->get();
    }
}
