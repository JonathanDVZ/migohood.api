<?php
namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\User;
use App\Models\Amenite;
use App\Models\Service_Amenite;
use App\Models\Service_Type;
use App\Models\Type;
use App\Models\Service_Calendar;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DB;
class ControllerService extends Controller
{
    //Muestra todo los service   
    public function ReadService(){
    //Se obtiene todos los servicios que se crean
    return Service::all();   
    }
   
    //Agrega Service
    public function CreateService(Request $request){   
         //regla de validacion
              $rule=[
                    'name'=>"required|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45",
                    'id'=>'required|numeric|min_1',
                    'date'=>'required',
                    'category_id'=>'required|numeric|max:4|min:1',                  
                    'address'=>'required',
                    'title'=>"required|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45",
                    'duration_id'=>'numeric|required|max:4|min:1',
                    'zipcode'=>'required|numeric|min:1',
                    'accommodation_id'=>'required|numeric|max:2|min:1'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
             return response()->json($validator->errors()->all());
             }
             else{
                  try{ //Verifico si se encuentra registrado en usuario
                      $user=User::where('id','=',$request->input('id'))->first();
                      if(count($user)>0){
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

    //Actualiza un Service
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

    //Eliminar Service
    public function DeleteService(Request $request){
           $rule=[
                    'id'=>'required|numeric'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
             return response()->json($validator->errors()->all());
             }
             else{
                 $service = Service::select()->where('id',$request->input("id"))->first();
                 if(count($service)>0){
                    DB::table('service')->where('id',$service->id)->delete();
                    return response()->json('Service Delete');
                 }else{
                    return response()->json('Service Not delete');   
                 }
              }        
    }
     
     //Se agrega los tipos de la clase category que selecciono previamente el usuario en el servicio
    public function AddTypeService(request $request){
          $rule=['id_type'=>'required|numeric|min:1|max:20',
                 'id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $type=Type::select()->where('id_type',$request->input("id_type"))->first();        
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
    }
       
    //Muestra todos los type-service
    public function GetTypeService(request $request){
         $rule=[
           'type_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $servtype=Service_Type::where('id','=',$request->input('type_id'))->first();
            if(count($servtype)>0){
              return response()->json($servtype);
            }
            else{
                return response()->json("Service-Type not found");
            }
        }
    } 
      
    //Elimina un service-type
    public function DeleteTypeService(request $request){
          $rule=[
                    'id'=>'required|numeric'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
             return response()->json($validator->errors()->all());
             }
             else{
                 $servicetype = Service_Type::select()->where('id',$request->input("id"))->first();
                 if($servicetype){
                    DB::table('service-type')->where('id',$servicetype->id)->delete();
                    return response()->json('Service-Type Delete');
                 }else{
                    return response()->json('Service-Type Not delete');   
                 }
              }   
    }     
          
     //Muestra un service con sus caracteristicas seleccionadas (category,duration,accommodation)
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

    //Agrega a un service un dia de la semana ->tabla (service_calender)
    public function AddServiceCalendar(request $request){
          $rule=[
                 'id'=>'required|numeric|min:1',
                 'codigo_id'=>'required|numeric|min:1|max:7' 
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $calendar=Calendar::select()->where('codigo_id',$request->input("codigo_id"))->first();        
                 $service=Service::select()->where('id',$request->input("id"))->first();
                 if(count($service)>0){
                       $newcalendar=new Service_Calendar;
                       $newcalendar->service_id=$service->id;
                       $newcalendar->calendar_id=$calendar->codigo_id;
                       $newcalendar->save();
                       return response()->json('Added date to service');  
                    }else{
                        return response()->json('Service not found'); 
                    }
                }
    }

    //Muestra un service-Calendar en especifico
    public function ReadCalendarService(Request $request){
            $rule=[
           'cod' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $servcal=Service_Calendar::where('codigo','=',$request->input('cod'))->first();
            if(count($servcal)>0){
              return response()->json($servcal);
            }
            else{
                return response()->json("Service-Calendar not found");
            }
        }
    }

    //Elimina Service-Calender
    public function DeleteServiceCalendar(Request $request){
          $rule=[
                    'id'=>'required|numeric'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
             }
             else{
                 $servlendar = Service_Calendar::select()->where('codigo',$request->input("codigo"))->first();
                 if(count($servlendar)>0){
                    DB::table('service_calendar')->where('codigo',$servlendar->codigo)->delete();
                    return response()->json('Service-Calendar Delete');
                 }else{
                    return response()->json('Service-Calendar Not delete');   
                 }
              }   
    }

    //Agrega a un service amenites nota:solo category 1 y 2 tienen amenites 
    public function AddServiceAmentines(Request $request){
        $rule=['codigo'=>'required|numeric|min:1|max:15',
                 'id'=>'numeric'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $amenite=Amenite::select()->where('codigo',$request->input("codigo"))->first();        
                 $service=Service::select()->where('id',$request->input("id"))->first();
                 if(count($service)>0){
                    $amenitenum=intval($amenite->category_id);
                    $servicenum=intval($service->category_id);
                    if (strcmp($amenitenum,$servicenum)==0){
                       $newserviceame=new Service_Amenite;
                       $newserviceame->service_id=$service->id;
                       $newserviceame->amenite_id=$amenite->codigo;
                       $newserviceame->save();
                       return response()->json('Add Amenite Service');  
                    }else{
                      return response()->json('Does not belong to category'); 
                    }
                }else{
                    return response()->json('Service not found'); 
                }
             } 
    }

    //Muestrala tabla  Service-Amenites
    public function ReadServiceAmenite(Request $request){
       $rule=[
           'id_serv_amet' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $servam=Service_Amenite::where('id','=',$request->input('id_serv_amet'))->first();
            if(count($servam)>0){
              return response()->json($servam);
            }
            else{
                return response()->json("Service-Amenite not found");
            }
        }
    }
     
    //Elimina tabla  Service-Amenites
    public function DeleteServiceAmenite(request $request){
             $rule=[
                    'id'=>'numeric|min:1'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
             }
             else{
                 $seramenite = Service_Amenite::select()->where('id',$request->input("id"))->first();
                 if(count($seramenite)>0){
                    DB::table('service_amenites')->where('id',$seramenite->id)->delete();
                    return response()->json('Service-Amenite Delete');
                 }else{
                    return response()->json('Service-Amenite Not delete');   
                 }
              }   
    }  
}

    
