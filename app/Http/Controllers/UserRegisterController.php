<?php

namespace App\Http\Controllers;

use App\Models\OrangTuaAsuh;
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

    private function insertUser($email, $password) {
        return User::create([
            'email' => $email,
            'password' => $password
        ]);
    }

    public function registerOTA(Request $request) {
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $nama = $request->input('nama');
        $telepon = $request->input('telepon');

        $resultUser = $this->insertUser($email, $password);

        $resultOTA = OrangTuaAsuh::create([
            'user_id' => $resultUser->id,
            'nama' => $nama,
            'telepon' => $telepon
        ]);

        if ($resultUser && $resultOTA) {
            return response()->json([
                'success' => true,
                'message' => 'Register success!',
                'data' => [
                    'user' => $resultUser,
                    'ota' => $resultOTA
                ]
            ],200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register fail!',
                'data' => ''
            ],400);
        }
    }
}
