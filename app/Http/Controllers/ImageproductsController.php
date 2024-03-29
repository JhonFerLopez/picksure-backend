<?php

namespace App\Http\Controllers;

use App\Models\Imageproduct;
use App\Models\Language;
use App\Models\TextsImageproducts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class ImageproductsController extends Controller
{
  /**
     * @OA\Get(
     *  tags={"Imagenes"},
     *  summary="Devuelve todas las imagenes filtrando el lenguaje",
     *  description="Retorna un Json con los titulos de las imagenes filtradas por lenguaje",
     *  path="/api/v1/imageproducts/{language}",
     *  security={{ "bearerAuth": {} }},
     *  @OA\Parameter(
     *    name="language",
     *    in="path",
     *    description="Prefijo del Idioma",
     *    required=true,
     *    @OA\Schema(
     *      default="ES",
     *      type="string",
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="offset",
     *    in="query",
     *    description="offset o Limit de datos",
     *    @OA\Schema(
     *      default="0",
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
		public function index(Request $request, $language)
    {  	
      if($language){
        $image = DB::table('imageproducts')
        ->join('texts_imageproducts', 'texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
        ->select('imageproducts.id', 'texts_imageproducts.language','imageproducts.img_url' ,'texts_imageproducts.title', 'texts_imageproducts.description')
        ->where('texts_imageproducts.language', '=', $language)
        ->get();
        $response['status'] = 200;
        $response['data'] = $image; 
      }	else {
        $response['status'] = 402;
        $response['data'] = 'Lenguaje no encontrado.';
      }
      return response()->json($response, $response['status']);
    } 
   /**
     *  @OA\Get(
     *  tags={"Imagenes"},
     *  summary="Devuelve la imagen por el id",
     *  description="Retorna un Json con la imagene seleccionada por le Id ",
     *  path="/api/v1/imageproducts/{language}/{image_id}",
     *  security={{ "bearerAuth": {} }},
     *  @OA\Parameter(
     *    name="language",
     *    in="path",
     *    description="Prefijo del Idioma",
     *    required=true,
     *    @OA\Schema(
     *      default="ES",
     *      type="string",
     *    )
     * ),
     * @OA\Parameter(
     *    name="image_id",
     *    in="path",
     *    description="Id de la imagen",
     *    required=true,
     *    @OA\Schema(
     *      default="1",
     *      type="integer",
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="offset",
     *    in="query",
     *    description="offset o Limit de datos",
     *    @OA\Schema(
     *      default="0",
     *      type="integer",
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
     *       @OA\Property(property="errors", type="string", example=".."),
     *    )
     *  )
     * )
     */


    public function showOne(Request $request, $language, $image_id)
    {    	
      $languageData = Language::where('abreviatura', $language)->first();

      $image_id = DB::table('imageproducts')
      ->join('texts_imageproducts','texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'texts_imageproducts.language_id','texts_imageproducts.title', 'texts_imageproducts.description','imageproducts.img_url')
      ->where('texts_imageproducts.language_id', '=', $languageData->id)
      ->where('imageproducts.id', '=', $image_id)
      ->get();
      if(!count($image_id) > 0){
        $response['status'] = Response::HTTP_NOT_FOUND;
        $response['data'] = 'Esta imagen no existe o no existe dentro del lenguaje especificado';
      }else{
        $response['status'] = Response::HTTP_OK;
        $response['data'] = $image_id;
      }
      return response()->json($response, $response['status']); 
    }

  /**
     * @OA\Get(
     *  tags={"Imagenes"},
     *  summary="Devuelve todas las imagenes por categoria",
     *  description="Retorna un Json con los las imagenes dependiendo la categoria ",
     *  path="/api/v1/imageproducts/category/{language}/{category_id}",
     *  security={{ "bearerAuth": {} }},
     *  @OA\Parameter(
     *    name="language",
     *    in="path",
     *    description="Prefijo del Idioma",
     *    required=true,
     *    @OA\Schema(
     *      default="ES",
     *      type="string",
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="category_id",
     *    in="path",
     *    description="Id de la categoria",
     *    required=true,
     *    @OA\Schema(
     *      default="1",
     *      type="integer",
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="offset",
     *    in="query",
     *    description="offset o Limit de datos",
     *    @OA\Schema(
     *      default="0",
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
     *       @OA\Property(property="message", type="string", example="Categoria no existe."),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
  */

  // Consultar ImagenProduct por Id de Categoría
    public function categoryId(Request $request, $language, $category_id)
    {  
      $languageData = Language::where('abreviatura', $language)->first();
      $images = DB::table('imageproducts')
      ->join('texts_imageproducts','texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->join('imageproduct_category', 'imageproduct_category.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'texts_imageproducts.language_id', 'texts_imageproducts.description','imageproducts.img_url')
      ->where('texts_imageproducts.language_id', $languageData->id)
      ->where('imageproduct_category.category_id', $category_id)
      ->get();
      if(!count($images) > 0){
        $response['status'] = Response::HTTP_NOT_FOUND;
        $response['data'] = 'Esta categoria no existe o no existe dentro del lenguaje especificado';
      }else{
        $response['status'] = Response::HTTP_OK;
        $response['data'] = $images;
      } 
      return response()->json($response, $response['status']);
    }

  /**
   * @OA\Get(
   *  tags={"Imagenes"},
   *  summary="Devuelve todas las imagenes filtrando el lenguaje",
   *  description="Retorna un Json con los titulos de las imagenes filtradas por lenguaje",
   *  path="/api/v1/imageproducts/filter/search/{language}",
   *  security={{ "bearerAuth": {} }},
   *  @OA\Parameter(
   *    name="language",
   *    in="path",
   *    description="Prefijo del idioma",
   *    required=true,
   *    @OA\Schema(
   *      default="ES",
   *      type="string",
   *    )
   *  ),
   *  @OA\Parameter(
   *    name="keyword",
   *    in="query",
   *    description="Frase clave",
   *    @OA\Schema(
   *      default="casa",
   *      type="string",
   *    )
   *  ),
   *  @OA\Parameter(
   *    name="category",
   *    in="query",
   *    description="ID de la Categoria",
   *    @OA\Schema(
   *      default="1",
   *      type="integer",
   *    )
   *  ),
   *  @OA\Parameter(
   *    name="offset",
   *    in="query",
   *    description="offset o Limit de datos",
   *    @OA\Schema(
   *      default="0",
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
  // Consultar ImagenProduct modalidad Search
  public function search(Request $request, $language)
  {    	
    $languageData = Language::where('abreviatura', $language)->first();

    $image = DB::table('imageproducts')
      ->join('texts_imageproducts', 'texts_imageproducts.imageproduct_id', '=', 'imageproducts.id')
      ->select('imageproducts.id', 'texts_imageproducts.title', 'texts_imageproducts.description')
      ->where('texts_imageproducts.language_id', '=', $languageData->id)
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
    $response['status'] = Response::HTTP_OK;
    $response['data'] = $image;
    
    return response()->json($response, $response['status']);
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