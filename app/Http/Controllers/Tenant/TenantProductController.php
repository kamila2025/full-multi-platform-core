<?php

namespace App\Http\Controllers\Tenant;

use App\Enums\Tenant\PermissionNameEnum;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\Tenant\productService;
use Yajra\DataTables\Facades\DataTables;

class TenantProductController extends BaseTenantController
{
  protected $productService;
  protected $productRepository;

  public function __construct(productService $productService, ProductRepository $productRepository)
  {
    $this->productService = $productService;
    $this->productRepository = $productRepository;
  }

  /**
   * 分類管理頁面
   */
  public function index(Request $request)
  {
    $this->authorizePermission(PermissionNameEnum::商品管理);

    if ($request->ajax()) {
      $records = $this->productRepository->getProducts();

      return DataTables::of($records)
        ->addColumn('name',                   fn($record) => $record->name)
        ->addColumn('image_url',              fn($record) => $record->image_url)
        ->addColumn('categories',             fn($record) => $record->categories->pluck('name')->toArray())
        ->addColumn('inventory_management',   fn($record) => 0)
        ->addColumn('status_name',            fn($record) => $record->status->name)
        ->addColumn('status_badge',           fn($record) => $record->status->badgeClass())
        ->addColumn('price',                  fn($record) => 0)
        ->make(true);
    }

    return view('content.tenant.product.tenant-product');
  }

  /**
   * 顯示新增商品表單
   */
  public function create()
  {
    $this->authorizePermission(PermissionNameEnum::商品管理);

    return view('content.tenant.product.tenant-product-add', [
      'categories' => Category::all(),
    ]);
  }

  /**
   * 創建商品
   */
  public function store(Request $request)
  {
    $this->authorizePermission(PermissionNameEnum::商品管理);

    try {
      $attributes = $request->validate([
        'name'                  => 'required|string|max:255',
        'description'           => 'nullable|string|max:255',
        'image_url'             => 'nullable|string|max:255',
        'inventory_management'  => 'nullable|string|max:255',
        'status'                => 'required|string|max:255',
        'categories'            => 'nullable|array',
        'categories.*'          => 'integer|exists:categories,id',
      ],[],[
        'name'                  => '商品名稱',
        'description'           => '商品描述',
        'image_url'             => '商品圖片',
        'inventory_management'  => '庫存管理方式',
        'status'                => '狀態',
        'categories'            => '商品分類',
        'categories.*'          => '商品分類',
      ]);

      $product = $this->productService->createProduct($attributes);

      return $this->successResponse('商品新增成功', ['redirect_url' => route('tenant.products.index', ['tenant' => tenant('id')])], 200);
    } catch (\Throwable $e) {
      return $this->errorResponse('商品新增失敗，請聯絡管理者', 500);
    }
  }

  /**
   * 顯示商品資料
   */
  public function show($id)
  {
    $this->authorizePermission(PermissionNameEnum::商品管理);

    $product = $this->productRepository->findOrFail($id);

    return $this->successResponse('商品取得成功', ['product' => $product], 200);
  }

  /**
   * 顯示編輯商品表單
   */
  public function edit($id)
  {
    $this->authorizePermission(PermissionNameEnum::商品管理);

    $product = $this->productRepository->findOrFail($id);

    return view('content.tenant.product.tenant-product-add', [
      'product' => $product,
      'categories' => Category::all(),
    ]);
  }

  /**
   * 更新商品
   */
  public function update(Request $request, $id)
  {
    $this->authorizePermission(PermissionNameEnum::商品管理);

    try {
      $attributes = $request->validate([
        'name'                  => 'required|string|max:255',
        'description'           => 'nullable|string|max:255',
        'image_url'             => 'nullable|string|max:255',
        'status'                => 'required|string|max:255',
        'inventory_management'  => 'nullable|string|max:255',
        'categories'            => 'nullable|array',
        'categories.*'          => 'integer|exists:categories,id',
      ],[],[
        'name'                  => '商品名稱',
        'description'           => '商品描述',
        'image_url'             => '商品圖片',
        'status'                => '狀態',
        'inventory_management'  => '庫存管理方式',
        'categories'            => '商品分類',
        'categories.*'          => '商品分類',
      ]);

      $product = $this->productService->updateProduct($id, $attributes);

      return $this->successResponse('商品更新成功', ['redirect_url' => route('tenant.products.index', ['tenant' => tenant('id')])], 200);
    } catch (\Throwable $e) {
      return $this->errorResponse('商品更新失敗，請聯絡管理者', 500);
    }
  }

  /**
   * 刪除商品
   */
  public function destroy($id)
  {
    $this->authorizePermission(PermissionNameEnum::商品管理);

    try {
      $this->productService->deleteProduct($id);

      return $this->successResponse('商品刪除成功', ['redirect_url' => route('tenant.products.index', ['tenant' => tenant('id')])], 200);
    } catch (\Throwable $e) {
      return $this->errorResponse('商品刪除失敗，請聯絡管理者', 500);
    }
  }
}
