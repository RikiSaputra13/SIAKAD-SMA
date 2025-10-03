<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->unsignedBigInteger('wali_kelas_id')->nullable()->after('nama_kelas');
            $table->foreign('wali_kelas_id')->references('id')->on('gurus')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['wali_kelas_id']);
            $table->dropColumn('wali_kelas_id');
        });
    }
};