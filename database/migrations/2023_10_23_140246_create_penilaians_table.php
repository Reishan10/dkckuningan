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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pendaftaran_id');
            $table->foreign('pendaftaran_id')->references('id')->on('pendaftaran')->onDelete('cascade');
            $table->uuid('soal_id');
            $table->foreign('soal_id')->references('id')->on('soal')->onDelete('cascade');
            $table->string('nilai', 100)->default(0);
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
        Schema::dropIfExists('penilaian');
    }
};
