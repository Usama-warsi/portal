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
        if(!Schema::hasTable('stores'))
        {
            Schema::create('stores', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('store_theme')->nullable();
                $table->string('theme_dir')->nullable();
                $table->string('domains')->nullable();
                $table->string('enable_storelink')->default('on');
                $table->string('enable_subdomain')->nullable();
                $table->string('subdomain')->nullable();
                $table->string('enable_domain')->default('off');
                $table->text('header_name')->nullable();
                $table->longText('about')->nullable();
                $table->string('tagline')->nullable();
                $table->string('slug')->nullable();
                $table->string('lang', 5)->default('en');
                $table->longText('storejs')->nullable();
                $table->string('currency')->default('$');
                $table->string('currency_code')->default('USD');
                $table->string('currency_symbol_position')->default('pre')->nullable();
                $table->string('currency_symbol_space')->default('without')->nullable();
                $table->string('certificate_template')->nullable();
                $table->string('certificate_color')->nullable();
                $table->string('certificate_gradiant')->nullable();
                $table->string('whatsapp')->nullable();
                $table->string('facebook')->nullable();
                $table->string('instagram')->nullable();
                $table->string('twitter')->nullable();
                $table->string('youtube')->nullable();
                $table->string('google_analytic')->nullable();
                $table->string('fbpixel_code')->nullable();
                $table->string('footer_note')->nullable();
                $table->string('enable_subscriber')->default('on');
                $table->string('enable_rating')->default('on');
                $table->string('blog_enable')->default('on');
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zipcode')->nullable();
                $table->string('country')->nullable();
                $table->string('logo')->nullable();
                $table->string('invoice_logo')->nullable();
                $table->integer('is_active')->default(1);
                $table->string('enable_whatsapp')->default('off');
                $table->string('whatsapp_number')->nullable();
                $table->integer('workspace_id')->default(0);
                $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('stores');
    }
};
