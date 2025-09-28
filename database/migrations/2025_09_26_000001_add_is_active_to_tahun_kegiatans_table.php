<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tahun_kegiatans', function (Blueprint $table) {
            if (! Schema::hasColumn('tahun_kegiatans', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('tahun');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tahun_kegiatans', function (Blueprint $table) {
            if (Schema::hasColumn('tahun_kegiatans', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
