<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Kreait\Firebase\Factory;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\Controller;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use stdClass;

class AuthController extends Controller {
    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(), [
            // 'uid' => 'required|string',
            'email' => 'required|email',
            // 'fcm_token' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()->first()
            ], 400);
        }

        $factory = (new Factory)
            ->withServiceAccount(env('FIREBASE_CREDENTIALS_PATH'))
            ->create();

        $auth = $factory->getAuth();

        try {
            $firebase = $auth->getUser($request->uid);
        } catch (UserNotFound $e) {
            return response()->json([
                'message' => 'User not found in Firebase'
            ], 404);
        }

        if ($firebase->email !== $request->email) {
            return response()->json([
                'message' => 'Email mismatch'
            ], 400);
        }

        $user = User::where('email', $firebase->email)->first();

        if (!$user) {
            $user = new User;
            $user->email = $firebase->email;
            $user->fullname = $firebase->displayName;
            $user->photo = $firebase->photoURL;
            $user->save();
        }

        $token = PersonalAccessToken::updateOrCreateForUser($user, ['name' => 'API Token'])->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'uid' => 'required|string',
            'email' => 'required|email',
            'fcm_token' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()->first()
            ], 400);
        }

        $user = $request->user();

        $data = new stdClass();
        $data->user = $user;

        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'data' => $data
        ],200);
    }

    public function info(Request $request) {
        $user = $request->user();

        $data = new stdClass();
        $data->user = $user;

        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'data' => $data
        ],200);
    }

    public function logout(Request $request) {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function destroy(Request $request) {
        $user = $request->user();

        $user->delete();
        // $user->tokens()->delete();

        return response()->json([
            'message' => 'Destroyed successfully'
        ]);
    }
}
