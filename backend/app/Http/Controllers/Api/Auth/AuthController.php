<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role'     => 'customer',
            'status'   => 'active',
        ]);

        // Token name helps debugging (WP-Frontend / Postman / Mobile etc.)
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'ok' => true,
            'message' => 'Registered successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', strtolower($validated['email']))->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if (($user->status ?? 'active') !== 'active') {
            return response()->json([
                'ok' => false,
                'message' => 'Account is not active.',
            ], 403);
        }

        $tokenName = $request->header('X-Client', 'api'); // e.g. wp-frontend, admin-panel
        $token = $user->createToken($tokenName)->plainTextToken;

        return response()->json([
            'ok' => true,
            'message' => 'Logged in successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status ?? 'active',
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'ok' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status ?? 'active',
            ],
        ]);
    }

    public function logout(Request $request)
    {
        // revoke only current token (best practice)
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Logged out successfully.',
        ]);
    }
}
