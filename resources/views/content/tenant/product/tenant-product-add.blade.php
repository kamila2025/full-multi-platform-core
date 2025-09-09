@extends('layouts/layoutMaster')

@section('title', isset($product) ? '編輯商品' : '新增商品')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
@endsection

@section('page-script')
    <script>
        // 初始化 select2
        $('.select2').select2();
    </script>
    <script src="{{ asset('js/tenant/product/tenant-product-add.js?v=' . time()) }}"></script>
@endsection

@section('content')
    <div class="app-ecommerce">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                @if (isset($product))
                    <h3 class="mb-2 mt-3">{{ $product->name }}</h3>
                    <p class="text-muted">最後更新於 : {{ $product->updated_at->format('Y-m-d H:i') }}</p>
                @else
                    <h3 class="mb-1">新增商品</h3>
                @endif
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <a href="{{ route('tenant.products.index', ['tenant' => tenant('id')]) }}"
                    class="btn btn-label-secondary">返回</a>
                @if (isset($product))
                    <a href="javascript:void(0)" type="button" id="deleteBtn" class="btn btn-label-danger">刪除</a>
                @endif
                <button type="button" id="saveButton" class="btn btn-primary">儲存</button>
            </div>
        </div>
        <form id="productForm">
            <input type='hidden' id="product_id" name='product_id' value="{{ $product->id ?? '' }}">

            <div class="row">
                <!-- 左側欄位 -->
                <div class="col-12 col-lg-8">
                    <!-- 商品資訊 -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">商品資訊</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 mb-2">
                                    <label for="name" class="form-label">商品名稱</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ isset($product) ? $product->name : '' }}">
                                </div>

                                <div class="col-12 mb-2">
                                    <label for="description" class="form-label">商品描述</label>
                                    <textarea type="text" id="description" name="description" class="form-control" rows="4">{{ isset($product) ? $product->description : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 商品資訊 -->

                    <!-- 商品圖片 -->
                    {{-- <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">商品圖片</h5>
                        </div>
                        <div class="card-body">
                            <div class="dropzone needsclick" id="dropzone-multi">
                                <div class="dz-message needsclick">
                                    上傳檔案或是拖曳至此
                                    <span class="note needsclick">大小限制：10MB, 1500 × 1500 pixel 您可以上傳 JPEG, PNG or
                                        GIF 類型檔案</span>
                                </div>
                                <div class="fallback">
                                    <input name="file" type="file" />
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- 商品圖片 -->

                    <!-- 商品庫存 -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">商品庫存</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 mb-2">
                                    <label for="inventory_management" class="form-label">是否追蹤庫存數量</label>
                                    <select id="inventory_management" name="inventory_management"
                                        class="select2 form-select">
                                        <option value="store"
                                            {{ isset($product) ? ($product->inventory_management->value == 'store' ? 'selected' : '') : '' }}>
                                            追蹤庫存數量</option>
                                        <option value="none"
                                            {{ isset($product) ? ($product->inventory_management->value == 'none' ? 'selected' : '') : '' }}>
                                            不追蹤庫存數量</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 商品庫存 -->
                </div>
                <!-- 左側欄位 -->

                <!-- 右側欄位 -->
                <div class="col-12 col-lg-4">
                    <!-- 發佈狀態 -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">發佈狀態</h5>
                        </div>
                        <div class="card-body">
                            <div class="col-12 mb-2">
                                <select id="status" name="status" class="select2 form-select">
                                    <option value="active"
                                        {{ isset($product) ? ($product->status->value == 'active' ? 'selected' : '') : '' }}>
                                        已發佈</option>
                                    <option value="inactive"
                                        {{ isset($product) ? ($product->status->value == 'inactive' ? 'selected' : '') : '' }}>
                                        未發佈</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- 發佈狀態 -->

                    <!-- 商品分類 -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="col-12 mb-2">
                                <label for="categories" class="form-label">商品分類</label>
                                <select id="categories" class="select2 form-select" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ isset($product) ? ($product->categories->contains($category->id) ? 'selected' : '') : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- 商品分類 -->
                </div>
                <!-- 右側欄位 -->
            </div>
        </form>
    </div>
@endsection
