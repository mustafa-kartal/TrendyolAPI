<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchTrendyolProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $supplierId;
    private $page;

    public function __construct($supplierId, $page = 0)
    {
        $this->supplierId = $supplierId;
        $this->page = $page;
    }

    public function handle()
    {
        $apiKey = env('TRENDYOL_API_KEY');
        $apiSecret = env('TRENDYOL_SECRET_KEY');

        $response = Http::withBasicAuth($apiKey, $apiSecret)
            ->withHeaders([
                'User-Agent' => "{$this->supplierId} - SelfIntegration",
            ])
            ->get(env("TRENDYOL_API_URL")."/sapigw/suppliers/{$this->supplierId}/products", [
                'page' => $this->page,
                'size' => 20,
            ]);

        if ($response->successful()) {
            $products = $response->json()['content'];
            foreach ($products as $product) {
                Product::updateOrCreate(
                    ['id' => $product['id']],
                    [
                        'productMainId' => $product['productMainId'] ?? 0,
                        'title' => $product['title'] ?? NULL,
                        'salePrice' => $product['salePrice'] ?? 0,
                        'quantity' => $product['quantity'] ?? 0,
                        'description' => $product['description'] ?? NULL,
                        'brand' => $product['brand'] ?? NULL,
                        'categoryName' => $product['categoryName'] ?? NULL,
                        'productUrl' => $product['productUrl'] ?? NULL,
                        'approved' => $product['approved'] ?? false,
                        'archived' => $product['archived'] ?? false,
                        'attributes' => json_encode($product['attributes']),
                        'barcode' => $product['barcode'] ?? NULL,
                        'brandId' => $product['brandId'] ?? 0,
                        'createDateTime' => $product['createDateTime'] ?? 0,
                        'dimensionalWeight' => $product['dimensionalWeight'] ?? 0,
                        'gender' => $product['gender'] ?? 0,
                        'hasActiveCampaign' => $product['hasActiveCampaign'],
                        'images' => json_encode($product['images']), 
                        'lastPriceChangeDate' => $product['lastPriceChangeDate'] ?? 0,
                        'lastStockChangeDate' => $product['lastStockChangeDate'] ?? 0,
                        'lastUpdateDate' => $product['lastUpdateDate'] ?? 0,
                        'listPrice' => $product['listPrice'] ?? 0,
                        'locked' => $product['locked'] ?? false,
                        'onSale' => $product['onSale'] ?? false,
                        'pimCategoryId' => $product['pimCategoryId'] ?? 0,
                        'platformListingId' => $product['platformListingId'] ?? 0,
                        'productCode' => $product['productCode'] ?? NULL,
                        'productContentId' => $product['productContentId'] ?? 0,
                        'returningAddressId' => $product['returningAddressId'] ?? 0,
                        'shipmentAddressId' => $product['shipmentAddressId'] ?? 0,
                        'stockCode' => $product['stockCode'] ?? NULL,
                        'stockUnitType' => $product['stockUnitType'] ?? NULL,
                        'supplierId' => $product['supplierId'] ?? 0,
                        'vatRate' => $product['vatRate'] ?? 0,
                        'version' => $product['version'] ?? 0,
                        'rejected' => $product['rejected']  ?? false,
                        'rejectReasonDetails' => $product['rejectReasonDetails'] ? json_encode($product['rejectReasonDetails']) : NULL,
                        'blacklisted' => $product['blacklisted']  ?? false,
                        'blacklistReason' => $product['blacklistReason'] ?? NULL,
                        'hasHtmlContent' => $product['hasHtmlContent'] ?? false,
                    ]
                );
            }

            if ($this->page < 463) {
                dispatch(new FetchTrendyolProductsJob($this->supplierId, $this->page + 1));
            }

        }else {
            \Log::error("Trendyol API isteği başarısız oldu: " . $response->body());
        }

        
    }
}
