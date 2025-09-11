<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('loan_type');
            $table->string('property_type');
            $table->string('property_address');
            $table->string('property_usage');
            $table->integer('number_of_people');
            $table->string('title');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();
            $table->decimal('income', 10, 2)->nullable();
            $table->string('super_an')->nullable();
            $table->text('other_assets')->nullable();
            $table->string('employment');
            $table->string('trust_account');
            $table->string('trust_name')->nullable();
            $table->string('share_account')->nullable();
            $table->json('liabilities');
            $table->json('loan_types')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicants');
    }
}
