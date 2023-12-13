<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'customer'          => 'required',
            'billingType'       => 'required|in:BOLETO,CREDIT_CARD,PIX',
            'value'             => 'required|min:1',
            'dueDate'           => 'required',
            'description'       => 'nullable|max:500',
            'externalReference' => 'nullable|max:50',
        ];

        $data = $this->post();
        
        if ($data['billingType'] === 'CREDIT_CARD') {
            $rules['creditCard.holderName'] = 'required|max:150';
            $rules['creditCard.number'] = 'required|max:16';
            $rules['creditCard.expiryMonth'] = 'required|max:2';
            $rules['creditCard.expiryYear'] = 'required|max:4';
            $rules['creditCard.ccv'] = 'required|max:3';

            $rules['creditCardHolderInfo.name'] = 'required|max:150';
            $rules['creditCardHolderInfo.postalCode'] = 'required|max:15';
            $rules['creditCardHolderInfo.email'] = 'required|max:150';
            $rules['creditCardHolderInfo.cpfCnpj'] = 'required|max:20';
            $rules['creditCardHolderInfo.addressNumber'] = 'required|max:3';
            $rules['creditCardHolderInfo.phone'] = 'required|max:20';
        }
        
        return $rules;
    }
}
