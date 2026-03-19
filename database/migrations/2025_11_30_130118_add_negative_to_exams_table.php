<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'negative_enabled')) {
                $table->boolean('negative_enabled')->default(false)->after('duration_minutes');
            }
            if (!Schema::hasColumn('exams', 'negative_marking')) {
                $table->float('negative_marking')->default(0)->after('negative_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'negative_marking')) {
                $table->dropColumn('negative_marking');
            }
            if (Schema::hasColumn('exams', 'negative_enabled')) {
                $table->dropColumn('negative_enabled');
            }
        });
    }
};
