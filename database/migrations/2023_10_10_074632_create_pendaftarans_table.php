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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('nta', 100);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('jenis_kelamin', 100);
            $table->string('kwaran', 100);
            $table->string('pangkalan', 100);
            $table->uuid('golongan_id');
            $table->foreign('golongan_id')->references('id')->on('golongan')->onDelete('cascade');
            $table->string('berkas');
            $table->integer('status')->default(1)->comment('1 = Proses, 2 = Terima, 3 = Tolak');
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
        Schema::dropIfExists('pendaftaran');
    }
};
