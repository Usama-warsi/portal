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
        if(Schema::hasTable('employees') && !Schema::hasColumn('employees', 'work_schedule','custom_day_off'))
        {
            Schema::table('employees', function($table) {
                $table->text('work_schedule')->after('employee_id')->nullable();
                $table->text('custom_day_off')->after('work_schedule')->nullable();

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
        Schema::dropIfExists('work_schedule');
    }
};
