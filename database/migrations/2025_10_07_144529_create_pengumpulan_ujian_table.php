<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengumpulan_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('ujian_id')->constrained('ujian')->onDelete('cascade');
            $table->string('berkas_jawaban')->nullable();
            $table->text('catatan_siswa')->nullable();
            $table->text('catatan_guru')->nullable();
            $table->decimal('nilai', 5, 2)->default(0);
            $table->datetime('waktu_pengumpulan');
            $table->enum('status', ['belum_dikumpulkan', 'dikumpulkan', 'dinilai', 'terlambat'])->default('belum_dikumpulkan');
            $table->timestamps();

            $table->unique(['siswa_id', 'ujian_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengumpulan_ujian');
    }
};