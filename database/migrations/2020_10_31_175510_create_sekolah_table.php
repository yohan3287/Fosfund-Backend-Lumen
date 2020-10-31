<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSekolahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('user_id')
                ->constrained('user')
                ->onDelete('cascade');
            $table->string('NPSN');
            $table->string('jenjang_pendidikan');
            $table->string('nama');
            $table->string('alamat');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kabupaten_kota');
            $table->string('provinsi');
            $table->string('kode_pos');
            $table->string('telepon');
            $table->string('status');
            $table->string('nama_kepala_sekolah');
            $table->string('NRKS');
            $table->string('KTP_kepala_sekolah_img_path');
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
        Schema::dropIfExists('sekolah');
    }
}
