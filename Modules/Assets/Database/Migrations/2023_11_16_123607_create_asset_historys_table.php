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
        if(!Schema::hasTable('asset_historys'))
        {
            Schema::create('asset_historys', function (Blueprint $table) {
                $table->id();
                $table->integer('assets_id')->nullable();
                $table->text('type')->nullable();
                $table->text('quantity')->nullable();
                $table->date('date')->nullable();
                $table->integer('created_by')->default(0);
                $table->integer('workspace_id')->default(0);
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
        Schema::dropIfExists('asset_historys');
    }
};
