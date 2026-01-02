<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|string|regex:/^[А-ЯЁA-Z][а-яёa-z]+$/',
                'last_name' => 'required|string|regex:/^[А-ЯЁA-Z][а-яёa-z]+$/',
                'patronymic' => 'required|string|regex:/^[А-ЯЁA-Z][а-яёa-z]+$/',
                'email' => 'required|string|unique:user',
                'password' => 'required|string|min:3|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
                'birth_date' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return (response()->json(
                ['error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]],
                422
            ));
        }

        $user = User::create(
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'patronymic' => $request->patronymic,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'birth_date' => $request->birth_date
            ]
        );

        return (response()->json(
            [
                "data" => [
                    "user" => [
                        "name" => $request->first_name . ' ' . $request->last_name . ' ' . $request->patronymic,
                        "email" => $request->email
                    ],
                    "code" => 201,
                    "message" => "Пользователь создан"
                ]
            ],
            201
        ));
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|string',
                'password' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return (response()->json(
                [
                    'error' => [
                        'code' => 422,
                        'message' => 'Validation error',
                        'errors' => $validator->errors()
                    ]
                ],
                422
            ));
        }

        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = Str::random(80);
            $user->api_token = $token;
            $user->save();

            return (response()->json(
                [
                    "data" => [
                        "user" => [
                            "id_user" => $user->id_user,
                            "name" => $user->first_name . ' ' . $user->last_name . ' ' . $user->patronymic,
                            "birth_date" => $user->birth_date,
                            "email" => $user->email
                        ],
                        "token" => $token
                    ]
                ],
                200
            ));
        } else {
            return (response()->json(
                [
                    'message' => "Login failed"
                ],
                403
            ));
        }
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->first();

        $user->api_token = null;
        $user->save();

        return (response()->noContent());
    }
}
