<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('added_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adder_user_id')->constrained('users'); // the one who adds
            $table->foreignId('added_user_id')->constrained('users'); // the user being added
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
        Schema::dropIfExists('added_users');
    }
}
