<?php

namespace App\Services;

use App\Services\AbstractPayment;

class CreditCardPayment extends AbstractPayment
{
    /**
     * Process the payment back to the customer
     * 
     * @return array
     */
    public function processPayment(array $payment): array
    {
        return [
            'invoiceNumber' => $payment['invoiceNumber'],
            'billingType'   => $payment['billingType'],
            'invoiceUrl'    => $payment['invoiceUrl'],
            'dueDate'       => $payment['dueDate'],
            'status'        => $payment['status'],
        ];
    }
}