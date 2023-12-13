<?php

namespace App\Repositories;

use App\Traits\Crud;
use App\Models\Customer;

class CustomerEloquentRepository
{
    use Crud;
    
    protected $model = Customer::class;
}