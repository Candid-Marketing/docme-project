<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFolderInvitationsTable extends Migration
{
    public function up()
    {
        Schema::create('folder_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folder_id');
            $table->unsignedBigInteger('inviter_id');
            $table->string('guest_email');
            $table->text('message')->nullable();
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->uuid('token')->unique();
            $table->timestamps();

            $table->foreign('folder_id')->references('id')->on('user_structure_folders')->onDelete('cascade');
            $table->foreign('inviter_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('folder_invitations');
    }
}
