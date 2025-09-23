<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_peserta', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('peserta_id')->constrained('pesertas')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_peserta');
    }
};
