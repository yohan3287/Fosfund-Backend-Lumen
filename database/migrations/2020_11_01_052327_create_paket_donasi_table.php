<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaketDonasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paket_donasi', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('pembayaran_id')
                ->constrained('pembayaran')
                ->onDelete('cascade');
            $table
                ->foreignId('admin_distributor_id')
                ->constrained('admin')
                ->onDelete('cascade');
            $table->string('tanggal_distribusi');
            $table->string('bukti_distribusi_img_path');
            $table->string('tanggal_penyerahan');
            $table->string('bukti_penyerahan_img_path');
            $table
                ->foreignId('admin_verifier_penyerahan_id')
                ->constrained('admin')
                ->onDelete('cascade');
            $table->string('waktu_verif_penyerahan');
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
        Schema::dropIfExists('paket_donasi');
    }
}
