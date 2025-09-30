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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penjab_id')->constrained('penjabs')->onDelete('cascade');

            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('tempat_id')->constrained('tempats')->onDelete('cascade');

            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
