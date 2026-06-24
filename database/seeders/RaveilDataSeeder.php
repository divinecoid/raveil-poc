<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RaveilDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Categories
        $categories = collect(['Exterior Carbon', 'Interior Carbon', 'Engine Bay Carbon', 'Carbon Steering Wheels', 'Custom Aero Kits'])->map(function ($name) {
            return \App\Models\Category::firstOrCreate(['slug' => \Illuminate\Support\Str::slug($name)], [
                'name' => $name,
                'description' => 'Produk untuk kategori ' . $name,
            ]);
        });

        // 2. Brands
        $brands = collect(['Toyota', 'Honda', 'Mitsubishi', 'BMW', 'Mercedes-Benz', 'Porsche'])->map(function ($name) {
            return \App\Models\Brand::firstOrCreate(['slug' => \Illuminate\Support\Str::slug($name)], [
                'name' => $name,
            ]);
        });

        // 3. Products
        $vehicleModelsList = [
            'Toyota' => ['GR Yaris', 'Supra MK5', 'GR86', 'Innova Zenix Hybrid'],
            'Honda' => ['Civic Type R', 'HR-V RS', 'CR-V Turbo'],
            'Mitsubishi' => ['Lancer Evo X', 'Pajero Sport Dakar', 'Xpander Cross'],
            'BMW' => ['M3 G80', 'M4 G82', '330i M Sport'],
            'Mercedes-Benz' => ['C63 AMG', 'A45 S AMG', 'G63 AMG'],
            'Porsche' => ['911 Carrera', '718 Cayman GT4', 'Taycan Turbo']
        ];
        $partNames = ['Front Lip', 'Rear Spoiler', 'Side Skirts', 'Diffuser', 'Hood', 'Mirror Covers', 'Steering Wheel', 'Interior Trim'];
        $products = collect();
        foreach (range(1, 15) as $i) {
            $cat = $categories->random();
            $brand = $brands->random();
            $models = $vehicleModelsList[$brand->name] ?? ['Universal'];
            $carModel = fake()->randomElement($models);
            
            $price = fake()->numberBetween(10, 500) * 10000;
            $stock = fake()->numberBetween(0, 50);
            $minStock = fake()->numberBetween(0, 10);
            
            $product = \App\Models\Product::firstOrCreate([
                'slug' => 'produk-dummy-' . $i
            ], [
                'category_id' => $cat->id,
                'brand_id' => $brand->id,
                'car_model' => $carModel,
                'name' => $brand->name . ' ' . $carModel . ' Carbon Fiber ' . fake()->randomElement($partNames),
                'description' => fake()->sentence(),
                'price' => $price,
                'stock_quantity' => $stock,
                'minimum_stock' => $minStock,
                'is_active' => true,
            ]);
            $products->push($product);
        }

        // 4. Customers
        $chindoNames = ['Kevin Wijaya', 'Michael Salim', 'Steven Kusuma', 'William Susanto', 'Jefri Lie', 'Evelyn Hartono', 'Jessica Tanoe', 'Richard Halim'];
        $customers = collect();
        foreach (range(1, 5) as $i) {
            $name = $chindoNames[$i] ?? fake()->name();
            $customers->push(\App\Models\Customer::firstOrCreate(['email' => strtolower(str_replace(' ', '', $name)) . '@gmail.com'], [
                'name' => $name,
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
            ]));
        }

        // 5. Vehicles
        $vehicleModels = [
            'Toyota' => ['GR Yaris', 'Supra MK5', 'GR86', 'Innova Zenix Hybrid'],
            'Honda' => ['Civic Type R', 'HR-V RS', 'CR-V Turbo'],
            'Mitsubishi' => ['Lancer Evo X', 'Pajero Sport Dakar', 'Xpander Cross'],
            'BMW' => ['M3 G80', 'M4 G82', '330i M Sport'],
            'Mercedes' => ['C63 AMG', 'A45 S AMG', 'G63 AMG']
        ];
        $vehicles = collect();
        foreach ($customers as $customer) {
            foreach (range(1, fake()->numberBetween(1, 3)) as $i) {
                $brand = fake()->randomElement(array_keys($vehicleModels));
                $model = fake()->randomElement($vehicleModels[$brand]);
                
                $vehicles->push(\App\Models\Vehicle::create([
                    'customer_id' => $customer->id,
                    'brand' => $brand,
                    'model' => $model,
                    'year' => fake()->numberBetween(2010, 2024),
                    'license_plate' => 'B ' . fake()->numberBetween(1000, 9999) . ' ' . strtoupper(fake()->lexify('???')),
                ]));
            }
        }

        // 6. Sales Orders
        foreach (range(1, 5) as $i) {
            $vehicle = $vehicles->random();
            $so = \App\Models\SalesOrder::create([
                'order_number' => \App\Models\SalesOrder::generateOrderNumber(),
                'customer_id' => $vehicle->customer_id,
                'vehicle_id' => $vehicle->id,
                'status' => fake()->randomElement(['Pending', 'Processing']),
                'total_amount' => 0,
                'notes' => fake()->sentence(),
            ]);

            $total = 0;
            // Sales Order Items
            foreach (range(1, fake()->numberBetween(1, 4)) as $j) {
                $product = $products->random();
                $qty = fake()->numberBetween(1, 4);
                $subtotal = $product->price * $qty;
                $total += $subtotal;

                \App\Models\SalesOrderItem::create([
                    'sales_order_id' => $so->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);
            }
            // Create Sales Order Services
            $services = [
                ['name' => 'Instalasi Custom Carbon', 'price' => 1500000],
                ['name' => 'Clear Coat & Polish', 'price' => 800000],
                ['name' => 'Fitting Bodykit', 'price' => 2000000],
                ['name' => 'Molding Cetakan', 'price' => 3000000],
            ];
            foreach (range(1, fake()->numberBetween(0, 2)) as $j) {
                $srv = fake()->randomElement($services);
                $qty = 1;
                $subtotal = $srv['price'] * $qty;
                \App\Models\SalesOrderService::create([
                    'sales_order_id' => $so->id,
                    'service_name' => $srv['name'],
                    'quantity' => $qty,
                    'unit_price' => $srv['price'],
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $so->update(['total_amount' => $total]);

            // 7. Projects
            $projectTypes = ['Full Carbon Kit', 'Custom Carbon Interior', 'Repair & Recoat Carbon', 'Aero Package Upgrade', 'Steering Wheel Laminasi'];
            $projectDescs = [
                'Client minta dibikinin full carbon buat eksteriornya, fittingnya harus bener-bener rapi.',
                'Repair bagian lips yang baret mentok polisi tidur, sekalian dilapis clear coat ulang.',
                'Upgrade setir pakai forged carbon, alcantara, dan pasang red marker di jam 12.',
                'Ganti kap mesin ori dengan carbon fiber yang lebih enteng buat track day.',
                'Lapis ulang panel interior yang kusam pakai twill carbon fiber biar lebih sporty.'
            ];
            $project = \App\Models\Project::create([
                'name' => fake()->randomElement($projectTypes) . ' ' . $vehicle->brand . ' ' . $vehicle->model,
                'description' => fake()->randomElement($projectDescs),
                'sales_order_id' => $so->id,
                'customer_id' => $vehicle->customer_id,
                'status' => 'in_progress',
                'start_date' => now(),
                'due_date' => now()->addDays(7),
            ]);

            // 8. Tasks
            $taskTitles = [
                'Molding dan cetak custom lips bumper',
                'Pemasangan spoiler belakang carbon',
                'Clear coat ulang part carbon yang kusam',
                'Fitting hood/kap mesin carbon',
                'Instalasi spion carbon replacement',
                'Pemasangan diffuser belakang carbon',
                'Poles part carbon fiber',
                'Laminasi carbon setir interior',
                'Pemasangan side skirt lip carbon',
                'Setting dan fitting bodykit depan',
            ];
            $taskStatuses = ['todo', 'in_progress', 'review', 'done'];
            foreach (range(1, fake()->numberBetween(3, 6)) as $k) {
                \App\Models\Task::create([
                    'project_id' => $project->id,
                    'title' => fake()->randomElement($taskTitles),
                    'description' => fake()->sentence(),
                    'status' => fake()->randomElement($taskStatuses),
                    'order' => $k,
                ]);
            }
        }
    }
}
