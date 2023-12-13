<?php

namespace App\Services;

use App\Repositories\CustomerEloquentRepository;
use App\Traits\Asaas;

class CustomerService
{
    use Asaas;

    /**
     * @var CustomerEloquentRepository $eloquentRepository
     */
    private $eloquentRepository;

    /**
     * Class constructor
     * 
     * @param CustomerEloquentRepository $eloquentRepository
     */
    public function __construct(CustomerEloquentRepository $eloquentRepository)
    {
        $this->eloquentRepository = $eloquentRepository;
    }

    /**
     * Creates a new payment
     * 
     * @param array $data
     */
    public function create(array $data)
    {
        if (!$customer = $this->eloquentRepository->findByField('cpfCnpj', $data['cpfCnpj'])) {
            $data['cpfCnpj'] = str_replace(['.', '-', '/' ], [''], $data['cpfCnpj']);
            
            $response = $this->post('customers', $data);

            $data['provider_id'] = $response['id'];
            $customer = $this->eloquentRepository->create($data);
        }

        return $customer;
    }
}