<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'registration_start')) {
                $table->renameColumn('registration_start', 'registration_open');
            }
            if (!Schema::hasColumn('events', 'registration_close')) {
                $table->date('registration_close')->nullable();
            }
            if (!Schema::hasColumn('events', 'revision_deadline')) {
                $table->date('revision_deadline')->nullable();
            }
            if (!Schema::hasColumn('events', 'withdrawal_cutoff')) {
                $table->date('withdrawal_cutoff')->nullable();
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'registration_for')) {
                $table->string('registration_for')->default('myself')->after('registration_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('registration_open', 'registration_start');
            $table->dropColumn(['registration_close', 'revision_deadline', 'withdrawal_cutoff']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('registration_for');
        });
    }
};
