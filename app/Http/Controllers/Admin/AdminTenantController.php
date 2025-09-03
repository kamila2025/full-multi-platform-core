<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class AdminTenantController extends Controller
{
  /**
   * 租戶管理頁面
   */
  function index()
  {
      return view('content.admin.admin-tenants');
  }

  function create()
  {
      $tenantId = $this->generateUniqueTenantId(6);

      return view('content.admin.admin-tenants-add', ['tenantId' => $tenantId]);
  }

  function store(Request $request)
  {
    try {
      DB::beginTransaction();

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

      $tenantId = $this->generateUniqueTenantId(6);

      $formattedPayments = [];
      foreach ($attributes['payments'] as $index => $payment) {
        $identifier    = $payment["group-a[$index][form-repeater-1]"] ?? null;
        $customer_name = $payment["group-a[$index][form-repeater-2]"] ?? null;
        $customer_id   = $payment["group-a[$index][form-repeater-3]"] ?? null;
        $str_check     = $payment["group-a[$index][form-repeater-4]"] ?? null;

        if($identifier == null || $customer_name == null || $customer_id == null || $str_check == null) {
          continue;
        }

        $formattedPayments[] = [
          'identifier'    => $identifier,
          'customer_name' => $customer_name,
          'customer_id'   => $customer_id,
          'str_check'     => $str_check,
        ];
      }

      $tenantId = $this->generateUniqueTenantId(6);
      $tenantData = [
          'id'              => $tenantId,
          'tenant_name'     => $attributes['name'],
          'tenancy_db_name' => 'adminserver_' . $tenantId,
          'identifier'      => $attributes['identifier'],
          'expire_date'     => $attributes['expire_date'],
          'status'          => $attributes['status'],
          'payment'         => $formattedPayments,
          'admin_email'     => $attributes['email'],
      ];

      CreateTenantWithAdminUser::dispatch(
        $tenantData,
        $attributes['email'],
        $attributes['password']
      );

      return response()->json(['message' => '保存成功'], 200);
    } catch(\Illuminate\Validation\ValidationException $e) {
      $errorMessage = $e->validator->errors()->first();
      return response()->json(['error' => $errorMessage], 422);
    }
    catch (\Exception $e) {
      return response()->json(['error' => '新增失敗，請聯絡管理者'], 500);
    }
  }

  /**
   * 生成唯一的租戶ID
   */
  public function generateUniqueTenantId($length = 6)
  {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

    do {
        $tenantId = substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
    } while (Tenant::where('id', $tenantId)->exists());

    return $tenantId;
  }
}
