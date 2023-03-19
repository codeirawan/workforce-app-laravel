<?php

namespace App\Transformers\Product;

use League\Fractal\TransformerAbstract;
use App\Transformers\Product\ProductTransformer;

class PurchaseDetailTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['product'];

    public function transform($purchaseDetail)
    {
        $quantity = json_decode($purchaseDetail->quantity);

        return [
            'id' => $purchaseDetail->id,
            'price' => (float) $purchaseDetail->price,
            'quantity' => [
                'ordered' => (int) $quantity->ordered,
                'validated' => (int) $quantity->validated,
                'storaged' => (int) $quantity->storaged,
            ],
            'note' => $purchaseDetail->note,
            'is_guaranteed' => (bool) $purchaseDetail->is_guaranteed,
            'warranty_expiration_date' => optional($purchaseDetail->warranty_expiration_date)->format('d-m-Y'),
            'warranty_terms_and_conditions' => $purchaseDetail->warranty_terms_and_conditions,
        ];
    }

    public function includeProduct($purchaseDetail)
    {
        $product = $purchaseDetail->product;
        if (!$product) return $this->primitive(null);

        return $this->item($product, new ProductTransformer);
    }
}