<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanAnakAsuhDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_anak_asuh_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_anak_asuh_id')
                ->constrained('pengajuan_anak_asuh')
                ->onDelete('cascade');
            $table->foreignId('anak_asuh_id')
                ->constrained('anak_asuh')
                ->onDelete('cascade');
            $table->foreignId('paket_donasi_id')
                ->unique()
                ->nullable()
                ->constrained('paket_donasi')
                ->onDelete('cascade');
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
        Schema::dropIfExists('pengajuan_anak_asuh_detail');
    }
}
