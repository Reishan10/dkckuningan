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
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->string('gudep')->after('pangkalan');
            $table->string('tahap_1')->after('berkas')->nullable();
            $table->string('tahap_2')->after('tahap_1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn('gudep');
            $table->dropColumn('tahap_1');
            $table->dropColumn('tahap_2');
        });
    }
};
