<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->json()->all();
        $validator = Validator::make($credentials, ['login' => 'required', 'password' => 'required']);
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        if (auth()->attempt($credentials)) {
            /** @var User $user */
            $user = auth()->user();
            $user->regenerateToken();
            $user->save();
            return response()->json([
                'token' => $user->token,
                'expiration_time' => $user->token_expired_at->timestamp,
            ]);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }
}
