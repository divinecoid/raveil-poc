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
            ['key' => 'visitor_count'],
            ['value' => '0', 'created_at' => now(), 'updated_at' => now()]
        );
        Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
            ['key' => 'whatsapp_click_count'],
            ['value' => '0', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Illuminate\Support\Facades\DB::table('settings')->whereIn('key', ['visitor_count', 'whatsapp_click_count'])->delete();
    }
};
