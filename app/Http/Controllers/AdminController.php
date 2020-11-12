<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\PaketDonasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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

    public function trueJsonResponse($result) {
        return response()->json([
            "success!" => false,
            "data" => $result
        ], 200);
    }

    public function falseJsonResponse() {
        return response()->json([
            "success!" => false,
            "data" => ''
        ], 400);
    }

    private function getAdminID() {
        $userID = Auth::id();

        $result = DB::select('
            SELECT `id`
            FROM `admin`
            WHERE `user_id` = ?;
        ', [$userID]);

        return (int)$result[0]->id;
    }

    public function publishBerita($judul, $konten, Request $request) {
        $adminID = $this->getAdminID();

        if ($adminID) {
            $file = $request->file('doc');
            $fileName = Carbon::now().'-'.$judul.'-'.$file->getClientOriginalName();
            $fileDirectory = 'uploads/berita/images';
            $filePath = $file->storeAs($fileDirectory , $fileName);

            DB::beginTransaction();
            $result = Berita::create([
                'judul' => $judul,
                'konten' => $konten,
                'image_path' => $filePath,
                'admin_publisher_id' => $adminID
            ]);

            if ($result) {
                DB::commit();
                return $this->trueJsonResponse($result);
            } else {
                DB::rollBack();
            }
        }

        return $this->falseJsonResponse();
    }

    public function getUnverifiedPembayaran() {
        if ($this->getAdminID()) {
            $result = DB::select('
                SELECT *
                FROM `order`
                WHERE `order`.`bukti_bayar_doc_path` != NULL
                    AND `order`.`waktu_verif_pembayaran` = NULL;
            ');

            if ($result) {
                return $this->trueJsonResponse($result);
            }
        }

        return $this->falseJsonResponse();
    }

    public function verifPembayaran($order_id) {
        $adminID = $this->getAdminID();

        if ($adminID) {
            DB::beginTransaction();
            $result = DB::update('
                UPDATE `order`
                SET `order`.`admin_verifier_pembayaran_id` = ?,
                    `order`.`waktu_verif_pembayaran` = CURRENT_TIMESTAMP
                WHERE `id` = ?
                    AND `order`.`bukti_bayar_doc_path` != NULL
                    AND `order`.`waktu_verif_pembayaran` = NULL;
            ', [$adminID, $order_id]);

            if ($result) {
                DB::commit();
                return $this->trueJsonResponse($result);
            } else {
                DB::rollBack();
            }
        }

        return $this->falseJsonResponse();
    }

    public function denyPembayaran($order_id) {
        $adminID = $this->getAdminID();

        if ($adminID) {
            DB::beginTransaction();
            $result = DB::update('
                UPDATE `order`
                SET `order`.`admin_verifier_pembayaran_id` = ?,
                    `order`.`waktu_verif_pembayaran` = NULL
                WHERE `id` = ?
                    AND `order`.`bukti_bayar_doc_path` != NULL
                    AND `order`.`waktu_verif_pembayaran` = NULL;
            ', [$adminID, $order_id]);

            if ($result) {
                DB::commit();
                return $this->trueJsonResponse($result);
            } else {
                DB::rollBack();
            }
        }

        return $this->falseJsonResponse();
    }

    public function getUnverifiedSekolah() {
        if ($this->getAdminID()) {
            $result = DB::select('
                SELECT *
                FROM `sekolah`
                WHERE `sekolah`.`waktu_verif` = NULL;
            ');

            if ($result) {
                return $this->trueJsonResponse($result);
            }
        }

        return $this->falseJsonResponse();
    }

    public function verifSekolah($sekolah_id) {
        $adminID = $this->getAdminID();

        if ($adminID) {
            DB::beginTransaction();
            $result = DB::update('
                UPDATE `sekolah`
                SET `sekolah`.`admin_verifier_id` = ?,
                    `sekolah`.`waktu_verif` = CURRENT_TIMESTAMP
                WHERE `sekolah`.`id` = ?
                    AND `sekolah`.`waktu_verif` = NULL;
            ', [$adminID, $sekolah_id]);

            if ($result) {
                DB::commit();
                return $this->trueJsonResponse($result);
            } else {
                DB::rollBack();
            }
        }

        return $this->falseJsonResponse();
    }

    public function denySekolah($sekolah_id) {
        $adminID = $this->getAdminID();

        if ($adminID) {
            DB::beginTransaction();
            $result = DB::update('
                UPDATE `sekolah`
                SET `sekolah`.`admin_verifier_id` = ?,
                    `sekolah`.`waktu_verif` = NULL
                WHERE `sekolah`.`id` = ?
                    AND `sekolah`.`waktu_verif` = NULL;
            ', [$adminID, $sekolah_id]);

            if ($result) {
                DB::commit();
                return $this->trueJsonResponse($result);
            } else {
                DB::rollBack();
            }
        }

        return $this->falseJsonResponse();
    }

    public function getUnverifiedPengajuanAA() {
        if ($this->getAdminID()) {
            $result = DB::select('
                SELECT *
                FROM `pengajuan_anak_asuh`
                WHERE `pengajuan_anak_asuh`.`waktu_verif` = NULL
                    AND `pengajuan_anak_asuh`.`form_doc_path` != NULL;
            ');

            if ($result) {
                return $this->trueJsonResponse($result);
            }
        }

        return $this->falseJsonResponse();
    }

    public function verifPengajuanAA($pengajuan_aa_id) {
        $adminID = $this->getAdminID();

        if ($adminID) {
            DB::beginTransaction();
            $result = DB::update('
                UPDATE `pengajuan_anak_asuh`
                SET  `pengajuan_anak_asuh`.`admin_verifier_id` = ?,
                    `pengajuan_anak_asuh`.`waktu_verif` = CURRENT_TIMESTAMP
                WHERE `pengajuan_anak_asuh`.`id` = ?
                    AND `pengajuan_anak_asuh`.`waktu_verif` = NULL
                    AND `pengajuan_anak_asuh`.`form_doc_path` != NULL;
            ', [$adminID, $pengajuan_aa_id]);

            if ($result) {
                DB::commit();
                return $this->trueJsonResponse($result);
            } else {
                DB::rollBack();
            }
        }

        return $this->falseJsonResponse();
    }

    public function denyPengajuanAA($pengajuan_aa_id) {
        $adminID = $this->getAdminID();

        if ($adminID) {
            DB::beginTransaction();
            $result = DB::update('
                UPDATE `pengajuan_anak_asuh`
                SET  `pengajuan_anak_asuh`.`admin_verifier_id` = ?,
                    `pengajuan_anak_asuh`.`waktu_verif` = NULL
                WHERE `pengajuan_anak_asuh`.`id` = ?
                    AND `pengajuan_anak_asuh`.`waktu_verif` = NULL
                    AND `pengajuan_anak_asuh`.`form_doc_path` != NULL;
            ', [$adminID, $pengajuan_aa_id]);

            if ($result) {
                DB::commit();
                return $this->trueJsonResponse($result);
            } else {
                DB::rollBack();
            }
        }

        return $this->falseJsonResponse();
    }

    public function getMatchedData() {
        if ($this->getAdminID()) {
            $result = DB::select('
                SELECT
                    `sekolah`.`id` AS sekolah_id,
                    `sekolah`.`jenjang_pendidikan` AS jenjang_pendidikan,
                    `sekolah`.`nama` AS nama_sekolah,
                    `sekolah`.`provinsi` AS provinsi_sekolah,
                    `anak_asuh`.`id` AS anak_asuh_id,
                    `anak_asuh`.`nama` AS nama_anak_asuh,
                    `anak_asuh`.`kelas` AS kelas_anak_asuh,
                    `pengajuan_anak_asuh`.`tahun_ajaran` AS tahun_ajaran,
                    `orang_tua_asuh`.`id` AS orang_tua_asuh_id,
                    `orang_tua_asuh`.`nama` AS nama_orang_tua_asuh
                FROM `orang_tua_asuh`
                JOIN `order` ON `order`.`orang_tua_asuh_id` = `orang_tua_asuh`.`id`
                JOIN `paket_donasi` ON `paket_donasi`.`order_id` = `order`.`id`
                JOIN `pengajuan_anak_asuh_detail` ON `pengajuan_anak_asuh_detail`.`paket_donasi_id` = `paket_donasi`.`id`
                JOIN `anak_asuh` ON `anak_asuh`.`id` = `pengajuan_anak_asuh_detail`.`anak_asuh_id`
                JOIN `pengajuan_anak_asuh` ON `pengajuan_anak_asuh`.`id` = `pengajuan_anak_asuh_detail`.`pengajuan_anak_asuh_id`
                JOIN `sekolah` ON `sekolah`.`id` = `pengajuan_anak_asuh`.`sekolah_id`;
            ');

            if ($result) {
                return $this->trueJsonResponse($result);
            }
        }

        return $this->falseJsonResponse();
    }

    public function inputDistribusiDonasi(Request $request) {
        $adminID = $this->getAdminID();

        if ($adminID) {
            $paketDonasiID = $request->input('paket_donasi_id');
            $tanggalDistribusi = $request->input('tanggal_distribusi');

            $file = $request->file('doc');
            $fileName = Carbon::now().'-bukti_distribusi-'.$file->getClientOriginalName();
            $fileDirectory = 'uploads/paket_donasi/'.$paketDonasiID.'/distribusi';
            $filePath = $file->storeAs($fileDirectory , $fileName);

            DB::beginTransaction();
            $result = PaketDonasi::findOrFail($paketDonasiID)
                ->update([
                    'admin_distributor_id' => $adminID,
                    'tanggal_distribusi' => $tanggalDistribusi,
                    'bukti_distribusi_doc_path' => $filePath
                ]);

            if ($request) {
                DB::commit();
                return $this->trueJsonResponse($request);
            } else {
                DB::rollBack();
            }
        }

        return $this->falseJsonResponse();
    }
}
