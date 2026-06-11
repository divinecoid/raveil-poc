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
        Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
            ['key' => 'hero_video'],
            ['value' => 'videos/hero-breaktherules.mp4', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Illuminate\Support\Facades\DB::table('settings')->where('key', 'hero_video')->delete();
    }
};
