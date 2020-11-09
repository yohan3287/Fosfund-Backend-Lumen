<?php

namespace App\Http\Controllers;

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

    private function getAdminID () {
        $userID = Auth::id();

        $result = DB::select('
            SELECT id
            FROM admin
            WHERE admin.user_id = ?;
        ', [$userID]);

        return (int)$result[0]->id;
    }

    public function verifPembayaran($order_id) {
        $adminID = $this->getAdminID();

        DB::beginTransaction();
        $result = DB::update('
            UPDATE order
            SET order.admin_verifier_pembayaran_id = ?, order.waktu_verif_pembayaran = CURRENT_TIMESTAMP
            WHERE order.id = ? AND order.bukti_bayar_doc_path != NULL;
        ', [$adminID, $order_id]);

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

    public function verifSekolah($sekolah_id) {
        $adminID = $this->getAdminID();

        DB::beginTransaction();
        $result = DB::update('
            UPDATE sekolah
            SET sekolah.admin_verifier_id = ?, sekolah.waktu_verif = CURRENT_TIMESTAMP
            WHERE sekolah.id = ?;
        ', [$adminID, $sekolah_id]);

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

    public function verifPengajuanAA($pengajuan_aa_id) {
        $adminID = $this->getAdminID();

        DB::beginTransaction();
        $result = DB::update('
            UPDATE pengajuan_anak_asuh
            SET  pengajuan_anak_asuh.admin_verifier_id = ?, pengajuan_anak_asuh.waktu_verif = CURRENT_TIMESTAMP
            WHERE pengajuan_anak_asuh.id = ? AND pengajuan_anak_asuh.form_doc_path != NULL;
        ', [$adminID, $pengajuan_aa_id]);

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

    public function getMatchedData() {

    }
}
