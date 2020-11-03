<?php

namespace App\Http\Controllers;

use App\Models\OrangTuaAsuh;
use App\Models\Sekolah;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        DB::beginTransaction();
        $resultUser = $this->insertUser($email, $password);

        if ($resultUser) {
            $resultOTA = OrangTuaAsuh::create([
                'user_id' => $resultUser->id,
                'nama' => $nama,
                'telepon' => $telepon
            ]);

            if ($resultOTA) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Register success!',
                    'data' => [
                        'user' => $resultUser,
                        'ota' => $resultOTA
                    ]
                ],200);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Register fail!',
                    'data' => ''
                ],400);
            }
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Register fail!',
                'data' => ''
            ],400);
        }
    }

    public function registerSekolah(Request $request) {
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $NPSN = $request->input('NPSN');
        $jenjang_pendidikan = $request->input('jenjang_pendidikan');
        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $kelurahan = $request->input('kelurahan');
        $kecamatan = $request->input('kecamatan');
        $kabupaten_kota = $request->input('kabupaten_kota');
        $provinsi = $request->input('provinsi');
        $kode_pos = $request->input('kode_pos');
        $telepon = $request->input('telepon');
        $status = $request->input('status');
        $nama_kepala_sekolah = $request->input('nama_kepala_sekolah');
        $NRKS = $request->input('NRKS');
        $KTP_kepala_sekolah_doc_path = $request->input('KTP_kepala_sekolah_doc_path');

        DB::beginTransaction();
        $resultUser = $this->insertUser($email, $password);

        if ($resultUser) {
            $resultSekolah = Sekolah::create([
                'user_id' => $resultUser->id,
                'NPSN' => $NPSN,
                'jenjang_pendidikan' => $jenjang_pendidikan,
                'nama' => $nama,
                'alamat' => $alamat,
                'kelurahan' => $kelurahan,
                'kecamatan' => $kecamatan,
                'kabupaten_kota' => $kabupaten_kota,
                'provinsi' => $provinsi,
                'kode_pos' => $kode_pos,
                'telepon' => $telepon,
                'status' => $status,
                'nama_kepala_sekolah' => $nama_kepala_sekolah,
                'NRKS' => $NRKS,
                'KTP_kepala_sekolah_doc_path' => $KTP_kepala_sekolah_doc_path
            ]);

            if ($resultSekolah) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Register success!',
                    'data' => [
                        'user' => $resultUser,
                        'ota' => $resultSekolah
                    ]
                ],200);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Register fail!',
                    'data' => ''
                ],400);
            }
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Register fail!',
                'data' => ''
            ],400);
        }
    }
}
