<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToCommentsTable extends Migration
{
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Nullable unsignedBigInteger for parent_id (self-reference)
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');

            // Foreign key constraint to comments.id with cascade on delete
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Drop foreign key before dropping column
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}
