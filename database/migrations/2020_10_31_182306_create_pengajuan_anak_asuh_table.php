<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanAnakAsuhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_anak_asuh', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('sekolah_id')
                ->constrained('sekolah')
                ->onDelete('cascade');
            $table->string('tahun_ajaran');
            $table
                ->foreignId('admin_verifier_id')
                ->constrained('admin')
                ->onDelete('cascade');
            $table->dateTime('waktu_admin_verif');
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
        Schema::dropIfExists('pengajuan_anak_asuh');
    }
}
