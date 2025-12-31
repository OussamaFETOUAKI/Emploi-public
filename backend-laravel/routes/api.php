<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AdminJobController;
use App\Http\Controllers\Api\ApplicationController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/register', function (Request $request) {

    if (!$request->name || !$request->email || !$request->password) {
        return response()->json([
            'message' => 'Name, email and password required'
        ], 400);
    }

    if (User::where('email', $request->email)->exists()) {
        return response()->json([
            'message' => 'Email already exists'
        ], 409);
    }

    $role = $request->role === 'admin' ? 'admin' : 'user';

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => $role,
    ]);

    return response()->json($user, 201);
});


Route::post('/login', function (Request $request) {

    if (!$request->email || !$request->password) {
        return response()->json([
            'message' => 'Email and password required'
        ], 400);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    return response()->json($user);
});

/*
|--------------------------------------------------------------------------
| JOBS (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show']);

/*
|--------------------------------------------------------------------------
| JOBS (ADMIN)
|--------------------------------------------------------------------------
*/

Route::post('/admin/jobs', [AdminJobController::class, 'store']);
Route::delete('/admin/jobs/{id}', [AdminJobController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| APPLICATIONS (CV)
|--------------------------------------------------------------------------
*/

Route::post('/jobs/{id}/apply', [ApplicationController::class, 'store']);
Route::get('/admin/applications', [ApplicationController::class, 'index']);
