<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Verificar se a tabela news_images existe
        if (Schema::hasTable('news_images')) {
            // Verificar se a coluna is_featured não existe e a coluna is_cover não existe
            if (!Schema::hasColumn('news_images', 'is_featured') && !Schema::hasColumn('news_images', 'is_cover')) {
                // Adicionar a coluna is_cover diretamente
                Schema::table('news_images', function (Blueprint $table) {
                    $table->boolean('is_cover')->default(false)->after('news_id');
                });
            }
            // Se is_featured existe, mas is_cover não
            else if (Schema::hasColumn('news_images', 'is_featured') && !Schema::hasColumn('news_images', 'is_cover')) {
                // Renomear is_featured para is_cover
                Schema::table('news_images', function (Blueprint $table) {
                    $table->renameColumn('is_featured', 'is_cover');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('news_images')) {
            if (Schema::hasColumn('news_images', 'is_cover')) {
                Schema::table('news_images', function (Blueprint $table) {
                    $table->renameColumn('is_cover', 'is_featured');
                });
            }
        }
    }
};
