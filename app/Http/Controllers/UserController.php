<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLikeCategory;
use App\Models\UserLikeImageproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

  public function login(Request $request)
  {    	
    $response = ['status' => 200, 'msg' => 'Hola John' ];
    $data = json_decode($request->getContent());
    $user = User::where('email', $data->email)->first();
    if($user){
      if(Hash::check($data->password, $user->password)){
        $token = $user->createToken('example');
        $response['status'] = 200;
        $response['msg'] = $token->plainTextToken; 
      }else {
        $response['status'] = 402;
        $response['msg'] = 'Password Incorrecta.';  
      }
    }else {
      $response['status'] = 402;
      $response['msg'] = 'Usuario no existe.';
    }
    return response()->json($response, 200);
  }

	public function index(Request $request)
  {    	
    $user = User::all();
    return response()->json($user, 200);
  }

  /**Create Like ImageProduct*/
  public function createLikeImageproduct(Request $request)
  {
    $likeImageproduct = UserLikeImageproduct::create($request->all());
    return response()->json($likeImageproduct, 200);
  }
  /**Show Like ImageProduct*/
  public function showLikeImageproduct(Request $request)
  {
    $likeImageproduct = DB::table('user_like_imageproduct')
      ->join('imageproducts', 'imageproducts.id', '=', 'user_like_imageproduct.imageproduct_id')
      ->join('texts_imageproducts', 'texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'texts_imageproducts.title', 'texts_imageproducts.description')
      ->where('user_like_imageproduct.user_id', '=', $request->user_id)
      ->where('texts_imageproducts.language_id', '=', $request->lenguage)
      ->get();
    return response()->json($likeImageproduct, 200);
  }
  /**Delete Like ImageProduct*/
  public function deleteLikeImageproduct(Request $request)
  {
    $this->validate($request, [
      'user_id' => 'required',
      'imageproduct_id' => 'required'
    ]);

    $likeImageproduct = DB::table('user_like_imageproduct')
    ->where('user_id', $request->user_id)  
    ->where('imageproduct_id', $request->imageproduct_id)
    ->delete();

    return response()->json('Deleted Successfully', 200);
  }

  /**Create Like Category*/
  public function createLikeCategory(Request $request)
  {
    DB::table('user_like_category')
    ->where('user_id', $request->user_id)  
    ->delete();

    foreach($request->category_id as $category){
      $likeCategory = UserLikeCategory::create([
        'user_id' => $request->user_id,
        'category_id' => $category
      ]);
    }    
    return response()->json($likeCategory, 200);
  }
  /**Delete Like Category*/
  public function deleteLikeCategory(Request $request)
  {
    $this->validate($request, [
      'user_id' => 'required',
      'category_id' => 'required'
    ]);

    $likeCategory = DB::table('user_like_category')
    ->where('user_id', $request->user_id)  
    ->where('category_id', $request->category_id)
    ->delete();

    return response()->json('Deleted Successfully', 200);
  }
  /**Select Like Category*/
  public function showLikeCategory(Request $request)
  {
    //$likeCategory = UserLikeCategory::where('user_id', $request->id)->get();
    $texts = DB::table('categories')
      ->leftJoin('user_like_category', function($leftJoin) use ($request){
        $leftJoin->on('categories.id', '=', 'user_like_category.category_id')
        ->where('user_like_category.user_id', '=', $request->user_id);
      })
      ->select(
        'categories.id', 
        'categories.name',
        DB::raw('(CASE WHEN user_like_category.user_id IS NULL THEN false ELSE true END) as like_category') 
      )
      ->orderBy('categories.name', 'asc')
      ->get();

    return response()->json($texts, 200);
  }

  
}