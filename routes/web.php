<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    $products = Product::where('is_active', true)->latest()->get();
    
    // Fetch settings and convert to array like ['whatsapp' => '...', 'instagram' => '...']
    $settings = Setting::pluck('value', 'key')->toArray();

    // Default fallbacks if not set
    $whatsapp = $settings['whatsapp'] ?? '6285705279999';
    $instagram = $settings['instagram'] ?? 'raveil.industries';

    $heroVideo = $settings['hero_video'] ?? 'videos/hero-breaktherules.mp4';
    if (Storage::disk('public')->exists($heroVideo)) {
        $heroVideoUrl = Storage::url($heroVideo);
    } else {
        $heroVideoUrl = asset($heroVideo);
    }

    // Increment visitor count if not visited in current session
    if (!session()->has('visited')) {
        session()->put('visited', true);
        $visitorSetting = Setting::firstOrCreate(['key' => 'visitor_count'], ['value' => '0']);
        $visitorSetting->value = (int)$visitorSetting->value + 1;
        $visitorSetting->save();
    }

    return view('catalog', compact('products', 'whatsapp', 'instagram', 'heroVideoUrl'));
});

Route::post('/track-whatsapp-click', function () {
    $clickSetting = Setting::firstOrCreate(['key' => 'whatsapp_click_count'], ['value' => '0']);
    $clickSetting->value = (int)$clickSetting->value + 1;
    $clickSetting->save();

    return response()->json(['success' => true]);
});

Route::post('/track-product-click', function (\Illuminate\Http\Request $request) {
    $productId = $request->input('product_id');
    if ($productId && Product::where('id', $productId)->exists()) {
        Product::where('id', $productId)->increment('clicks');
        \App\Models\ProductClick::create(['product_id' => $productId]);
    }
    return response()->json(['success' => true]);
});

// Helper routes for cPanel/shared hosting deployments
Route::get('/setup-storage', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return 'Storage linked successfully! Your images should now appear. <a href="/">Go back</a>';
});

Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return 'Cache cleared successfully! <a href="/admin">Try Admin Login</a>';
});
