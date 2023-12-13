<?php

namespace App\Services;

class PaymentService
{
    public static function manager($billingType)
    {
        $method = null;

        switch ($billingType) {
            case 'BOLETO':
                $method = app()->make(BilletPayment::class);
                break;
            case 'CREDIT_CARD':
                $method = app()->make(CreditCardPayment::class);
                break;
            case 'PIX':
                $method = app()->make(PixPayment::class);
                break;
            default: 
                throw new \Exception('Payment method does not exists.');
        }

        return $method;
    }
}