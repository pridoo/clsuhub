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
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('department_id')->nullable()->after('id');

        $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'department_id')) {
            // Mas ligtas gamitin ang array version
            try {
                $table->dropForeign(['department_id']);
            } catch (\Exception $e) {
                // Ignore error kung wala ang foreign key
            }

            $table->dropColumn('department_id');
        }
    });
}


};
