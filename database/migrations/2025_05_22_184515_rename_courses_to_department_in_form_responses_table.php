<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE `form_responses` CHANGE `courses` `department` VARCHAR(255)");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `form_responses` CHANGE `department` `courses` VARCHAR(255)");
    }
};
