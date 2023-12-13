<?php

namespace App\Http\Controllers\Api;

use App\Services\CustomerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerController extends Controller
{
    /**
     * @var CustomerService $customerService
     */
    protected $customerService;

    /**
     * Class constructor
     * 
     * @param CustomerService $customerService
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\CustomerRequest  $request
     * @return \Illuminate\Http\JsonResource
     */
    public function store(CustomerRequest $request)
    {
        return new JsonResource(
            $this->customerService->create($request->all())
        );
    }
}
