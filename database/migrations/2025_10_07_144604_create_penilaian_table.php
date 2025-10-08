<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->string('mata_pelajaran');
            $table->integer('tahun_ajaran');
            $table->integer('semester');
            
            // Nilai per komponen
            $table->decimal('nilai_uh', 5, 2)->default(0);
            $table->decimal('nilai_uts', 5, 2)->default(0);
            $table->decimal('nilai_uas', 5, 2)->default(0);
            $table->decimal('nilai_tugas', 5, 2)->default(0);
            $table->decimal('nilai_praktik', 5, 2)->default(0);
            
            // Nilai akhir
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->string('predikat')->nullable();
            $table->text('deskripsi')->nullable();
            
            $table->timestamps();

            // Gunakan nama indeks yang lebih pendek
            $table->unique([
                'siswa_id', 
                'mata_pelajaran', 
                'kelas_id', 
                'tahun_ajaran', 
                'semester'
            ], 'penilaian_unique_key');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian');
    }
};