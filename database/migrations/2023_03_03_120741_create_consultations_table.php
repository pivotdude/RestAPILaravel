<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('tel');
            $table->string('kid');
            $table->string('age');


            $table->integer('code')->unique();
            $table->tinyInteger('rating')->nullable();

            $table->unsignedBigInteger('fk_region');
            $table->foreign('fk_region')->references('id')->on('regions');

            $table->unsignedBigInteger('fk_organization');
            $table->foreign('fk_organization')->references('id')->on('organizations');

            $table->unsignedBigInteger('fk_category');
            $table->foreign('fk_category')->references('id')->on('categories');

            $table->unsignedBigInteger('fk_problem');
            $table->foreign('fk_problem')->references('id')->on('problems');

            $table->unsignedBigInteger('fk_consultant');
            $table->foreign('fk_consultant')->references('id')->on('consultants');

            $table->date('date');

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
        Schema::dropIfExists('consultations');
    }
}
