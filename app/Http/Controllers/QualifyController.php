<?php

namespace App\Http\Controllers;

use App\Models\QualificationOption;
use App\Models\QualifyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class QualifyController extends Controller
{
  public function index(Request $request)
  {    	
    $qualify = QualificationOption::select('id', 'name', 'value', 'option')->get();
    return response()->json($qualify, 200);
  }

  //** Preguntar si debe calificar la app */
  public function rateApp(Request $request, $id)
  {    	
    $qualify = QualifyApp::where('user_id', $id)->first();
    $result = ['error' => true, 'fecha' => date('Y-m-d')];
    //var_dump(is_object($qualify));
    //var_dump(!empty($qualify));
    if(!empty($qualify)){
      $fecha_entrada = $qualify->date_ask_again;
      $fecha_actual = date("Y-m-d");
      if($fecha_entrada >= $fecha_actual){
        $result['error'] = false;
        $result['fecha'] = $fecha_entrada;
      }
    }
    return response()->json($result, 200);
  }
 /**
     * @OA\Post(
     *  tags={"Qualify"},
     *  summary="Crea Calificación de la app por el Usuario",
     *  description="Crea la calificacion dada por un usuario a la app",
     *  path="/api/v1//qualify/{id}",
     *  security={{ "bearerAuth": {} }},
     * * @OA\Parameter(
     *    name="id",
     *    in="path",
     *    description="Id",
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
  //** Guargar Calificación de la app por Usuario */
  public function createQualifyApp(Request $request, $id)
  {
    $qualify = QualifyApp::where('user_id', $id)->first();
    $fechaAsk = date( "Y-m-d", strtotime( date( "Y-m-d")." +".$request->value." ".$request->option ) );
    $result = ['error' => false, 'fecha' => $fechaAsk];
    if(empty($qualify)){
      $qualify = QualifyApp::create([
        'user_id' => $id,
        'action' => $request->name,
        'answer_date' => date('Y-m-d'),
        'date_ask_again' => $fechaAsk,
      ]);
    }else {
      $qualify->action = $request->name;
      $qualify->answer_date = date('Y-m-d');
      $qualify->date_ask_again = $fechaAsk; 
      $qualify->save();
    }
    
    return response()->json($result, 200);
  }
}