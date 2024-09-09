<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Jobs\FetchTrendyolProductsJob;

class ProductController extends Controller
{
    public function index()
    {
        $totalPages = 463;
        $supplierId = env("TRENDYOL_SUPPLIER_ID");
        for ($page = 0; $page < $totalPages; $page++) {
            FetchTrendyolProductsJob::dispatch($supplierId, $page);
        }

        Log::info("Tüm ürünler kuyruğa eklendi.");

        return back()->with("message","Ürünler çekilmeye başlandı.");
    }


}
