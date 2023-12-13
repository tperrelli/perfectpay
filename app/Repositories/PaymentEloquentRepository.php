<?php

namespace App\Repositories;

use App\Traits\Crud;
use App\Models\Payment;

class PaymentEloquentRepository
{
    use Crud;

    protected $model = Payment::class;
}