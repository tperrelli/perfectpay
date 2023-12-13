<?php

namespace App\Http\Controllers\Api;

use App\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\PaymentRequest  $request
     * @return \Illuminate\Http\JsonResource
     */
    public function store(PaymentRequest $request): JsonResource
    {
        $payment = PaymentService::manager($request->input('billingType'));

        return new JsonResource(
            $payment->create($request->all())
        );
    }
}
