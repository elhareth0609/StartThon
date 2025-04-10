<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Factory;
use Laravel\Sanctum\PersonalAccessToken;
use stdClass;
use MongoDB\BSON\Int64;

class AuthController extends Controller {
    public function authenticate(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()->first()
                ], 400);
            }
    
            // Check database connection first
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Database connection error',
                    'error' => $e->getMessage()
                ], 500);
            }
    
            $user = User::where('email', $request->email)->where('password',bcrypt($request->password))->first();
            $email = User::where('email', $request->email)->first();

            if (!$email) {
                $user = new User();
                $user->email = $request->email;
                $user->role_id = new Int64(2);
                $user->password = $request->password; // Or use $request->password
                $user->save();
                
                // Make sure the save operation works
                if (!$user->save()) {
                    return response()->json([
                        'message' => 'Failed to create user'
                    ], 500);
                }
            }
            //  elseif ($email && !$user) {
            //     return response()->json([
            //         'message' => bcrypt($request->password)
            //     ],401);
            // }


    
            // $token = $user->createToken('API Token')->plainTextToken;
    
            return response()->json([
                'user' => $user,
                // 'token' => $token
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
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
