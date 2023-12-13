<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Validation\ValidationException;

trait Asaas 
{
    /**
     * @return string
     */
    private function url(): string
    {
        return config("asaas.url");
    }

    /**
     * @return string
     */
    private function getKey(): string
    {
        return config("asaas.key");
    }

    /**
     * @return array
     */
    private function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'access_token' => $this->getKey()
        ];
    }

    private function processResponse($response)
    {
        if ($response->failed()) {

            $errors = [];
            collect($response->json()['errors'])->each(function($error) use(&$errors) {
                if (array_key_exists($error['code'], $errors)) {
                    array_push($errors[$error['code']], $error['description']);
                } else {
                    $errors[$error['code']] = [$error['description']];
                }
            });
        
            $error = ValidationException::withMessages($errors);

            throw $error;
        }

        return $response->json();
    }

    /**
     * @return \Illuminate\Http\Client\PendingRequest
     */
    private function request(): PendingRequest
    {
        return Http::withHeaders($this->getHeaders());
    }

    public function post(string $uri, array $data = [])
    {
        return $this->processResponse($this->request()->post($this->url() . $uri, $data));
    }

    public function get(string $uri)
    {
        return $this->processResponse($this->request()->get($this->url() . $uri));
    }
}