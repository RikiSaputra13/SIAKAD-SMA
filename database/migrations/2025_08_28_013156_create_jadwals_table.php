<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('guru_id');
            $table->string('hari');
            $table->string('mata_pelajaran');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();

            // foreign key
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
