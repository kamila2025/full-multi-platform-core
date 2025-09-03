<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminTenantController extends Controller
{
  function index()
  {
    return view('content.admin.admin-tenant');
  }
}
