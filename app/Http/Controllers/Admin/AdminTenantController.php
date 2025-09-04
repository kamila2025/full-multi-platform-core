<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\Tenant\TenantStatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\tenantService;
use Yajra\DataTables\Facades\DataTables;

class AdminTenantController extends Controller
{
  protected $tenantService;

  public function __construct(tenantService $tenantService)
  {
    $this->tenantService = $tenantService;
  }

  /**
   * 租戶管理頁面
   */
  public function index(Request $request)
  {
    if ($request->ajax()) {
      $records = Tenant::orderBy('sort', 'asc')
          ->get();

      return DataTables::of($records)
        ->addColumn('tenant_id',            fn($record) => $record->id)
        ->addColumn('tenant_name',          fn($record) => $record->name)
        ->addColumn('tenant_expire_date',   fn($record) => $record->expire_date)
        ->addColumn('tenant_status',        fn($record) => TenantStatusEnum::from($record->status)->label())
        ->addColumn('tenant_status_badge',  fn($record) => TenantStatusEnum::from($record->status)->badge())
        ->addColumn('tenant_created_at',    fn($record) => $record->created_at->format('Y-m-d H:i:s'))
        ->make(true);
      }

      return view('content.admin.admin-tenants');
  }

  /**
   * 顯示創建租戶表單
   */
  public function create()
  {
      $tenantId = $this->tenantService->generateUniqueTenantId(6);

      return view('content.admin.admin-tenants-add', ['tenantId' => $tenantId]);
  }

  /**
   * 創建租戶
   */
  public function store(Request $request)
  {
    try {
      $attributes = $request->validate([
        'id'           => 'required|string|max:12',
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|max:255',
        'password'     => 'required|string|min:5',
        'expire_date'  => 'required|date',
        'status'       => 'required|string|in:activated,unactivated',
      ],[],[
        'id'           => '租戶ID',
        'name'         => '租戶名稱',
        'email'        => '租戶信箱',
        'password'     => '租戶密碼',
        'expire_date'  => '到期時間',
        'status'       => '租戶狀態',
      ]);

      // 創建租戶與租戶管理員
      $this->tenantService->createTenant($attributes);

      return $this->successResponse('租戶新增成功', ['redirect_url' => route('admin.tenants.index')], 200);
    } catch (\Throwable $e) {
      return $this->errorResponse('租戶新增失敗，請聯絡管理者', null, 500);
    }
  }

  /**
   * 顯示編輯租戶表單
   */
  public function edit($id)
  {
      $tenant = Tenant::findOrFail($id);

      return view('content.admin.admin-tenants-add', ['tenant' => $tenant]);
  }

  /**
   * 更新租戶
   */
  public function update(Request $request, $id)
  {
    try {
      $attributes = $request->validate([
        'id'           => 'required|string|max:12',
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|max:255',
        'password'     => 'nullable|string|min:5',
        'expire_date'  => 'required|date',
        'status'       => 'required|string|in:activated,unactivated',
      ],[],[
        'id'           => '租戶ID',
        'name'         => '租戶名稱',
        'email'        => '租戶信箱',
        'password'     => '租戶密碼',
        'expire_date'  => '到期時間',
        'status'       => '租戶狀態',
      ]);

      // 更新租戶
      $this->tenantService->updateTenant($id, $attributes);

      return $this->successResponse('租戶更新成功', ['redirect_url' => route('admin.tenants.index')], 200);
    } catch (\Throwable $e) {
      return $this->errorResponse('租戶更新失敗，請聯絡管理者', null, 500);
    }
  }

  /**
   * 刪除租戶
   */
  public function destroy($id)
  {
    try {
      $this->tenantService->deleteTenant($id);

      return $this->successResponse('租戶刪除成功', ['redirect_url' => route('admin.tenants.index')], 200);
    } catch (\Throwable $e) {
      return $this->errorResponse('租戶刪除失敗，請聯絡管理者', null, 500);
    }
  }

  /**
   * 模擬登入租戶
   */
  public function simulateLogin($id)
  {
  }
}
