<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('judge_assignments', function (Blueprint $table) {
            $table->integer('technical_score')->nullable()->after('status');
            $table->text('remarks')->nullable()->after('technical_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('judge_assignments', function (Blueprint $table) {
            $table->dropColumn(['technical_score', 'remarks']);
        });
    }
};
