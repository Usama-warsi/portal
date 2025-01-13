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
        if (Schema::hasTable('proposals') && !Schema::hasColumn('proposals', 'converted_retainer_id','is_convert_retainer')) {
            Schema::table('proposals', function (Blueprint $table) {

                $table->integer('converted_retainer_id')->after('converted_invoice_id')->default('0');
                $table->integer('is_convert_retainer')->after('is_convert')->default('0');
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
        Schema::table('proposals', function (Blueprint $table) {
        });
    }
};
