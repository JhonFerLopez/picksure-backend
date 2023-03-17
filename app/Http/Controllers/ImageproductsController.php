<?php

namespace App\Http\Controllers;

use App\Models\Imageproduct;
use App\Models\TextsImageproducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ImageproductsController extends Controller
{
	public function index(Request $request)
  {    	
    $image = DB::table('imageproducts')
      ->join('texts_imageproducts', 'texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'texts_imageproducts.title', 'texts_imageproducts.description')
      ->where('texts_imageproducts.language_id', '=', $request->lenguage)
      ->get();
    return response()->json($image, 200);
  } 

  public function showOne(Request $request, $id)
  {    	
    $image = DB::table('imageproducts')
      ->join('texts_imageproducts', 'texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'texts_imageproducts.title', 'texts_imageproducts.description')
      ->where('texts_imageproducts.language_id', '=', $request->lenguage)
      ->where('imageproducts.id', '=', $id)
      ->get();
    return response()->json($image, 200);
  }

  // Consultar ImagenProduct por Id de Categoría
  public function categoryId(Request $request)
  {    	
    $image = DB::table('imageproducts')
      ->join('texts_imageproducts', 'texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->join('imageproduct_category', 'imageproduct_category.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'texts_imageproducts.title', 'texts_imageproducts.description')
      ->where('texts_imageproducts.language_id', '=', $request->lenguage)
      ->where('imageproduct_category.category_id', '=', $request->category)
      ->get();
    return response()->json($image, 200);
  }

  // Consultar ImagenProduct modalidad Search
  public function search(Request $request)
  {    	
    $image = DB::table('imageproducts')
      ->join('texts_imageproducts', 'texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'texts_imageproducts.title', 'texts_imageproducts.description')
      ->where('texts_imageproducts.language_id', '=', $request->lenguage)
      ->when(!empty($request->category), function($category) use ($request) {
        return $category->join('imageproduct_category', 'imageproduct_category.imageproduct_id', '=', 'imageproducts.id')
        ->where('imageproduct_category.category_id', '=', $request->category);
      })
      ->when(!empty($request->keyword), function($keyword) use ($request) {
        return $keyword->where( function($query) use ($request) {
          return $query->where('texts_imageproducts.title', 'LIKE', '%'.$request->keyword.'%')
            ->orWhere('texts_imageproducts.description', 'LIKE', '%'.$request->keyword.'%');
        });
      })
      ->get();
      //->toSql();

    return response()->json($image, 200);
  }
  /*
  public function indexFilter($id)
  {
    $users = User::where(function ($query) use ($id) {
      $query->where('firstname', 'like', '%'.$id.'%')
        ->orWhere('lastname', 'like', '%'.$id.'%')
        ->orWhere('phone', 'like', '%'.$id.'%')
        ->orWhere('email', 'like', '%'.$id.'%');
    })->get();
    return response()->json($users, 200);
  }

  public function showOneUser($id)
  {
    return response()->json(User::find($id));
  }

  public function create(Request $request)
  {
    $this->validate($request, [
      'firstname' => 'required',
      'lastname' => 'required',
      'phone' => 'required',
      'email' => 'required|email|unique:users'
    ]);

    $User = User::create($request->all());

    return response()->json($User, 201);
  }

  public function update($id, Request $request)
  {
    $User = User::findOrFail($id);
    $User->update($request->all());

    return response()->json($User, 200);
  }

  public function delete($id)
  {
    User::findOrFail($id)->delete();
    return response('Deleted Successfully', 200);
  }*/
}