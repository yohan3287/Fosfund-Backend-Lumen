<?php

namespace App\Http\Controllers;


use App\Models\OrangTuaAsuh;
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



    public function getHistory ($ota_id) {
        $resultWithoutAA = DB::table('order')
            ->join('paket_donasi', 'paket_donasi.order_id', 'order.id')
            ->join('pengajuan_anak_asuh_detail', 'pengajuan_anak_asuh_detail.paket_donasi_id', 'paket_donasi.id')
            ->where('order.orang_tua_asuh_id', $ota_id)
//            ->select('paket_donasi.id')
            ->get();


        $resultWithAA = DB::table('order')
            ->join('paket_donasi', 'paket_donasi.order_id', 'order.id')
            ->join('pengajuan_anak_asuh_detail', 'pengajuan_anak_asuh_detail.paket_donasi_id', 'paket_donasi.id')
            ->join('anak_asuh', 'anak_asuh')
            ->where('order.orang_tua_asuh_id', $ota_id)
//            ->select('paket_donasi.id')
            ->union($resultWithoutAA)
            ->get();

        return response()->json([
            'result' => $resultWithAA
        ]);
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
                'message' => 'Insert order success!',
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
                'message' => 'Insert order fail!',
                'data' => ''
            ],400);
        }
    }

    public function confirmPayment ($ota_id, $order_id, Request $request) {
        $buktiBayarDocPath = $request->input('bukti_bayar_doc_path');
//        dd($buktiBayarDocPath);
        DB::beginTransaction();
        $result = Order::findOrFail($order_id)
            ->update(['bukti_bayar_doc_path' => $buktiBayarDocPath]);

        if ($request) {
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Insert bukti bayar success!',
                'data' => $result
            ],200);
        } else {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Insert bukti bayar fail!',
                'data' => ''
            ],400);
        }
    }
}
