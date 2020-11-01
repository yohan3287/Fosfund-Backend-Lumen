<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembelanjaanDonasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelanjaan_donasi', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('paket_donasi_id')
                ->constrained('paket_donasi')
                ->onDelete('cascade');
            $table->date('tanggal');
            $table->string('bukti_belanja_img_path');
            $table
                ->foreignId('admin_verifier_id')
                ->constrained('admin')
                ->onDelete('cascade');
            $table->dateTime('waktu_verif');
            $table->text('catatan_admin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelanjaan_donasi');
    }
}
