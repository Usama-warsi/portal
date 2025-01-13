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
        Schema::table('retainers', function (Blueprint $table) {
            if (!Schema::hasColumn('retainers', 'retainer_template')) {
                $table->string('retainer_template')->after('retainer_module')->nullable();
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
        Schema::table('retainers', function (Blueprint $table) {

        });
    }
};
