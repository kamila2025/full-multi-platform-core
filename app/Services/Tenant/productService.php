<?php

namespace App\Services\Tenant;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class productService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * 創建商品
     */
    public function createProduct(array $attributes): Product
    {
      try {
          DB::beginTransaction();

          $product = $this->productRepository->create([
            'name'                  => $attributes['name'],
            'description'           => $attributes['description'] ?? null,
            'image_url'             => $attributes['image_url'] ?? null,
            'inventory_management'  => $attributes['inventory_management'],
            'status'                => $attributes['status'],
          ]);

          if (isset($attributes['categories'])) {
            $product->categories()->sync($attributes['categories']);
          }

          DB::commit();

          return $product;
      } catch (\Throwable $e) {
          DB::rollBack();

          throw $e;
      }
    }

    /**
     * 更新商品
     */
    public function updateProduct($id, array $attributes): Product
    {
        try {
            DB::beginTransaction();

            $product = $this->productRepository->findOrFail($id);

            $updateData = [
                'name'                  => $attributes['name'],
                'description'           => $attributes['description'] ?? null,
                'image_url'             => $attributes['image_url'] ?? null,
                'inventory_management'  => $attributes['inventory_management'],
                'status'                => $attributes['status'],
            ];

            $product->update($updateData);

            if (isset($attributes['categories'])) {
              $product->categories()->sync($attributes['categories']);
            }

            DB::commit();

            return $product;
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * 刪除商品
     */
    public function deleteProduct($id): void
    {
        try {
            DB::beginTransaction();

            $product = $this->productRepository->findOrFail($id);

            $product->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
