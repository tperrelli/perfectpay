<?php

namespace App\Traits;

trait Crud
{
    private function model()
    {
        return app()->make($this->model);
    }

    public function index()
    {
        return $this->model()::all();
    }

    public function create(array $data)
    {
        return $this->model()::create($data);
    }

    public function find(int $id)
    {
        return $this->model()::findOrFail($id);
    }

    public function findByField(string $field, string $value)
    {
        return $this->model()::firstWhere($field, $value);
    }

    public function update(array $data, int $id)
    {
        $resource = $this->model()::findOrFail($id);

        return $resource->update($data);
    }

    public function delete(int $id)
    {
        $resource = $this->model()::findOrFail($id);

        return $resource->delete();
    }
}