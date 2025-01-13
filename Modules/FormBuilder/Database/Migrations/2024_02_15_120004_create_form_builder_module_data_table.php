<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('form_builder_module_data')) {
            Schema::create('form_builder_module_data', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('form_id');
                $table->integer('module');
                $table->text('response_data')->nullable();
                $table->integer('workspace')->nullable();
                $table->timestamps();
            });
        }
        if (Schema::hasTable('form_field_responses') && Schema::hasTable('form_builder_module_data')) {
            $formFieldResponse = DB::table('form_field_responses')->get();
            foreach ($formFieldResponse as $response) {
                DB::table('form_builder_module_data')->insert([
                    'form_id'   => $response->form_id,
                    'module'    => 1,
                    'response_data' => json_encode(
                        [
                            'subject_id'    => $response->subject_id,
                            'name_id'       => $response->name_id,
                            'email_id'      => $response->email_id,
                            'user_id'       => $response->user_id,
                            'pipeline_id'   => $response->pipeline_id,
                        ]),
                    'workspace'  => $response->workspace,
                    'created_at' => $response->created_at,
                    'updated_at' => $response->updated_at,
                ]);
            }
            Schema::dropIfExists('form_field_responses');

            $migrationFileName = DB::table('migrations')
            ->where('migration', 'like', "%create_form_field_responses_table%")
            ->first();

            if ($migrationFileName) {
                DB::table('migrations')->where('migration', $migrationFileName->migration)->delete();
                // $migrationFilePath = base_path('Modules/FormBuilder/Database/Migrations/' . $migrationFileName->migration . '.php');
                // File::delete($migrationFilePath);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_builder_module_data');
    }
};
