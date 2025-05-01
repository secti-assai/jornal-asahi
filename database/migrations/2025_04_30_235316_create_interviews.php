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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_video_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('featured')->default(false);
            $table->dateTime('interview_date')->nullable();
            $table->string('interviewee')->nullable(); // Pessoa entrevistada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
