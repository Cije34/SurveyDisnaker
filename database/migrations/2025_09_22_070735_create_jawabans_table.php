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
        Schema::create('jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('peserta_id')->constrained('pesertas')->cascadeOnDelete();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->text('jawaban')->nullable();
            $table->enum('tipe', ['essay', 'survey']); // tipe jawaban
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawabans');
    }
};
