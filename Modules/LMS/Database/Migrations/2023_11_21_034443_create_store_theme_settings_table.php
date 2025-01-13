<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('store_theme_settings'))
        {
            Schema::create('store_theme_settings', function (Blueprint $table) {
                $table->id();
                $table->string('name')->comment('name/pagename');
                $table->text('value')->nullable()->comment('value/json_value');
                $table->string('theme_name')->nullable();
                $table->integer('store_id');
                $table->integer('created_by');
                $table->timestamps();
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
        Schema::dropIfExists('store_theme_settings');
    }
};
