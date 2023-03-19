<?php

namespace App\Transformers\Master;

use League\Fractal\TransformerAbstract;

class PaymentMethodTransformer extends TransformerAbstract
{
    public function transform($paymentMethod)
    {
        return [
            'id' => $paymentMethod->id,
            'name' => $paymentMethod->name
        ];
    }
}