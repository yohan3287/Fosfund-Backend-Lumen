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
            $table->foreignId('pembayaran_id')
                ->constrained('pembayaran')
                ->onDelete('cascade');
            $table->foreignId('admin_distributor_id')
                ->nullable()
                ->constrained('admin')
                ->onDelete('cascade');
            $table->string('tanggal_distribusi')
                ->nullable();
            $table->string('bukti_distribusi_doc_path')
                ->unique()
                ->nullable();
            $table->string('tanggal_penyerahan')
                ->nullable();
            $table->string('bukti_penyerahan_doc_path')
                ->unique()
                ->nullable();
            $table->foreignId('admin_verifier_penyerahan_id')
                ->nullable()
                ->constrained('admin')
                ->onDelete('cascade');
            $table->string('waktu_verif_penyerahan')
                ->nullable();
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
