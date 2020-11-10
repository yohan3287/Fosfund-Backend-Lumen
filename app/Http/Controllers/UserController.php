<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\OrangTuaAsuh;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

    public function getProfile() {
        $userID = Auth::id();

        $resultOTA = DB::select('
            SELECT *
            FROM user
            JOIN orang_tua_asuh ON orang_tua_asuh.user_id = user.id
            WHERE user.id = ?;
        ', [$userID]);

        if ($resultOTA) {
            Return response()->json([
                "success!" => true,
                "data" => $resultOTA
            ], 200);
        } else {
            $resultSekolah = DB::select('
                SELECT *
                FROM user
                JOIN sekolah ON sekolah.user_id = user.id
                WHERE user.id = ?;
            ', [$userID]);

            if ($resultSekolah) {
                Return response()->json([
                    "success!" => true,
                    "data" => $resultSekolah
                ], 200);
            } else {
                $resultAdmin = DB::select('
                    SELECT *
                    FROM user
                    JOIN admin ON admin.user_id = user.id
                    WHERE user.id = ?;
                ', [$userID]);

                if ($resultAdmin) {
                    Return response()->json([
                        "success!" => true,
                        "data" => $resultAdmin
                    ], 200);
                } else {
                    Return response()->json([
                        "success!" => false,
                        "data" => ''
                    ], 400);
                }
            }
        }
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
                    'data' => [
                        'user' => $resultUser,
                        'ota' => $resultOTA
                    ]
                ],200);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'data' => ''
                ],400);
            }
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
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
                    'data' => [
                        'user' => $resultUser,
                        'ota' => $resultSekolah
                    ]
                ],200);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'data' => ''
                ],400);
            }
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'data' => ''
            ],400);
        }
    }

    public function registerAdmin(Request $request) {
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $nama = $request->input('nama');
        $jabatan = $request->input('jabatan');

        DB::beginTransaction();
        $resultUser = $this->insertUser($email, $password);

        if ($resultUser) {
            $resultAdmin = Admin::create([
                'user_id' => $resultUser->id,
                'nama' => $nama,
                'jabatan' => $jabatan
            ]);

            if ($resultAdmin) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'data' => [
                        'user' => $resultUser,
                        'ota' => $resultAdmin
                    ]
                ],200);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'data' => ''
                ],400);
            }
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'data' => ''
            ],400);
        }
    }

    public function logout() {
        $userID = Auth::id();

        DB::beginTransaction();
        $result = DB::update('
            UPDATE `oauth_access_tokens`
            SET
                `oauth_access_tokens`.`revoked` = 1,
                `oauth_access_tokens`.`updated_at` = CURRENT_TIMESTAMP,
                `oauth_access_tokens`.`expires_at` = CURRENT_TIMESTAMP
            WHERE `oauth_access_tokens`.`user_id` = ?;
        ', [$userID]);

        if ($result) {
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $result
            ],200);
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'data' => ''
            ],400);
        }
    }
}
