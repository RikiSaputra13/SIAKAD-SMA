<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Tambah kolom yang diperlukan
            if (!Schema::hasColumn('absensis', 'guru_id')) {
                $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('absensis', 'mapel_id')) {
                $table->unsignedBigInteger('mapel_id')->nullable();
            }
            
            if (!Schema::hasColumn('absensis', 'token_id')) {
                $table->foreignId('token_id')->nullable()->constrained('tokens')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('absensis', 'sesi')) {
                $table->string('sesi')->nullable();
            }
            
            if (!Schema::hasColumn('absensis', 'waktu')) {
                $table->time('waktu')->nullable();
            }
            
            if (!Schema::hasColumn('absensis', 'token_used')) {
                $table->string('token_used')->nullable();
            }
            
            // Ubah keterangan_izin menjadi keterangan jika perlu
            if (!Schema::hasColumn('absensis', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropForeign(['token_id']);
            $table->dropColumn([
                'guru_id',
                'mapel_id', 
                'token_id',
                'sesi',
                'waktu',
                'token_used',
                'keterangan'
            ]);
        });
    }
};