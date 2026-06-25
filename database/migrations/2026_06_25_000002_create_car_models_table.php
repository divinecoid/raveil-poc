<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->default(1)->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        // Seed some default models for existing brands
        $brands = [
            'Porsche' => ['911 (992) GT3'],
            'Ferrari' => ['Roma'],
            'Lamborghini' => ['Huracan Sterrato'],
            'BMW' => ['M4 (G82)'],
            'Aston Martin' => ['Vantage'],
            'Audi' => ['RS6 (C8)'],
        ];

        foreach ($brands as $brandName => $models) {
            $brand = DB::table('brands')->where('name', $brandName)->first();
            if ($brand) {
                foreach ($models as $modelName) {
                    DB::table('car_models')->insert([
                        'company_id' => 1,
                        'brand_id' => $brand->id,
                        'name' => $modelName,
                        'slug' => Str::slug($modelName) . '-' . uniqid(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
