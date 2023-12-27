<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sk_lulus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->year('tahun');
            $table->string('lokasi', 100);
            $table->date('tanggal_penetapan');
            $table->string('nomor_lampiran', 100);
            $table->date('tanggal_lampiran');
            $table->string('tentang_lampiran', 100);
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
        Schema::dropIfExists('sk_lulus');
    }
};
