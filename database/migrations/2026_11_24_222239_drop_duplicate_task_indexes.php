<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP INDEX IF EXISTS tasks_user_id_status_index');
    }

    public function down(): void
    {
        // no-op: index is redundant
    }
};
