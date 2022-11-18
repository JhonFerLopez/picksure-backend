<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DepartmentController extends Controller
{
  public function index(Request $request)
  {    	
    $department = Department::select(['id', 'name'])
    ->orderBy('name','asc')
    ->get();
    return response()->json($department, 200);
  }
}