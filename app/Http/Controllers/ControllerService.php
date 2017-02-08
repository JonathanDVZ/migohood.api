<?php
namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\User;
use App\Models\Service_Type;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DB;
class ControllerService extends Controller
{
         public function ReadService(){
             //Se obtiene todos los servicios que se crean
             return Service::all();   
         }

         public function CreateService(Request $request)
         {   
         //regla de validacion
              $rule=[
                    'name'=>"required|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45",
                    'id'=>'required|numeric',
                    'date'=>'required',
                    'category_id'=>'required|numeric|max:4|min:1',                  
                    'address'=>'required',
                    'title'=>"required|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45",
                    'duration_id'=>'numeric|required|max:4|min:1',
                    'zipcode'=>'required|numeric',
                    'accommodation_id'=>'required|numeric|max:2|min:1'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
             return response()->json($validator->errors()->all());
             }
             else{
                  try{ //Verifico si se encuentra registrado en usuario
                      $date=User::where('id','=',$request->input('id'))->first();
                      if($date!=null){
                          $newService=new Service();
                          $newService->name=ucwords(strtolower($request->input('name')));
                          $newService->user_id=$date->id;
                          $newService->date=$request->input('date');
                          $newService->category_id=$request->input('category_id');
                          $newService->address=$request->input('address');
                          if($request->has('description'))
                          $newService->description=$request->input('description');
                          $newService->title=ucwords(strtolower($request->input('title')));
                          $newService->duration_id=$request->input('duration_id');
                          $newService->zipcode=$request->input('zipcode');
                          $newService->accommodation_id=$request->input('accommodation_id');
                      if($newService->save()){
                         return response()->json('Service Create');
                      }
                      }else{
                          return response()->json('User not Found');
                      }
                  }catch(exception $e){ 
                         return response()->json('Service not Create');
                      }
                  
                }
            }

            //Actualiza un servicio
     public function UpdateService(Request $request){
            //Regla de validacion       
              $rule=[
                    'name'=>'required|regex:/^[a-zA-Z_áéíóúàèìòùñ\s]*$/|max:45',
                    'date'=>'required|date_format:Y-m-d',
                    'user_id'=>'required|numeric',
                    'category_id'=>'required|numeric',
                    'address'=>'required',
                    'title'=>'required',
                    'duration_id'=>'numeric|required',
                    'zipcode'=>'required|numeric',
                    'accommodation_id'=>'required|numeric'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
               }
             else{
             // Buscamos el usuario que posea el id ingresado, una vez que se halle entra a la condición
                 $service = Service::select()->where('user_id',$request->input("user_id"))->first();
                 if (count($service)>0){
            
                        DB::table('service')->where('id',$service->id)->update(
                            ['name' => ucwords(strtolower($request->input('name'))),
                            'date' => $request->input('date'),
                            'category_id' => $request->input('category_id'),
                            'address' => $request->input('address'),
                            'description' => $request->input('description'),
                            'title' => $request->input('title'),
                            'duration_id' => $request->input('duration_id'),
                            'zipcode' => $request->input('zipcode'),
                            'accommodation_id' => $request->input('accommodation_id')]);
                            return response()->json('Update Service');
                  }else{
                     return response()->json('Not Service');
                  }
            }
 }


     public function DeleteService(Request $request)
    {
           $rule=[
                    'id'=>'required|numeric'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
             return response()->json($validator->errors()->all());
             }
             else{
                 $service = Service::select()->where('id',$request->input("id"))->first();
                 if($service){
                    DB::table('service')->where('id',$service->id)->delete();
                    return response()->json('Service Delete');
                 }else{
                    return response()->json('Service Not delete');   
                 }
              }        
    }
     
     //Se agrega los tipos de la clase category que selecciono previamente el usuario
     public function AddTypeService(request $request){
          $rule=['id_type'=>'required|min:1|max:20'

          ];

          $type=Type::select()->where('id_type',$request->input("id_type"))->first();
          //$typeservice=Service_Type::select()->where('id_type',$request->input("id_type"))->first();         
          $service=Service::select()->where('id',$request->input("id"))->first();
           if(count($service)>0){
             $typenum=intval($type->category_id);
             $servicenum=intval($service->category_id);
                  if (strcmp($typenum,$servicenum)==0){
                      $typeservice=new Service_Type;
                      $typeservice->service_id=$service->id;
                      $typeservice->type_id=$type->id_type;
                      $typeservice->save();
                      return response()->json('Add Type');  
                    }else{
                      return response()->json('Does not belong to category'); 
                    }
            }else{
                return response()->json('Service not found'); 
            }
       }

      public function ReadTypeService(request $request){
          return Service_type::all();
      } 
          
          

     

     public function GetUserService(){
       $getrent = DB::table('user')->join('service','user.id', '=','service.user_id')
       ->join('category','service.category_id','=','category.id')
       ->join('duration','service.duration_id','=','duration.id')
       ->join('accommodation','accommodation_id','=','accommodation.id')
       ->select('user.name as user','user.secondname','user.email','service.name as service','service.date','service.description','service.address','category.name as category','duration.type as duration','accommodation.name as accomodation')
       ->get();
       if(count($getrent)>0){
            return response()->json($getrent); 
       }else{
           return response()->json("Rent not fount"); 
       }
    }
}