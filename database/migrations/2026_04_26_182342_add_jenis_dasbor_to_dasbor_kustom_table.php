<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dasbor_kustom', function (Blueprint $table) {
            $table->enum('jenis_dasbor', ['default', 'custom'])
                  ->default('custom')
                  ->after('nama_dasbor');
        });
    }

    public function down(): void
    {
        Schema::table('dasbor_kustom', function (Blueprint $table) {
            $table->dropColumn('jenis_dasbor');
        });
    }
};