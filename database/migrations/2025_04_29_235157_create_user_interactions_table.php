<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_interactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Usuário que recebeu a interação
            $table->unsignedBigInteger('visitor_id')->nullable(); // Usuário que fez a interação (null se anônimo)
            $table->string('visitor_name')->nullable(); // Nome do visitante (para comentários anônimos)
            $table->text('comment')->nullable();
            $table->boolean('like')->default(false);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('visitor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_interactions');
    }
};
