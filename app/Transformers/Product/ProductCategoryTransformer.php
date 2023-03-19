<?php

namespace App\Transformers\Product;

use App\Models\Master\ProductCategory;
use League\Fractal\TransformerAbstract;

class ProductCategoryTransformer extends TransformerAbstract
{
    public function transform(ProductCategory $productCategory)
    {
        return [
            'id' => $productCategory->id,
            'code' => $productCategory->code,
            'name' => $productCategory->name
        ];
    }
}