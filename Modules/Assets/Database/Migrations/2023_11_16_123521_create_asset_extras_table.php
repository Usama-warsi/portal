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
        if(!Schema::hasTable('asset_extras'))
        {
            Schema::create('asset_extras', function (Blueprint $table) {
                $table->id();
                $table->integer('asset_id')->nullable();
                $table->text('code')->nullable();
                $table->integer('quantity')->nullable();
                $table->date('date')->nullable();
                $table->text('description')->nullable();
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
        Schema::dropIfExists('asset_extras');
    }
};
