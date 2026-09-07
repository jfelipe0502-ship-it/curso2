<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'rol')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('rol')->default('lector')->after('password');
        });
    }

    public function down(): void
    {
        // The column is owned by the preceding role migration.
    }
};
