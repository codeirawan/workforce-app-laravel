<?php

namespace App\Transformers\Product;

use League\Fractal\TransformerAbstract;

class SupplierTransformer extends TransformerAbstract
{
    public function transform($supplier)
    {
        return [
            'id' => $supplier->id,
            'name' => $supplier->name,
            'telephone' => $supplier->telephone,
            'address' => $supplier->address,
            'type' => $supplier->type,
            'npwp' => $supplier->npwp
        ];
    }
}