@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', '員工管理')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('js/tenant/user/tenant-user.js?v=' . time()) }}"></script>
@endsection

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h3 class="mb-1">員工管理</h3>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            <a href="javascript:void(0);" class="btn btn-primary" id="addUserBtn">
                <span>
                    <i class="bx bx-plus me-0 me-sm-2"></i>
                    <span class="d-none d-sm-inline-block"> 新增員工 </span>
                </span>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable text-nowrap">
            <table class="user-datatable table border-top">
                <thead>
                    <tr>
                        <th>員工姓名</th>
                        <th>員工信箱</th>
                        <th>建立時間</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- 員工 modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="card-title">新增員工</h3>
                    </div>
                    <form id="userForm" class="row g-3" onsubmit="return false">
                        <input type="hidden" id="userId" name="userId" />
                        <div class="col-md-12">
                            <label class="form-label" for="name">員工姓名</label>
                            <input type="text" id="name" name="name" class="form-control" />
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="email">員工信箱</label>
                            <input type="text" id="email" name="email" class="form-control" />
                        </div>
                        <div class="col-md-12 mb-2 form-password-toggle">
                            <label class="form-label" for="password">密碼</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" value="" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">取消</button>
                            <button type="submit" class="btn btn-primary me-sm-3 me-1" id="saveBtn">儲存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- 員工 modal -->
@endsection
