<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_folder', function (Blueprint $table) {
            $table->id();
            $table->string('folder_name');
            $table->string('folder_description')->nullable();  // Fixed nullable typo
            $table->string('folder_path')->nullable();
            $table->string('folder_status')->nullable();  // Fixed invalid 'folder' method
            $table->string('folder_visibility')->nullable();  // Fixed invalid 'folder' method
            $table->string('folder_access')->nullable();  // Fixed invalid 'folder' method
            $table->string('folder_created_by')->nullable();  // Fixed invalid 'folder' method
            $table->string('folder_updated_by')->nullable();  // Fixed invalid 'folder' method
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
        Schema::dropIfExists('main_folder');
    }
}
