<?php

namespace App\Services;

use App\Repositories\CustomerEloquentRepository;
use App\Traits\Asaas;
use App\Repositories\PaymentEloquentRepository;

abstract class AbstractPayment
{
    use Asaas;

    /**
     * @var PaymentEloquentRepository $eloquentRepository
     */
    private $eloquentRepository;
    
    /**
     * @var CustomerEloquentRepository $customerEloquentRepository
     */
    private $customerEloquentRepository;

    /**
     * Class constructor
     * 
     * @param PaymentEloquentRepository $eloquentRepository
     * @param CustomerEloquentRepository $customerEloquentRepository
     */
    public function __construct(
        PaymentEloquentRepository $eloquentRepository,
        CustomerEloquentRepository $customerEloquentRepository
        )
    {
        $this->eloquentRepository = $eloquentRepository;
        $this->customerEloquentRepository = $customerEloquentRepository;
    }

    /**
     * Process the payment back to the customer
     * 
     * @return array
     */
    abstract function processPayment(array $payment): array;

    /**
     * Creates a new payment
     * 
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $response = $this->post('payments', $data);
        $customer = $this->customerEloquentRepository->findByField('provider_id', $data['customer']);
        
        $data['provider_id'] = $response['id'];
        $data['customer_id'] = $customer->id;
        $data['invoiceNumber'] = $response['invoiceNumber'];
        $data['status'] = $response['status'];

        $this->eloquentRepository->create($data);

        return $this->processPayment($response);
    }
}