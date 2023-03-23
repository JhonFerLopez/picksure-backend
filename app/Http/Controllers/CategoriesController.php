<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{

  /**
     * @OA\Get(
     *  tags={"Categorias"},
     *  summary="Devuelve todos los Categorias filtrando el lenguage recibido",
     *  description="Retorna un Json con los Datos de las Categorias.",
     *  path="/api/v1/categories",
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
     *       @OA\Property(type="array",@OA\Items(type="array",@OA\Items())),
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
	public function index(Request $request)
  {    	
    if(!isset($request->lenguage)){
      return response()->json(array('data' => 'No existe el parametro id Lenguage'), 502);
    }
    $idLenguage = Language::where('id', $request->lenguage)->first();
    if($idLenguage){
      $categories = DB::table('categories')
      ->join('texts_categories', 'texts_categories.category_id', '=', 'categories.id')
      ->select(
        'categories.id', 
        'texts_categories.name'
      )
      ->where('texts_categories.language_id', '=', $request->lenguage)
      ->orderBy('categories.name', 'asc')
      ->get();
      $response['status'] = 200;
      $response['data'] = $categories; 
    }else {
      $response['status'] = 402;
      $response['data'] = 'Lenguaje no existe.';
    }
  
    return response()->json($response, $response['status']);
  }

  /**
     * @OA\Get(
     *  tags={"Categorias"},
     *  summary="Devuelve todos los Municipios asignados a un departamento",
     *  description="Retorna un Json con los Datos de los Municipios.",
     *  path="/api/v1/categories/{prefijo}",
     *  security={{ "bearerAuth": {} }},
     *  @OA\Parameter(
     *    name="prefijo",
     *    in="path",
     *    description="prefijo",
     *    required=true,
     *    @OA\Schema(
     *      default="ES",
     *      type="string",
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Resultado de la Operación",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="1"),
     *       @OA\Property(property="name", type="string", example="Cali"),
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
  public function idioma(Request $request, $prefijo){
  
    return response()->json($prefijo, 200);
  }
  
}