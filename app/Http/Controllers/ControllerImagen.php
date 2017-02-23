<?php
namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DB;
class ControllerImagen extends Controller
{
    public function ReadImagen(){
          return Imagen::all();
    }

    public function AddImagen(Request $request){
          $rule=[
            'id'=>'required|numeric',
            'ruta'=>'required'
            ];
        $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{//Busca si el usuario se encuentra registrado
             $service=service::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $addimagen=new Imagen();
                  $addimagen->ruta=$request->input('ruta');
                  $addimagen->user_id=$service->id;
                  if($addimagen->save()){
                       return response()->json('Add Ruta');
                   }
              }else{
                   return response()->json('Service not found ');
             }
        } 
    }

    public function UpdateImagen(Request $request){
         $rule=[
           'id' => 'required|numeric',
           'ruta'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $updateimagen=Imagen::where('id','=',$request->input('id'))->first();
             if($updateimagen!=null){
                  $updateimagen->ruta=$request->input('ruta');
                  if($updateimagen->save()){
                       return response()->json('Update ruta');
                   }
              }else{
                  return response()->json('Imagen not found ');
              }
       }
       }

        public function DeleteImagen(Request $request){
        $rule=[
            'id' => 'required|numeric'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $deleteimagen=Imagen::where('id','=',$request->input('id'))->first();
             if($deleteimagen!=null){
                  $deleteimagen->delete();
                  return response()->json('Imagen Delete');
              }else{
                 return response()->json('Imagen not found');
               }
           }
        }
}