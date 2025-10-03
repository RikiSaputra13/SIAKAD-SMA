<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Hapus kolom email dan password dari siswa (karena sudah ada di users)
            if (Schema::hasColumn('siswas', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('siswas', 'password')) {
                $table->dropColumn('password');
            }
            
            // Tambahkan user_id sebagai foreign key
            if (!Schema::hasColumn('siswas', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            
            // Kembalikan kolom email dan password jika diperlukan
            $table->string('email')->nullable();
            $table->string('password')->nullable();
        });
    }
};