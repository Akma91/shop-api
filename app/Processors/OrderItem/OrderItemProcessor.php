<?php

namespace App\Processors\OrderItem;

use App\Interfaces\OrderItemProcessorInterface;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class OrderItemProcessor implements OrderItemProcessorInterface 
{
    private array $processedOrderItems = [];
    private int $aggregatedPrice = 0;

    public function processOrderItems(array $orderData, array $orderItemsData)
    {
        $this->resetOrderItemsProcessing();

        foreach ($orderItemsData as $orderItemData) {
            $orderItem = new OrderItem($orderItemData);

            $orderItemSku = $orderItemData['sku'];
            $priceListName = $orderItemData['price_list_name'];
            $orderItemQuantity = $orderItemData['quantity'];

            $productWithRequestedPriceList = $this->getRelatedProductWithRequestedPriceList($orderItemSku, $priceListName);

            if (!$productWithRequestedPriceList) {
                throw new ModelNotFoundException("Product with sku: '" . $orderItemSku . "' for pricelist: '" . $priceListName . "' not found!");
            }

            $productPriceListPrice = $productWithRequestedPriceList->priceLists->first()->pivot->price;

            if(isset($orderData['user_id'])){
                $productContractListPrice = $this->getContractListPrice($orderData['user_id'], $orderItemSku);
            }

            if (isset($productContractListPrice)) {
                $appliedPrice = $productContractListPrice;
            } else {
                $appliedPrice = $productPriceListPrice;
            }

            //TODO implement item level price modifier in a manner of total order modifier

            $orderItem->applied_unit_price = $appliedPrice;

            $appliedPriceWithQuantity = $appliedPrice * $orderItemQuantity;

            $this->aggregatedPrice += $appliedPriceWithQuantity;

            $this->processedOrderItems[] = $orderItem;
        }

        return $this->processedOrderItems;
    }

    public function getAggregatedPrice(): int
    {
        return $this->aggregatedPrice;
    }

    private function getRelatedProductWithRequestedPriceList(string $orderItemSku, string $priceListName): ?Product
    {
        //TODO Put in repository
            return Product::where('sku', $orderItemSku)
            ->whereHas('priceLists', function ($query) use ($priceListName) {
                $query->where('name', $priceListName);
            })
            ->with(['priceLists' => function ($query) use ($priceListName) {
                $query->where('name', $priceListName);
            }])
            ->first();
    }

    private function getContractListPrice(string $userId, string $orderItemSku): ?int
    {
        return DB::table('products_contract_lists')
        ->leftJoin('contract_lists', 'products_contract_lists.contract_list_id', '=', 'contract_lists.id')
        ->where('user_id', $userId)
        ->where('sku', $orderItemSku)
        ->select('price')
        ->value('price');
    }

    private function resetOrderItemsProcessing(): void
    {
        $this->processedOrderItems = [];
        $this->aggregatedPrice = 0;
    }
}