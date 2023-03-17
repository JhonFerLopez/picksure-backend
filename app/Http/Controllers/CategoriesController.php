<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLikeCategory;
use App\Models\UserLikeImageproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{

	public function index(Request $request)
  {    	
    $categories = DB::table('categories')
      ->join('texts_categories', 'texts_categories.category_id', '=', 'categories.id')
      ->select(
        'categories.id', 
        'texts_categories.name'
      )
      ->where('texts_categories.language_id', '=', $request->lenguage)
      ->orderBy('categories.name', 'asc')
      ->get();

    return response()->json($categories, 200);
  }
  
}