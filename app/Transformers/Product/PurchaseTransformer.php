<?php

namespace App\Transformers\Product;

use League\Fractal\TransformerAbstract;
use App\Transformers\Product\SupplierTransformer;
use App\Transformers\Master\PaymentMethodTransformer;
use App\Transformers\Product\PurchaseDetailTransformer;

class PurchaseTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['supplier', 'payment_method', 'details'];

    public function transform($purchase)
    {
        return [
            'id' => $purchase->id,
            'total_price' => (float) $purchase->total_price,
            'total_price_formatted' => 'Rp' . number_format((float) $purchase->total_price, 2),
            'pay_later' => (bool) $purchase->pay_later,
            'proof_of_payment' => $purchase->proof_of_payment,
            'status' => $purchase->status,
            'created_at' => $purchase->created_at->toDateTimeString()
        ];
    }

    public function includeSupplier($purchase)
    {
        $supplier = $purchase->supplier;
        if (!$supplier) return $this->primitive(null);

        return $this->item($supplier, new SupplierTransformer);
    }

    public function includePaymentMethod($purchase)
    {
        $paymentMethod = $purchase->paymentMethod;
        if (!$paymentMethod) return $this->primitive(null);

        return $this->item($paymentMethod, new PaymentMethodTransformer);
    }

    public function includeDetails($purchase)
    {
        $purchaseDetails = $purchase->details()->with([
                                    'product' => function($query) { $query->withoutGlobalScopes(); }
                                ])->get();

        return $this->collection($purchaseDetails, new PurchaseDetailTransformer);
    }
}