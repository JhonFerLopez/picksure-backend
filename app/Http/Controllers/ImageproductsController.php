<?php

namespace App\Http\Controllers;

use App\Models\Imageproduct;
use App\Models\Language;
use App\Models\TextsImageproducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ImageproductsController extends Controller
{
  /**
     * @OA\Get(
     *  tags={"Imagenes"},
     *  summary="Devuelve todas las imagenes filtrando el lenguaje",
     *  description="Retorna un Json con los titulos de las imagenes filtradas por lenguaje",
     *  path="/api/v1/imageproducts",
     *  security={{ "bearerAuth": {} }},
     *  @OA\Parameter(
     *    name="lenguage",
     *    in="query",
     *    description="ID del Lenguage",
     *    required=true,
     *    @OA\Schema(
     *      default="1",
     *      type="integer",
     *    )
     *  ),
     *  @OA\Response(
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
     *       @OA\Property(property="message", type="string", example="Lenguaje no existe."),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */
	public function index(Request $request)
  {  
    $idLenguage = Language::where('id', $request->lenguage)->first();
    if($idLenguage){
      $image = DB::table('imageproducts')
      ->join('texts_imageproducts', 'texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'texts_imageproducts.title', 'texts_imageproducts.description')
      ->where('texts_imageproducts.language_id', '=', $request->lenguage)
      ->get();
      $response['status'] = 200;
      $response['data'] = $image; 
    }	else {
      $response['status'] = 402;
      $response['data'] = 'Lenguaje no existe.';
    }
    return response()->json($response, $response['status']);
  } 
   /**
     * @OA\Get(
     *  tags={"Imagenes"},
     *  summary="Devuelve una imagen por su Id",
     *  description="Retorna un Json con la imagen seleccionada por el Id",
     *  path="/api/v1/imageproducts/{id}",
     *  security={{ "bearerAuth": {} }},
     *  @OA\Parameter(
     *    name="Id imagen",
     *    in="path",
     *    description="Id imagen",
     *    required=true,
     *    @OA\Schema(
     *      type="string",
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Resultado de la Operación",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="1"),
     *       @OA\Property(property="name", type="string", example="imagen"),
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Estado Invalido de la Operación",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Los datos son incorrectos."),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */


  public function showOne(Request $request, $id)
  {    	
    $image = DB::table('imageproducts')
      ->join('texts_imageproducts', 'texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'imageproducts.user_id_create','texts_imageproducts.title','texts_imageproducts.imageproduct_id')
      ->where('texts_imageproducts.language_id', '=', $request->lenguage)
      ->where('imageproducts.id', '=', $id)
      ->get();
      return response()->json($id, 200);  //pendiente
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