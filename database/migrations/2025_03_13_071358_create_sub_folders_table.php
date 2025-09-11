<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_folders', function (Blueprint $table) {
            $table->id();
            $table->integer('folder_id')->nullable();
            $table->string('sub_folder_name');
            $table->string('sub_folder_description')->nullable();  // Fixed nullable typo
            $table->string('sub_folder_path')->nullable();
            $table->string('sub_folder_status')->nullable();  // Fixed invalid 'folder' method
            $table->string('sub_folder_visibility')->nullable();  // Fixed invalid 'folder' method
            $table->string('sub_folder_access')->nullable();  // Fixed invalid 'folder' method
            $table->string('sub_folder_created_by')->nullable();  // Fixed invalid 'folder' method
            $table->string('sub_folder_updated_by')->nullable();  // Fixed invalid 'folder' method
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_folders');
    }
}
