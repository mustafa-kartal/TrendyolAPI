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
        Schema::create('products', function (Blueprint $table) {
            $table->string("id");
            $table->boolean('approved')->default(true);
            $table->boolean('archived')->default(false);
            $table->json('attributes')->nullable();
            $table->string('barcode')->nullable();
            $table->string('brand')->nullable();
            $table->foreignId('brandId')->nullable();
            $table->string('categoryName')->nullable();
            $table->string('createDateTime')->nullable();
            $table->text('description')->nullable();
            $table->decimal('dimensionalWeight', 8, 2)->default(1);
            $table->string('gender')->nullable();
            $table->boolean('hasActiveCampaign')->default(false);
            $table->json('images')->nullable();
            $table->string('lastPriceChangeDate')->nullable();
            $table->string('lastStockChangeDate')->nullable();
            $table->string('lastUpdateDate')->nullable();
            $table->decimal('listPrice', 10, 2)->default(0);
            $table->boolean('locked')->default(false);
            $table->boolean('onSale')->default(false);
            $table->foreignId('pimCategoryId')->nullable();
            $table->string('platformListingId')->nullable();
            $table->string('productCode')->nullable();
            $table->foreignId('productContentId')->nullable();
            $table->string('productMainId')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->foreignId('returningAddressId')->nullable();
            $table->decimal('salePrice', 10, 2)->default(0);
            $table->foreignId('shipmentAddressId')->nullable();
            $table->string('stockCode')->nullable();
            $table->string('stockUnitType')->default('Adet');
            $table->foreignId('supplierId')->nullable();
            $table->string('title')->nullable();
            $table->unsignedTinyInteger('vatRate')->default(0);
            $table->boolean('rejected')->default(false);
            $table->boolean('blacklisted')->default(false);
            $table->boolean('hasHtmlContent')->default(false);
            $table->text('productUrl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
