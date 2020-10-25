<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;


class AuthController extends Controller
{
    /**
     * Регистрация пользователя с Api Sanctum
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse вида "token": "0|********"
     */
    public function register(Request $request):\Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:users', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $u = new User();
        $u->name = $request['name'];
        $u->email = $request['email'];
        $u->password = bcrypt($request['password']);
        $u->save();
        $resp = [];
        $resp['token'] = $u->createToken('myapp')->plainTextToken;
        return response()->json($resp, 200);
    }

    /**
     * Вход пользователя с Api Sanctum
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse вида {
     *  "user": {"id": 6, "name": "u5", "email": "u5@kotofei.ml", "email_verified_at": null, "created_at": "2020-10-11T10:00:57.000000Z", "updated_at": "2020-10-11T10:00:57.000000Z"},
     *  "token": "0|********"
     * }
     */
    public function login(Request $request):\Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $u = User::where('email', $request->email)->first();
        if(!$u || !Hash::check($request->password, $u->password)){
            return response()->json(['message'=>'bad login or password'], 401);
        }
        $token = $u->createToken('myapp')->plainTextToken;
        return response()->json(['user'=>$u, 'token'=>$token], 200);
    }

    /**
     * Выход пользователя с Api Sanctum
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse пустой
     */
    public function logout(Request $request):\Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([], 200);
    }
}
