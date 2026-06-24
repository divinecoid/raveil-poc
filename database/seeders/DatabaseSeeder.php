<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed the Admin User for local testing
        User::factory()->create([
            'name' => 'Raveil Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Seed Settings
        Setting::updateOrCreate(['key' => 'whatsapp'], ['value' => '6281234567890']);
        Setting::updateOrCreate(['key' => 'instagram'], ['value' => 'raveilindustries']);
        Setting::updateOrCreate(['key' => 'hero_video'], ['value' => 'videos/hero-breaktherules.mp4']);
        Setting::updateOrCreate(['key' => 'visitor_count'], ['value' => '0']);
        Setting::updateOrCreate(['key' => 'whatsapp_click_count'], ['value' => '0']);

        // 3. Seed Brands
        $porsche = Brand::create(['name' => 'Porsche', 'slug' => 'porsche']);
        $ferrari = Brand::create(['name' => 'Ferrari', 'slug' => 'ferrari']);
        $lamborghini = Brand::create(['name' => 'Lamborghini', 'slug' => 'lamborghini']);
        $bmw = Brand::create(['name' => 'BMW M', 'slug' => 'bmw-m']);
        $audi = Brand::create(['name' => 'Audi Sport', 'slug' => 'audi-sport']);
        $aston = Brand::create(['name' => 'Aston Martin', 'slug' => 'aston-martin']);

        // 4. Seed Categories
        $aerodynamics = Category::create([
            'name' => 'Aerodynamics',
            'slug' => 'aerodynamics',
            'description' => 'Precision aero upgrades designed to maximize downforce and stability.',
        ]);

        $bodykits = Category::create([
            'name' => 'Body Kits',
            'slug' => 'body-kits',
            'description' => 'Complete visual and structural enhancement packages.',
        ]);

        $steering = Category::create([
            'name' => 'Custom Steering Wheels',
            'slug' => 'custom-steering-wheels',
            'description' => 'Ergonomic racing steering wheels crafted with forged carbon and alcantara.',
        ]);

        $exhaust = Category::create([
            'name' => 'Exhaust Systems',
            'slug' => 'exhaust-systems',
            'description' => 'High-performance exhausts featuring carbon-clad tips and valved controls.',
        ]);

        $accessories = Category::create([
            'name' => 'Carbon Accessories',
            'slug' => 'carbon-accessories',
            'description' => 'Subtle interior and exterior carbon accents to complete the aesthetic.',
        ]);

        // 5. Seed Products
        Product::create([
            'category_id' => $aerodynamics->id,
            'brand_id' => $porsche->id,
            'car_model' => '911 (992) GT3',
            'name' => 'Porsche 911 (992) GT3 Carbon Fiber Rear Wing',
            'slug' => 'porsche-992-gt3-carbon-wing',
            'description' => 'High-gloss motorsport-grade carbon fiber rear wing to enhance downforce and stability at speed.',
            'price' => 85000000.00,
            'image' => 'products/porsche-wing.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $aerodynamics->id,
            'brand_id' => $ferrari->id,
            'car_model' => 'Roma',
            'name' => 'Ferrari Roma Carbon Fiber Front Spoiler',
            'slug' => 'ferrari-roma-carbon-front-spoiler',
            'description' => 'A sleek, low-profile front splitter highlighting the aggressive stance of the Roma while directing airflow.',
            'price' => 65000000.00,
            'image' => 'products/ferrari-spoiler.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $bodykits->id,
            'brand_id' => $lamborghini->id,
            'car_model' => 'Huracan Sterrato',
            'name' => 'Lamborghini Huracan Sterrato Carbon Fender Flares',
            'slug' => 'lamborghini-sterrato-carbon-fenders',
            'description' => 'Widened carbon fiber arch extensions suitable for off-road aesthetics and wider tires.',
            'price' => 120000000.00,
            'image' => 'products/lambo-fenders.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $exhaust->id,
            'brand_id' => $bmw->id,
            'car_model' => 'M4 (G82)',
            'name' => 'BMW M4 (G82) Carbon Fiber Exhaust Tips',
            'slug' => 'bmw-g82-carbon-exhaust-tips',
            'description' => 'Signature quad exhaust tips finished in high-heat carbon sleeves over titanium pipelines.',
            'price' => 25000000.00,
            'image' => 'products/bmw-exhaust.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $steering->id,
            'brand_id' => null, // Raveil custom product
            'car_model' => 'Universal',
            'name' => 'Raveil Custom Forged Carbon Steering Wheel',
            'slug' => 'raveil-forged-carbon-steering-wheel',
            'description' => 'Individually built racing steering wheel featuring forged carbon structures, red 12 o\'clock marker, and premium Alcantara wrap.',
            'price' => 35000000.00,
            'image' => 'products/steering-wheel.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $accessories->id,
            'brand_id' => $aston->id,
            'car_model' => 'Vantage',
            'name' => 'Aston Martin Vantage Carbon Side Gills',
            'slug' => 'aston-martin-vantage-carbon-gills',
            'description' => 'Glossy carbon fiber replacement vents for the front fenders, highlighting the Vantage\'s classic profile.',
            'price' => 18000000.00,
            'image' => 'products/aston-gills.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $bodykits->id,
            'brand_id' => $audi->id,
            'car_model' => 'RS6 (C8)',
            'name' => 'Audi RS6 (C8) Carbon Fiber Rear Diffuser',
            'slug' => 'audi-rs6-c8-carbon-diffuser',
            'description' => 'Deeply channeled rear diffuser designed to integrate seamlessly with the OEM bumper, optimizing under-car vacuum.',
            'price' => 75000000.00,
            'image' => 'products/audi-diffuser.jpg',
            'is_active' => true,
        ]);

        $this->call(RaveilDataSeeder::class);
    }
}

