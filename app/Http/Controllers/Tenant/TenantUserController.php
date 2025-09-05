<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\userService;
use Yajra\DataTables\Facades\DataTables;

class TenantUserController extends Controller
{
  protected $userService;

  public function __construct(userService $userService)
  {
    $this->userService = $userService;
  }

  /**
   * 員工管理頁面
   */
  public function index(Request $request)
  {
    if ($request->ajax()) {
      $records = User::latest()->get();

      return DataTables::of($records)
        ->addColumn('user_name',       fn($record) => $record->name)
        ->addColumn('user_email',      fn($record) => $record->email)
        ->addColumn('user_created_at', fn($record) => $record->created_at->format('Y-m-d H:i:s'))
        ->addColumn('user_isAdmin',    fn($record) => $record->parameter['isAdmin'] ?? false)
        ->make(true);
      }

      return view('content.tenant.tenant-user');
  }

  /**
   * 創建員工
   */
  public function store(Request $request)
  {
    try {
      $attributes = $request->validate([
        'name'      => 'required|string|max:255',
        'email'     => 'required|email|max:255',
        'password'  => 'required|string|min:5',
      ],[],[
        'name'      => '員工姓名',
        'email'     => '員工信箱',
        'password'  => '員工密碼',
      ]);

      $this->userService->createUser($attributes);

      return $this->successResponse('員工新增成功', null, 200);
    } catch (\Throwable $e) {
      return $this->errorResponse('員工新增失敗，請聯絡管理者', 500);
    }
  }

  public function show($id)
  {
    $user = User::findOrFail($id);

    return $this->successResponse('員工取得成功', ['user' => $user], 200);
  }

  /**
   * 更新員工
   */
  public function update(Request $request, $id)
  {
    try {
      $attributes = $request->validate([
        'name'      => 'required|string|max:255',
        'email'     => 'required|email|max:255',
        'password'  => 'nullable|string|min:5',
        ],[],[
        'name'      => '員工姓名',
        'email'     => '員工信箱',
        'password'  => '員工密碼',
      ]);

      $this->userService->updateUser($id, $attributes);

      return $this->successResponse('員工更新成功', null, 200);
    } catch (\Throwable $e) {
      return $this->errorResponse('員工更新失敗，請聯絡管理者', null, 500);
    }
  }

  /**
   * 刪除員工
   */
  public function destroy($id)
  {
    try {
      $this->userService->deleteUser($id);

      return $this->successResponse('員工刪除成功', null, 200);
    } catch (\Throwable $e) {
      return $this->errorResponse('員工刪除失敗，請聯絡管理者', null, 500);
    }
  }
}
