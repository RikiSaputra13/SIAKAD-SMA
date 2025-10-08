<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tipe_ujian_id')->constrained('tipe_ujian')->onDelete('cascade');
            $table->string('mata_pelajaran');
            $table->string('judul_ujian');
            $table->text('deskripsi')->nullable();
            $table->string('berkas_soal')->nullable();
            $table->string('berkas_kunci_jawaban')->nullable();
            $table->integer('total_nilai')->default(100);
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
            $table->datetime('batas_pengumpulan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['draft', 'published', 'completed'])->default('draft');
            $table->text('instruksi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ujian');
    }
};