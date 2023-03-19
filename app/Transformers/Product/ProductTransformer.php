<?php

namespace App\Transformers\Product;

use App\Models\Product\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'photo' => $product->photo,
            'sku_per_unit' => (bool) $product->sku_per_unit,
            'mac_address' => (bool) $product->mac_address
        ];
    }
}