<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $keywordType = $request->input('keyword_type');
        $keyword = $request->input('keyword');

        $query = Product::query();

        if (!empty($keywordType) && !empty($keyword)) {
            switch ($keywordType) {
                case 'title':
                    $query->where('title', 'like', '%' . $keyword . '%');
                    break;
                case 'productMainId':
                    $query->where('productMainId', 'like', '%' . $keyword . '%');
                    break;
                case 'productCode':
                    $query->where('productCode', 'like', '%' . $keyword . '%');
                    break;
            }
        }

        $products = $query->paginate(10);

        return view("welcome",["products" => $products]);
    }


    public function updatePriceAndInventory(Request $request)
    {
        $barcode = $request->input('barcode');
        $quantity = $request->input('quantity');
        $salePrice = $request->input('salePrice');
        $listPrice = $request->input('listPrice');

        $data = [
            'items' => [
                [
                    'barcode' => $barcode,
                    'quantity' => $quantity,
                    'salePrice' => $salePrice,
                    'listPrice' => $listPrice,
                ]
            ]
        ];

        $response = Http::withBasicAuth(env("TRENDYOL_API_KEY"), env("TRENDYOL_SECRET_KEY"))
        ->withHeaders([
            'User-Agent' => env("TRENDYOL_SUPPLIER_ID")." - SelfIntegration",
        ])
        ->post(env("TRENDYOL_API_URL").'/sapigw/suppliers/'.env("TRENDYOL_SUPPLIER_ID").'/products/price-and-inventory', $data);

        $batchRequestId = $response->json("batchRequestId");
        session()->put("batchRequestId",$batchRequestId);
        return back()->with("message","Your price and stock update request has been processed.");
    }

    public function checkBatchStatus($batchRequestId)
    {
        $response = Http::withBasicAuth(env("TRENDYOL_API_KEY"), env("TRENDYOL_SECRET_KEY"))
        ->withHeaders([
            'User-Agent' => env("TRENDYOL_SUPPLIER_ID")." - SelfIntegration",
        ])
        ->get(env("TRENDYOL_API_URL")."/sapigw/suppliers/".env("TRENDYOL_SUPPLIER_ID")."/products/batch-requests/".$batchRequestId);

        $items = $response->json("items");
        
        if (isset($items[0]['status']) && $items[0]['status'] === 'SUCCESS') {
            Product::where("barcode",$items[0]["requestItem"]['barcode'])->update([
                "quantity" => $items[0]["requestItem"]['quantity'],
                "salePrice" => $items[0]["requestItem"]['salePrice'],
                "listPrice" => $items[0]["requestItem"]['listPrice'],
            ]);
            session()->forget("batchRequestId");
            return back()->with('message','The stock and price update of the product with barcode '.$items[0]["requestItem"]['barcode'].' was successfully realized.');
            
        } else {
            session()->forget("batchRequestId");
            return back()->with('message','The stock and price update of the product with barcode '.$items[0]["requestItem"]['barcode'].' failed! --- Failure Reasons: '.$items[0]["failureReasons"]);
        }
    }

}
