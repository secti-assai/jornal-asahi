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
        // Vamos verificar primeiro se a coluna existe antes de tentar renomeá-la
        if (Schema::hasTable('news_images') && Schema::hasColumn('news_images', 'is_featured')) {
            Schema::table('news_images', function (Blueprint $table) {
                $table->renameColumn('is_featured', 'is_cover');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('news_images') && Schema::hasColumn('news_images', 'is_cover')) {
            Schema::table('news_images', function (Blueprint $table) {
                $table->renameColumn('is_cover', 'is_featured');
            });
        }
    }
};
