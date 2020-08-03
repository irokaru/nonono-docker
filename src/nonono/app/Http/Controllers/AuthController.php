<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    /**
     * Login user and return a token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json(['status' => 'success',], 200)->header('Authorization', $token);
        }
        return response()->json(['error' => 'Unauthorized',], 401);
    }

    /**
     * Logout User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['status' => 'success',], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = null;
        try {
            $token = $this->guard()->refresh();
        } catch (JWTException $e) {
            return response()->json(['error' => 'refresh failed',], 401);
        }

        return response()->json(['status' => 'successs',], 200)->header('Authorization', $token);
    }

    /**
     * check have a token.
     *
     * @return boolean
     */
    public static function check()
    {
        try {
            $token = JWTAuth::parseToken()->authenticate();
            return true;
        } catch (JWTException $e) {
            return false;
        }
    }

    /**
     * Return auth guard
     */
    private function guard()
    {
        return Auth::guard();
    }
}
