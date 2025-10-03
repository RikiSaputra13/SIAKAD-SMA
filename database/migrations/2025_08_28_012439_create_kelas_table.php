<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Mengembalikan kolom menjadi tidak boleh null
            $table->unsignedBigInteger('wali_kelas_id')->change();
        });
    }
};