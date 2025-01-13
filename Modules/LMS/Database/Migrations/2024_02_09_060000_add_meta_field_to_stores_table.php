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
        Schema::table('stores', function (Blueprint $table) {
             // if not exist, add the new column
            if (!Schema::hasColumn('stores', 'meta_keyword')) {
                $table->string('meta_keyword')->nullable()->after('youtube');
                $table->string('meta_description')->nullable()->after('meta_keyword');
                $table->string('meta_image')->nullable()->after('meta_description');
                $table->string('enable_pwa')->default('off')->after('meta_image');
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
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('meta_keyword');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_image');
        });
    }
};
