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
        if (Schema::hasColumn('form_responses', 'department')) {
            $table->dropColumn('department');
        }
    });
}

public function down()
{
    Schema::table('form_responses', function (Blueprint $table) {
        $table->string('department')->nullable()->after('address');
    });
}

};
