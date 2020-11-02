<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnakAsuhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anak_asuh', function (Blueprint $table) {
            $table->id();
            $table->string('NISN')
                ->unique();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('kelas');
            $table->string('alamat');
            $table->string('nama_ayah');
            $table->string('pekerjaan_ayah')
                ->nullable();
            $table->string('nama_ibu');
            $table->string('pekerjaan_ibu')
                ->nullable();
            $table->string('bantuan_lain')
                ->nullable();
            $table->string('status');
            $table->string('catatan')
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
        Schema::dropIfExists('anak_asuh');
    }
}
