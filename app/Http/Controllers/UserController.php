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
/**
     * @OA\Post(
     *  tags={"Likes"},
     *  summary="Crea like que un usuario de a las imagenes",
     *  description="Crea like que un usuario de a las imagenes",
     *  path="/api/v1/user/like_imageproduct/{user_id}/{img_id}",
     *  security={{ "bearerAuth": {} }},
     * * @OA\Parameter(
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
     *    name="img_id",
     *    in="path",
     *    description="Id de la imagen",
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
  /**Create Like ImageProduct*/
  public function createLikeImageproduct(Request $request,$user_id, $img_id)
  {
    $likeImageproduct = UserLikeImageproduct::create($request->$user_id);
    return response()->json($likeImageproduct, 200);
  }
   /**
     * @OA\Get(
     *  tags={"User"},
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
        $response['data'] = 'Este usuario no exite o no a dado like a ninguna imagen dentro del lenguaje especificado';
       }else{
        $response['status'] = Response::HTTP_OK;
        $response['data'] = $user_id;
      }
      return response()->json($response, $response['status']);
    }
/**
     * @OA\Delete(
     *  tags={"Likes"},
     *  summary="Elimina un like dado por un usuairo",
     *  description="Elimina un like dado por un usuairo a una imagen",
     *  path="/api/v1/user/like_imageproduct/{user_id}/{img_id}",
     *  security={{ "bearerAuth": {} }},
     * * @OA\Parameter(
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
     *    name="img_id",
     *    in="path",
     *    description="Id de la imagen a la que desea eliminarle el like",
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
     *       @OA\Property(property="message", type="string", example="Like eliminado"),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */
  /**Delete Like ImageProduct*/
  public function deleteLikeImageproduct($user_id, $img_id)
  {
    $delete = DB::table('user_like_imageproduct')
    ->where('user_id', $user_id)  
    ->where('imageproduct_id', $img_id )
    ->delete();
    return response()->json('Se elimino este like', 200);
  }

 /**
     * @OA\Post(
     *  tags={"Likes"},
     *  summary="Crea like de un usuario a las categorias",
     *  description="Crea like que un usuario de a las categorias",
     *  path="/api/v1/user/like_category/{user_id}/{category_id}",
     *  security={{ "bearerAuth": {} }},
     * @OA\RequestBody(
     *  @OA\MediaType(
     *   mediaType="application/raw",
     *   @OA\Schema(
     *    required={"email","password"},
     *    @OA\Property(property="email", type="string", example="flover.sanchez@ziel.com.co"),
     *     @OA\Property(property="password", type="string", example="password"),
     *    )
     *  ),
     * ),
     * * @OA\Parameter(
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
     *    name="category_id",
     *    in="path",
     *    description="Id de la categoria",
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

/**
     * @OA\Delete(
     *  tags={"Likes"},
     *  summary="Elimina un like dado por un usuario",
     *  description="Elimina un like dado por un usuairo a una categoria",
     *  path="/api/v1/user/like_category/{user_id}/{category_id}",
     *  security={{ "bearerAuth": {} }},
     * * @OA\Parameter(
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
     *    name="category_id",
     *    in="path",
     *    description="Id de la categoria a la cual desea eliminar el like",
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
     *       @OA\Property(property="message", type="string", example="Se eliminó el like a esta categoria"),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */
  /**Delete Like Category*/
  public function deleteLikeCategory($user_id,$category_id )
  {
    $delete = DB::table('user_like_category')
    ->where('user_id', $user_id)  
    ->where('category_id', $category_id)
    ->delete();

    return response()->json('Se elimino este like', 200);
  }
  /**Select Like Category*/

  /**
     * @OA\Get(
     *  tags={"User"},
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
      $response['data'] = 'Este usuario no exite o no a dado like a ninguna categoria dentro del lenguaje especificado';

    }else{
      $response['status'] = Response::HTTP_OK;
      $response['data'] = $user_id;
    }
    return response()->json($response, $response['status']);
    
  }

  /**
     * @OA\Post(
     *  tags={"Usuarios"},
     *  summary="Crea un nuevo usuario",
     *  description="Crea un nuevo usuario en el sistema",
     *  path="/api/v1/user/create_users/{user_id}",
     *  security={{ "bearerAuth": {} }},
     * * @OA\Parameter(
     *    name="Name",
     *    in="path",
     *    description="Nombre de usuario",
     *    required=true,
     *    @OA\Schema(
     *      default="1",
     *      type="varchar",
     *    )
     *  ),
     * * @OA\Parameter(
     *    name="email",
     *    in="path",
     *    description="Correo",
     *    required=true,
     *    @OA\Schema(
     *      default="1",
     *      type="integer",
     *    )
     *  ),
     *  * * @OA\Parameter(
     *    name="password",
     *    in="path",
     *    description="Contraseña",
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
  public function CreateUser(Request $request ,$user_id)
  {
    $user = new User;
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();

    return redirect('/home');
}

 /**
     * @OA\Delete(
     *  tags={"Usuarios"},
     *  summary="Elimina un usuario",
     *  description="Elimina un usuario",
     *  path="/api/v1/user/delete_users/{user_id}",
     *  security={{ "bearerAuth": {} }},
     * * @OA\Parameter(
     *    name="user_id",
     *    in="path",
     *    description="Id del usuario",
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
     *       @OA\Property(property="message", type="string", example="Se eliminó este usuario"),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */
 public function DeleteUser($user_id){

  $delete = DB::table('users')
  ->where('id', $user_id)  
  ->delete();

  return response()->json('Usuario eliminado', 200);
  }

 /**
     * @OA\Put(
     *  tags={"Usuarios"},
     *  summary="Actualiza la información de un usuario",
     *  description="Actualiza la información de un usuario seleccionado",
     *  path="/api/v1/user/update_users/{user_id}",
     *  security={{ "bearerAuth": {} }},
     * * @OA\Parameter(
     *    name="user_id",
     *    in="path",
     *    description="Id del usuario",
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
public function UpdateUser($user_id){

  }

/**
     * @OA\Get(
     *  tags={"Usuarios"},
     *  summary="Muestra toda la informacion",
     *  description="Muestra toda la informacion del usuario",
     *  path="/api/v1/user/show_users/{user_id}",
     *  security={{ "bearerAuth": {} }},
     * * @OA\Parameter(
     *    name="user_id",
     *    in="path",
     *    description="Id del usuario",
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
     *       @OA\Property(property="message", type="string", example="No esxite este usuario"),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */
  public function ShowInfoUser($user_id){

    $$user_id = DB::table('users')
      ->select('users.id', 'users.name', 'users.last_name', 'users.email', 'users.created_at','users.avatar')
      ->where('users.id', $user_id)
      ->get();
      if(!count($$user_id) > 0){
        $response['status'] = Response::HTTP_NOT_FOUND;
        $response['data'] = 'Este usuario no existe';
      }else{
        $response['status'] = Response::HTTP_OK;
        $response['data'] = $$user_id;
      }
      return response()->json($response, $response['status']); 
  }

  
}