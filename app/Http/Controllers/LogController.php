<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\User;
use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Resources\UmkmResource;
use App\Http\Resources\UserResource;
use Laravel\Socialite\Facades\Socialite;

class LogController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        $data = Socialite::driver('google')->stateless()->user();
        $user = User::where('google_id', $data->id)->first();
        if (!$user) {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'google_id' => $data->id,
            ]);
            Photo::create([
                'user_id' => $user->id,
                'url' => $data->avatar,
                'is_main' => true,
            ]);
            return redirect(env('CLIENT_URL') . "/auth/redirect/$user->google_id/$user->email");
        } else {
            return redirect(env('CLIENT_URL') . "/auth/redirect/$user->google_id/$user->email");
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'google_id' => 'required',
        ]);
        $user = User::where('email', $request->email)
                    ->where('google_id', $request->google_id)
                    ->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
        return response()->json([
            'message' => 'User found',
            'user' => $user,
            'token' => $user->createToken('Megadigi')->plainTextToken,
        ], 201);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out',
        ], 401);
    }
    public function info(Request $request)
    {
        // return (new UserResource(User::find($request->user()->id)))->response()->setStatusCode(200);
        return response()->json([
            'user' => new UserResource(User::find($request->user()->id)),
            'umkm' => $request->user()->umkm? new UmkmResource(Umkm::where('user_id', $request->user()->id)->first()) : null,
        ], 200);
    }
}
