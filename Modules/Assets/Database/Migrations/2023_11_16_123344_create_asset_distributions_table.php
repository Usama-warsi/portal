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
        if(!Schema::hasTable('asset_distributions'))
        {
            Schema::create('asset_distributions', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id')->nullable();
                $table->text('serial_code')->nullable();
                $table->float('dist_number');
                $table->date('assign_date');
                $table->date('return_date');
                $table->text('quantity');
                $table->text('assets_branch')->nullable();
                $table->text('notes');
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
        Schema::dropIfExists('asset_distributions');
    }
};
