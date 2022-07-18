<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class UserController extends Controller
{
    function login(Request $request)
    {
        $validator = Validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        if (Auth::attempt($validated)) {
            $payload = [
                'sub' => '1234567890',
                'name' => 'Administrator',
                'iat' => Carbon::now()->timestamp,
            ];

            $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

            return response()->json([
                'msg' => 'Token Berhasil Dibuat',
                'data' => 'Bearer' . $token
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Email atau Password Salah'
            ], 422);
        }
    }
}
