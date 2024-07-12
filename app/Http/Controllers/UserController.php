<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\user;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user $user)
    {
        //
    }
    public function logout(Request $req): \Illuminate\Http\JsonResponse
    {
        $req->user()->currentAccessToken()->delete();

        return ApiResponse::success(null, "Logged out successfully");
    }

    public function login(Request $req): \Illuminate\Http\JsonResponse
    {
        return $token = $user->createToken('user-token')->plainTextToken;
    }
}
