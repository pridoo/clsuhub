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
    Schema::table('posts', function (Blueprint $table) {
        $table->enum('privacy', ['public', 'department'])->default('public')->after('content');
        $table->unsignedBigInteger('department_id')->nullable()->after('privacy');

        $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('posts', function (Blueprint $table) {
        // Check muna kung may foreign key bago i-drop
        if (Schema::hasColumn('posts', 'department_id')) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        }

        if (Schema::hasColumn('posts', 'privacy')) {
            $table->dropColumn('privacy');
        }
    });
}


};
