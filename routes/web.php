<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    $products = Product::where('is_active', true)->with('brand', 'category')->latest()->get();
    
    // Extract unique brands that have active products
    $brands = $products->pluck('brand')->filter()->unique('id')->values();
    
    // Check if there are active products with no brand (Raveil Custom / Universal)
    $hasUniversal = $products->contains(function ($product) {
        return is_null($product->brand_id);
    });

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

    // Fetch active studio photos sorted by sort_order
    $studioPhotos = \App\Models\StudioPhoto::where('is_active', true)->orderBy('sort_order')->get();

    // Increment visitor count if not visited in current session
    if (!session()->has('visited')) {
        session()->put('visited', true);
        $visitorSetting = Setting::firstOrCreate(['key' => 'visitor_count'], ['value' => '0']);
        $visitorSetting->value = (int)$visitorSetting->value + 1;
        $visitorSetting->save();
    }

    return view('catalog', compact('products', 'brands', 'hasUniversal', 'studioPhotos', 'whatsapp', 'instagram', 'heroVideoUrl'));
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

Route::get('/admin/finance/pdf', function (\Illuminate\Http\Request $request) {
    // We assume the user is authenticated if they reached here from admin panel
    $start = $request->input('start', now()->startOfMonth()->toDateString());
    $end = $request->input('end', now()->endOfMonth()->toDateString());

    $invoices = \App\Models\Invoice::where('status', 'Paid')
        ->whereBetween('issue_date', [$start, $end])
        ->get();
    
    $expenses = \App\Models\Expense::where('status', 'Paid')
        ->whereBetween('date', [$start, $end])
        ->get();

    $totalIncome = $invoices->sum('total');
    $totalExpense = $expenses->sum('amount');
    $netProfit = $totalIncome - $totalExpense;

    $receivables = \App\Models\Invoice::where('status', 'Unpaid')
        ->whereBetween('issue_date', [$start, $end])
        ->get();

    $payables = \App\Models\Expense::where('status', 'Unpaid')
        ->whereBetween('date', [$start, $end])
        ->get();

    $totalReceivable = $receivables->sum('total');
    $totalPayable = $payables->sum('amount');

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.finance_report', compact(
        'start', 'end', 'invoices', 'expenses', 'totalIncome', 'totalExpense', 'netProfit',
        'receivables', 'payables', 'totalReceivable', 'totalPayable'
    ));

    return $pdf->download('Finance_Report_' . $start . '_to_' . $end . '.pdf');
})->name('finance.pdf');
