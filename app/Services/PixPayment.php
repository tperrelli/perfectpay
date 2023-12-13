<?php

namespace App\Services;

class PixPayment extends AbstractPayment
{
    /**
     * Process the payment back to the customer
     * 
     * @return array
     */
    public function processPayment(array $payment): array
    {
        $qrCode = $this->retrieveQRCode($payment['id']);
        
        return [
            'invoiceNumber' => $payment['invoiceNumber'],
            'billingType'   => $payment['billingType'],
            'invoiceUrl'    => $payment['invoiceUrl'],
            'dueDate'       => $payment['dueDate'],
            'qrCode'        => $qrCode['encodedImage'],
            'status'        => $payment['status'],
        ];
    }

    private function retrieveQRCode(string $id): array
    {
        return $this->get("payments/$id/pixQrCode");
    }
}