<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInnerchildFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('innerchild_folders', function (Blueprint $table) {
            $table->id();
            $table->integer('folder_id')->nullable();
            $table->string('innerchild_folder_name');
            $table->string('innerchild_folder_description')->nullable();
            $table->string('innerchild_folder_path')->nullable();
            $table->string('innerchild_folder_status')->nullable();
            $table->string('innerchild_folder_visibility')->nullable();  // Fixed invalid 'folder' method
            $table->string('innerchild_folder_access')->nullable();  // Fixed invalid 'folder' method
            $table->string('innerchild_folder_created_by')->nullable();  // Fixed invalid 'folder' method
            $table->string('innerchild_folder_updated_by')->nullable();  // Fixed invalid 'folder' method
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
        Schema::dropIfExists('innerchild_folders');
    }
}
