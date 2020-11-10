<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class BeritaController extends Controller
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

    public function getNews() {
        $result = DB::select('
            SELECT *
            FROM `berita`;
        ');

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
