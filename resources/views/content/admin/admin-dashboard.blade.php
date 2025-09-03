@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', '租戶管理')

@section('vendor-style')
@endsection

@section('page-style')
@endsection

@section('vendor-script')
@endsection

@section('page-script')
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1">租戶管理</h4>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            <a href="{{ route('central.tenant.create') }}" class="btn btn-primary">
                <span>
                    <i class="bx bx-plus me-0 me-sm-2"></i>
                    <span class="d-none d-sm-inline-block"> 新增租戶 </span>
                </span>
            </a>
        </div>
    </div>

    {{-- <div class="row mb-2">
        @foreach ($tenants as $tenant)
            <div class="col-12 col-sm-6 col-lg-4 mb-4 ">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5>{{ $tenant->tenant_name }} {{ $tenant->identifier }}</h5>

                        <p class="badge bg-label-{{ $tenant->status == 'activated' ? 'primary' : 'danger' }}">
                            {{ $tenant->status == 'activated' ? '已開通' : '未開通' }}</p>
                        <p>{{ $tenant->expire_date }}</p>
                        <a href="{{ route('central.tenant.edit', ['tenant' => $tenant->id]) }}" class="btn btn-primary"> 管理
                        </a>
                        <a href="{{ url('/') . '/' . $tenant->id }}" class="btn btn-primary" target="_blank"> 網站 </a>
                        <a href="{{ route('central.tenant.impersonate', ['tenant' => $tenant->id]) }}"
                            class="btn btn-primary" target="_blank"> 模擬登入 </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}
@endsection
