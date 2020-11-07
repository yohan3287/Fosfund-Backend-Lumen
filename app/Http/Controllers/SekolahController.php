<?php

namespace App\Http\Controllers;

use App\Models\AnakAsuh;
use App\Models\PengajuanAnakAsuh;
use App\Models\PengajuanAnakAsuhDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SekolahController extends Controller
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

    public function pengajuanAA ($sekolah_id ,Request $request) {
        $tahun_ajaran = $request->input('tahun_ajaran');
        $siswa = $request->input('siswa');

        DB::beginTransaction();
        $resultPengajuanAA = PengajuanAnakAsuh::create([
            'sekolah_id' => $sekolah_id,
            'tahun_ajaran' => $tahun_ajaran
        ]);

        if ($resultPengajuanAA) {
            $pengajuan_anak_asuh_id = $resultPengajuanAA->id;

            $index = 0;
            foreach ($siswa as $s){
                $resultAA[$index] = AnakAsuh::create([
                    'NISN' => $s['NISN'],
                    'nama' => $s['nama'],
                    'tempat_lahir' => $s['tempat_lahir'],
                    'tanggal_lahir' => $s['tanggal_lahir'],
                    'jenis_kelamin' => $s['jenis_kelamin'],
                    'kelas' => $s['kelas'],
                    'alamat' => $s['alamat'],
                    'nama_ayah' => $s['nama_ayah'],
                    'pekerjaan_ayah' => $s['pekerjaan_ayah'],
                    'nama_ibu' => $s['nama_ibu'],
                    'pekerjaan_ibu' => $s['pekerjaan_ibu'],
                    'bantuan_lain' => $s['bantuan_lain'],
                    'status' => $s['status'],
                    'catatan' => $s['catatan']
                ]);

                $resultPengajuanAADetail = PengajuanAnakAsuhDetail::create([
                    'pengajuan_anak_asuh_id' => $pengajuan_anak_asuh_id,
                    'anak_asuh_id' => $resultAA[$index]->id
                ]);

                $index++;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Insert pengajuan anak asuh success!',
                'data' => [
                    'pengajuan_anak_asuh' => $resultPengajuanAA,
                    'anak_asuh' => $resultAA
                ]
            ],200);
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Insert pengajuan anak asuh fail!',
                'data' => ''
            ],400);
        }
    }
}
