<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('full_name');
            $table->string('address');
            $table->string('courses');
            $table->string('specialization');
            $table->year('graduation_year');
            $table->string('graduate_studies_within_12m')->nullable();
            $table->string('present_employment')->nullable();
            $table->string('had_job_before')->nullable();
            $table->date('first_employment_date')->nullable();
            $table->string('first_workplace')->nullable();
            $table->string('position')->nullable();
            $table->string('employer')->nullable();
            $table->string('office_address')->nullable();
            $table->string('employer_contact')->nullable();
            $table->string('time_to_first_job')->nullable();
            $table->string('job_related_to_degree')->nullable();

            // Optional groups stored as JSON arrays
            $table->json('optional_group_a_points')->nullable();
            $table->string('optional_group_a_title')->nullable();

            $table->json('optional_group_b_points')->nullable();
            $table->string('optional_group_b_title')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_responses');
    }
}
