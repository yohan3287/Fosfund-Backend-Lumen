<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\PaketDonasi;
use Illuminate\Http\Request;
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

    public function order ($ota_id, Request $request) {
        $jumlahPaketSd = (int)$request->input('jumlah_paket_sd');
        $jumlahPaketSmp = (int)$request->input('jumlah_paket_smp');

        DB::beginTransaction();
        $resultOrder = Order::create([
            'orang_tua_asuh_id' => $ota_id
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
                'message' => 'Register success!',
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
                'message' => 'insert order fail!',
                'data' => ''
            ],400);
        }
    }

    public function konfirmasiBayar ($ota_id, $order_id, Request $request) {
        $buktiBayarDocPath = $request->input('bukti_bayar_doc_path');

    }
}
