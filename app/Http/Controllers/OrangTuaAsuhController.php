<?php

namespace App\Http\Controllers;


use App\Models\OrangTuaAsuh;
use App\Models\Order;
use App\Models\PaketDonasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrangTuaAsuhController extends Controller
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

    private function getOTAID () {
        $userID = Auth::id();

        $result = DB::select('
            SELECT id
            FROM orang_tua_asuh
            WHERE orang_tua_asuh.user_id = ?;
        ', [$userID]);

        return (int)$result[0]->id;
    }

    public function getRiwayat () {
        $otaID = $this->getOTAID();

        $result = DB::select('
            SELECT
                order.id AS order_id,
                order.bukti_bayar_doc_path,
                order.waktu_verif_pembayaran,
                paket_donasi.id AS paket_donasi_id,
                paket_donasi.nama AS paket_donasi,
                paket_donasi.tanggal_distribusi,
                paket_donasi.tanggal_penyerahan,
                paket_donasi.waktu_verif_penyerahan,
                a.anak_asuh_id,
                a.nama,
                a.NISN
            FROM `order`
            JOIN paket_donasi ON order.id = paket_donasi.order_id
            LEFT JOIN (
                SELECT
                    pengajuan_anak_asuh_detail.paket_donasi_id,
                    pengajuan_anak_asuh_detail.anak_asuh_id,
                    anak_asuh.NISN,
                    anak_asuh.nama
                FROM `pengajuan_anak_asuh_detail`
                JOIN anak_asuh ON anak_asuh.id = pengajuan_anak_asuh_detail.anak_asuh_id
            ) as a ON paket_donasi.id = a.paket_donasi_id
            WHERE order.orang_tua_asuh_id = ?;
        ', [$otaID]);

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

    public function order (Request $request) {
        $otaID = $this->getOTAID();
        $jumlahPaketSd = (int)$request->input('jumlah_paket_sd');
        $jumlahPaketSmp = (int)$request->input('jumlah_paket_smp');

        DB::beginTransaction();
        $resultOrder = Order::create([
            'orang_tua_asuh_id' => $otaID
        ]);

        if ($resultOrder) {
            for ($n = 0; $n < $jumlahPaketSd; $n++) {
                $resultPaketDonasiSD250K[$n] = PaketDonasi::create([
                    'nama' => 'sd_250k',
                    'order_id' => $resultOrder->id
                ]);
            }

            for ($n = 0; $n < $jumlahPaketSmp; $n++) {
                $resultPaketDonasiSMP300K[$n] = PaketDonasi::create([
                    'nama' => 'smp_300k',
                    'order_id' => $resultOrder->id
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => [
                    'order' => $resultOrder,
                    'paket_donasi_sd_250k' => $resultPaketDonasiSD250K,
                    'paket_donasi_smp_300K' => $resultPaketDonasiSMP300K
                ]
            ],200);
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'data' => ''
            ],400);
        }
    }

    public function confirmPayment ($order_id, Request $request) {
        $otaID = $this->getOTAID();

        $validUser = DB::select('
            SELECT *
            FROM order
            WHERE order.orang_tua_asuh_id = ? AND order.id = ?;
        ', [$otaID, $order_id]);

        if ($validUser) {
            $file = $request->file('file');
            $fileName = Carbon::now().'-'.$file->getClientOriginalName();
            $fileDirectory = 'uploads/ota/'.$otaID;
            $filePath = $file->storeAs($fileDirectory , $fileName);

            DB::beginTransaction();
            $result = Order::findOrFail($order_id)
                ->update(['bukti_bayar_doc_path' => $filePath]);

            if ($request) {
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
        } else {
            return response()->json([
                'success' => false,
                'data' => ''
            ],400);
        }
    }
}
