<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'approved',
        'archived',
        'attributes',
        'barcode',
        'brand',
        'brandId',
        'categoryName',
        'createDateTime',
        'description',
        'dimensionalWeight',
        'gender',
        'hasActiveCampaign',
        'images',
        'lastUpdateDate',
        'listPrice',
        'locked',
        'onSale',
        'pimCategoryId',
        'platformListingId',
        'productCode',
        'productContentId',
        'productMainId',
        'quantity',
        'returningAddressId',
        'salePrice',
        'shipmentAddressId',
        'stockCode',
        'stockUnitType',
        'supplierId',
        'title',
        'id',
        'vatRate',
        'deliveryDuration',
        'rejected',
        'blacklisted',
        'hasHtmlContent',
        'productUrl',
        'deliveryOptions'
    ];
}
