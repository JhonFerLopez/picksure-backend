<?php

namespace App\Http\Controllers;

use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TownController extends Controller
{
  public function department(Request $request, $id)
  {    	
    $town = Town::select(['id', 'name'])
    ->where('department_id', $id)
    ->orderBy('name', 'asc')
    ->get();
    return response()->json($town, 200);
  }
}