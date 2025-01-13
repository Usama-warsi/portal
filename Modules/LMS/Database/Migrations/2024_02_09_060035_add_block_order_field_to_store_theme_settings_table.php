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
        Schema::table('store_theme_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('store_theme_settings', 'block_order')) {
                $table->string('block_order')->nullable()->after('value');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_theme_settings', function (Blueprint $table) {
            $table->dropColumn('block_order');
        });
    }
};
