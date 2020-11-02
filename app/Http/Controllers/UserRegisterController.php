<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function registerOTA(Request $request) {
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $result = User::create([
            'email' => $email,
            'password' => $password
        ]);

        if ($result) {
            return response()->json([
                'success' => true,
                'data' => $result
            ],200);
        } else {
            return response()->json([
                'success' => false,
                'data' => ''
            ],400);
        }
    }
}
