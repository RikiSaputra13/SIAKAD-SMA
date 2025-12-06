<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Pastikan table mapels sudah ada sebelum membuat foreign key
        if (!Schema::hasTable('mapels')) {
            // Jika table mapels belum ada, buat dulu kolom tanpa foreign key
            Schema::table('tokens', function (Blueprint $table) {
                $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('cascade');
                $table->unsignedBigInteger('mapel_id')->nullable();
                $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('cascade');
                $table->date('attendance_date')->nullable();
                $table->string('jam_mulai')->nullable();
                $table->string('jam_selesai')->nullable();
            });
        } else {
            // Jika table mapels sudah ada, buat dengan foreign key
            Schema::table('tokens', function (Blueprint $table) {
                $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('cascade');
                $table->foreignId('mapel_id')->nullable()->constrained('mapels')->onDelete('cascade');
                $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('cascade');
                $table->date('attendance_date')->nullable();
                $table->string('jam_mulai')->nullable();
                $table->string('jam_selesai')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::table('tokens', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropForeign(['mapel_id']);
            $table->dropForeign(['kelas_id']);
            $table->dropColumn([
                'guru_id', 
                'mapel_id', 
                'kelas_id', 
                'attendance_date',
                'jam_mulai',
                'jam_selesai'
            ]);
        });
    }
};