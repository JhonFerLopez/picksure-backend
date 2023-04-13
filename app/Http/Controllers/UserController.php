<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLikeCategory;
use App\Models\UserLikeImageproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{

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
   /**
     * @OA\Get(
     *  tags={"Likes"},
     *  summary="Devuelve todos los likes realizados por un usuario",
     *  description="Retorna un Json con todos los likes  realizados por un usuario a las imagenes filtrandolo por el idioma",
     *  path="/api/v1/user/like_imageproduct/{user_id}/{lang_id}",
     *  security={{ "bearerAuth": {} }},
     * @OA\Parameter(
     *    name="user_id",
     *    in="path",
     *    description="Id del usuario",
     *    required=true,
     *    @OA\Schema(
     *      default="1",
     *      type="integer",
     *    )
     *  ),
     * * @OA\Parameter(
     *    name="lang_id",
     *    in="path",
     *    description="Id del lenguaje",
     *    required=true,
     *    @OA\Schema(
     *      default="1",
     *      type="integer",
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Resultado de la Operación",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="integer", example="200"),
     *       @OA\Property(property="title de la imagen", type="varchar", example="String")
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Estado Invalido de la Operación",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="No esxite like"),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */
  public function showLikeImageproduct($user_id,$lang_id)
  {
    $user_id = DB::table('user_like_imageproduct')
      ->join('imageproducts', 'imageproducts.id', '=', 'user_like_imageproduct.imageproduct_id')
      ->join('texts_imageproducts','texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->select('user_like_imageproduct.user_id', 'imageproducts.id','texts_imageproducts.language_id','texts_imageproducts.title', 'texts_imageproducts.description')
      ->where('user_like_imageproduct.user_id', $user_id)
      ->where('texts_imageproducts.language_id', $lang_id)
      
      ->get();
     if(!count($user_id) > 0){
      $response['status'] = Response::HTTP_NOT_FOUND;
      $response['data'] = 'Este usuario no le ha dado Like a ninguna imagen';

    }else{
      $response['status'] = Response::HTTP_OK;
      $response['data'] = $user_id;
    }
    return response()->json($response, $response['status']);
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

  /**
     * @OA\Get(
     *  tags={"Likes"},
     *  summary="Devuelve todos los likes realizados por un usuario",
     *  description="Retorna un Json con todos los likes  realizados por un usuario a las categorias filtrandolo por el lenguaje",
     *  path="/api/v1/user/like_category/{user_id}/{lang_id}",
     *  security={{ "bearerAuth": {} }},
     * @OA\Parameter(
     *    name="user_id",
     *    in="path",
     *    description="Id del usuario",
     *    required=true,
     *    @OA\Schema(
     *      default="1",
     *      type="integer",
     *    )
     *  ),
     *    * @OA\Parameter(
     *    name="lang_id",
     *    in="path",
     *    description="Id del lenguaje",
     *    required=true,
     *    @OA\Schema(
     *      default="1",
     *      type="integer",
     *    )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Resultado de la Operación",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="integer", example="200"),
     *       @OA\Property(property="title de la imagen", type="varchar", example="String")
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Estado Invalido de la Operación",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="No esxite like"),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */
  public function showLikeCategory($user_id,$lang_id)
  {
    //$likeCategory = UserLikeCategory::where('user_id', $request->id)->get();
    $user_id = DB::table('user_like_category')
      ->join('categories', 'categories.id', '=', 'user_like_category.category_id')
      ->join('texts_categories','texts_categories.category_id', '=', 'categories.id')
      ->select('user_like_category.user_id', 'categories.id','texts_categories.language_id','texts_categories.name')
      ->where('user_like_category.user_id', $user_id)
       ->where('texts_categories.language_id', $lang_id)
      
      ->get();
     if(!count($user_id) > 0){
      $response['status'] = Response::HTTP_NOT_FOUND;
      $response['data'] = 'Este usuario no exite o aun no le ha dado Like a ninguna categoria';

    }else{
      $response['status'] = Response::HTTP_OK;
      $response['data'] = $user_id;
    }
    return response()->json($response, $response['status']);
    
  }

  
}