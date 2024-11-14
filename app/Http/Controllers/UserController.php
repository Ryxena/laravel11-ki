<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\user;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'profile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation Error', $validator->errors());
        }

        $profileImage = null;
        if ($request->hasFile('profile')) {
            $image = $request->file('profile');
            $fileName = time().'_'.$image->getClientOriginalName();
            $profileImage = $image->storeAs('public/profile', $fileName);
        }
        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'profile' => $profileImage,
            'password' => bcrypt($request->get('password')),
        ]);
        if ($user->save()) {
            return ApiResponse::success($user, 'User registered successfully');
        } else {
            return ApiResponse::error('Error When Register A user');
        }
    }
    public function logout(Request $req): \Illuminate\Http\JsonResponse
    {
        $req->user()->currentAccessToken()->delete();

        return ApiResponse::success(null, "Logged out successfully");
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return ApiResponse::error('Validation Error', $validator->errors(), 422);
        }
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = User::where('id', Auth::user()->id)->first();
            $token = $user->createToken('user-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'login successfully',
                'data' => $user,
                'token' => $token,
            ]);
        } else {
            return ApiResponse::error('Unauthorized email or password wrong', [], 401);
        }
    }

    public function edit(Request $request): JsonResponse
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|min:3',
            'password' => 'nullable|string|min:8',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Validation error', $validator->errors());
        }

        try {
            if ($request->hasFile('profile')) {
                if ($user->profile != null) {
                    Storage::delete($user->profile);
                }

                $image = $request->file('profile');
                $photoName = time().'.'.$image->getClientOriginalExtension();
                $profileImage = $image->storeAs('public/profile', $photoName);
                $user->profile = $profileImage;
            }

            if ($request->filled('name')) {
                $user->name = $request->name;
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return ApiResponse::success($user, 'Profile berhasil diperbarui');
        } catch (Exception $e) {
            return ApiResponse::error('Terjadi kesalahan saat memperbarui profile', null);
        }
    }
}
