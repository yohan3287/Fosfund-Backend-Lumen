<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orang_tua_asuh_id')
                ->constrained('orang_tua_asuh')
                ->onDelete('cascade');
            $table->string('bukti_bayar_doc_path')
                ->unique()
                ->nullable();
            $table->foreignId('admin_verifier_id')
                ->nullable()
                ->constrained('admin')
                ->onDelete('cascade');
            $table->dateTime('waktu_verif')
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
        Schema::dropIfExists('pembayaran');
    }
}
