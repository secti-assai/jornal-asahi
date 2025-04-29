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
        Schema::table('news_images', function (Blueprint $table) {
            $table->renameColumn('is_featured', 'is_cover');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_images', function (Blueprint $table) {
            $table->renameColumn('is_cover', 'is_featured');
        });
    }
};
