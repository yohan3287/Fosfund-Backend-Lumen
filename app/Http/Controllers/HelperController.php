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

class HelperController extends Controller
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

    public function download(Request $request) {
        $filePath = $request->input('doc_path');

        return response()->download($filePath);
    }
}
