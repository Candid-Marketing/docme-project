<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_folders', function (Blueprint $table) {
            $table->id();
            $table->integer('folder_id')->nullable();
            $table->string('child_folder_name');
            $table->string('child_folder_description')->nullable();
            $table->string('child_folder_path')->nullable();
            $table->string('child_folder_status')->nullable();
            $table->string('child_folder_visibility')->nullable();  // Fixed invalid 'folder' method
            $table->string('child_folder_access')->nullable();  // Fixed invalid 'folder' method
            $table->string('child_folder_created_by')->nullable();  // Fixed invalid 'folder' method
            $table->string('child_folder_updated_by')->nullable();  // Fixed invalid 'folder' method
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
        Schema::dropIfExists('child_folders');
    }
}
