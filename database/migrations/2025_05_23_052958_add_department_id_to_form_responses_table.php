<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('form_responses', function (Blueprint $table) {
        if (!Schema::hasColumn('form_responses', 'department_id')) {
            $table->unsignedBigInteger('department_id')->nullable()->after('address');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        }
    });
}

public function down()
{
    Schema::table('form_responses', function (Blueprint $table) {
        if (Schema::hasColumn('form_responses', 'department_id')) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        }
    });
}

};
