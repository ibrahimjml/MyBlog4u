<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('active_sessions', function (Blueprint $table) {
            $table->dropUnique('active_sessions_browser_token_unique');
        });

        Schema::table('active_sessions', function (Blueprint $table) {
            $table->unique(
                ['user_id', 'browser_token'],
                'active_sessions_user_browser_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('active_sessions', function (Blueprint $table) {
            $table->dropUnique('active_sessions_user_browser_unique');
        });

        Schema::table('active_sessions', function (Blueprint $table) {
            $table->unique('browser_token', 'active_sessions_browser_token_unique');
        });
    }
};
