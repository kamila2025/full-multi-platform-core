<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends Repository
{
    protected $fieldSearchable = [
      'name' => 'like',
      'categories.id' => 'in',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Product::class;
    }

    public function getProducts($attributes = [])
    {
        return $this->model
            ->with('categories')
            ->latest()
            ->get();
    }
}
