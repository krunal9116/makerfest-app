<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('youtube_link_1')->nullable()->after('status');
            $table->string('youtube_link_2')->nullable()->after('youtube_link_1');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['youtube_link_1', 'youtube_link_2']);
        });
    }
};
