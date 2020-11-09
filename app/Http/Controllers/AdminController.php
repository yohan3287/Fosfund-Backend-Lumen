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

    public function verifPembayaran() {

    }

    public function verifSekolah() {

    }

    public function verifPengajuanAA() {

    }

    public function getMatchedData() {

    }
}
