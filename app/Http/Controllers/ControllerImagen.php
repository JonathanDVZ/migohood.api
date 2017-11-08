<?php
namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DB;
class ControllerImagen extends Controller
{
    public function GetImagen(Request $request){
           $rule=[
           'service_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $service=Service::where('id','=',$request->input('service_id'))->first();
            if(count($service)>0){
                 $imagen = DB::select('select * from image where service_id=?', [$service->id]);
               if(count($imagen)>0){
                    return response()->json($imagen);
               }else{
                    return response()->json("imagen not found");
               }
            }
            else{
                return response()->json("Service not found");
            }
        }
    }
//Agrega una imagen
    public function AddImagen(Request $request){
          $rule=[
            'service_id'=>'required|numeric|min:1',
            'ruta'=>'required'
            ];
        $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{//Busca si el usuario se encuentra registrado
             $service=Service::where('id','=',$request->input('service_id'))->first();
             $users = DB::table('image')->where('service_id','=',$service->id)->count();
             if($service!=null){
                 $imagen = DB::table('image')->where('service_id','=',$service->id)->count();
                 if($imagen<=10){
                  $addimagen=new Image();
                  $addimagen->ruta=$request->input('ruta');
                  $addimagen->service_id=$service->id;
                  $addimagen->description=$request->input('description');
                  if($addimagen->save()){
                       return response()->json('Add Imagen');
                   }
                 }else{
                      return response()->json('Imagen Limit!');
                 }
              }else{
                   return response()->json('Service not found ');
             }
        } 
    }

    public function UpdateImagen(Request $request){
           $rule=[
             'service_id' => 'required|numeric|min:1',
             'ruta'=>'required',
            // 'description'=>'required'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
          return response()->json($validator->errors()->all());
          }else{
            $service=Service::where('id','=',$request->input('service_id'))->first();
               $updateimagen=Image::where('service_id','=',$service->id)->where('id','=',$request->input('id'))->first();
               if($updateimagen!=null){
                    $updateimagen->ruta=$request->input('ruta');
                    $updateimagen->description=$request->input('description');
                    if($updateimagen->save()){
                         return response()->json('Update Imagen');
                     }
                }else{
                        
                     $users = DB::table('image')->where('service_id','=',$service->id)->count();
                     if($service!=null){
                         $imagen = DB::table('image')->where('service_id','=',$service->id)->count();
                         if($imagen<=2){
                          $addimagen=new Image();
                          $addimagen->ruta=$request->input('ruta');
                          $addimagen->service_id=$service->id;
                          $addimagen->description=$request->input('description');
                          if($addimagen->save()){
                               return response()->json('Add Imagen');
                           }
                         }else{
                              return response()->json('Imagen Limit!');
                         }
                      }else{
                           return response()->json('Service not found ');
                     }
                    return response()->json('Imagen not found ');
                }
         }
       }

        public function DeleteImagen(Request $request){
        $rule=[
            'imagen_id' => 'required|numeric'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $deleteimagen=Imagen::where('id','=',$request->input('imagen_id'))->first();
             if($deleteimagen!=null){
                  $deleteimagen->delete();
                  return response()->json('Imagen Delete');
              }else{
                 return response()->json('Imagen not found');
               }
           }
        }
        
        //Agregar un comentario a una imagen especifica
        public function AddDescriptionImagen(Request $request){
            $rule=[
            'ruta_id' => 'required|image',
            'service_id'=>'required|numeric|min:1',
            'description'=>'string'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $service=Imagen::where('service_id','=',$request->input("service_id"))->first();
             $descriptionimagen=Imagen::where('ruta','=',$request->input('ruta_id'))->first();
             if(count($service)>0){
                  if(count($descriptionimagen)>0){
                      $descriptionimagen->description=$request->input("description");
                      $descriptionimagen->save();
                      return response()->json('Add Description');
                  }else{
                      return response()->json('Imagen not found');
                  }
            }else{
                return response()->json('Imagen not found');
            }     
         }
        }
}