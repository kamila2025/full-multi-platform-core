@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', '租戶管理')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-select-bs5/select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('js/admin/admin-tenants.js?v=' . time()) }}"></script>
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h3 class="mb-1">租戶管理</h3>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
                <span>
                    <i class="bx bx-plus me-0 me-sm-2"></i>
                    <span class="d-none d-sm-inline-block"> 新增租戶 </span>
                </span>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap">
            <table class="tenant-datatable table border-top">
                <thead>
                    <tr>
                        <th>租戶ID</th>
                        <th>租戶名稱</th>
                        <th>到期時間</th>
                        <th>狀態</th>
                        <th>建立時間</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
