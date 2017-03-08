<?php
namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\User;
use App\Models\Category;
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
use DateTime;
use DB;
class ControllerService extends Controller
{
    //Muestra todo los service   
    public function ReadService(){
    //Se obtiene todos los servicios que se crean
    return Service::all();   
    }
   
    //Agrega Service
    /*public function CreateService(Request $request){   
         //regla de validacion
              $rule=[
                    'id'=>'required|numeric|min:1',
                  //  'date'=>'required|date_format:Y-m-d',
                    'category_id'=>'required|numeric|min:1',                  
                    'address'=>'required',
                    'title'=>"required|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45",
                    'duration_id'=>'numeric|required|min:1',
                    'zipcode'=>'required|numeric|min:1',
                    'accommodation_id'=>'required|numeric|max:2|min:1',
                    'city_id'=>'required|numeric|min:1',
                    'num_bedroom'=>'numeric|min:1',
                    'num_bathroom'=>'numeric|min:1',
                    'num_guest'=>'required|numeric|min:1'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
             return response()->json($validator->errors()->all());
             }
             else{
                  //Verifico si se encuentra registrado en usuario
                      $user = User::select()->where('id',$request->input("id"))->first();    
                      if(count($user)>0){
                          $newService=new Service();
                          $dt = new DateTime();
                          $newService->user_id=$user->id;
                          $newService->date=$dt->format('Y-m-d');
                          $newService->category_id=$request->input('category_id');
                          $newService->address=$request->input('address');
                          if($request->has('description'))
                          $newService->description=$request->input('description');
                          $newService->title=ucwords(strtolower($request->input('title')));
                          $newService->duration_id=$request->input('duration_id');
                          $newService->zipcode=$request->input('zipcode');
                          $newService->accommodation_id=$request->input('accommodation_id');
                          $newService->city_id=$request->input('city_id');
                          $newService->num_bedroom=$request->input('num_bedroom');
                          $newService->num_bathroom=$request->input('num_bathroom');
                          $newService->num_guest=$request->input('num_guest');
                      if($newService->save()){
                         return response()->json('Service Create');
                      }
                      }else{
                          return response()->json('User not Found');
                      }
                  }
    }*/

    //Crea el servicio asignando primero la catogoria
    public function CreateService(Request $request){
         //regla de validacion
             $rule=[
                  'user_id'=>'required|numeric|min:1',
                  'category_id'=>'required|numeric|min:1'
              ];
                $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
               }
             else{
                 //Busca si se encuentra un usuario registrado 
                 $user = User::select()->where('id',$request->input("user_id"))->first();
                 //Busca si se encuentra la category asignada 
                 $category = Category::select()->where('id',$request->input("category_id"))->first();
                 //Verifica si lo encontro
                 if(count($user)>0){
                    if(count($category)>0){
                      $newservice=new Service();
                      $dt = new DateTime();
                      $newservice->user_id=$user->id;
                      $newservice->date=$dt->format('Y-m-d'); 
                      $newservice->category_id=$category->name;
                      if($newservice->save()){
                         return response()->json('Add Service'); 
                      }  
                    }else{//Si no encuentra le genera un mensaje con el id de la category al que agrego
                        return response()->json(['Message'=>'Category not Found','Error'=>$Category->id]); 
                    } 
                 }else{//Si no encuentra le genera un mensaje con el id del usuario al que agrego
                    return response()->json(['Message'=>'User not Found','Error'=>$user->id]);
                 }   
             }
    }
     

    //Actualiza un Service
    public function UpdateService(Request $request){
            //Regla de validacion       
              $rule=[
                    'user_id'=>'required|numeric|min:1',
                    'category_id'=>'required|numeric',
                    'address'=>'required',
                    'title'=>'required|string',
                    'duration_id'=>'numeric|required|min:1',
                    'zipcode'=>'required|numeric',
                    'accommodation_id'=>'required|numeric|min:1',
                     'city_id'=>'required|numeric|min:1',
                    'num_bedroom'=>'numeric|min:1',
                    'num_bathroom'=>'numeric|min:1',
                    'num_guest'=>'required|numeric|min:1'
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
                            ['category_id' => $request->input('category_id'),
                            'address' => $request->input('address'),
                            'description' => $request->input('description'),
                            'title' => $request->input('title'),
                            'duration_id' => $request->input('duration_id'),
                            'zipcode' => $request->input('zipcode'),
                            'accommodation_id' => $request->input('accommodation_id'),
                            'city_id' => $request->input('city_id'),
                            'num_bedroom' => $request->input('num_bedroom'),
                            'num_bathroom' => $request->input('num_bathroom'),
                            'num_guest' => $request->input('num_guest')
                            ]);
                            return response()->json('Update Service');
                  }else{
                     return response()->json('Service not found');
                  }
            }
       }


    //Eliminar Service
    public function DeleteService(Request $request){
           $rule=[
                    'id'=>'required|numeric|min:1'
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
    public function AddTypeService(Request $request){
          $rule=['id_type'=>'required|numeric|min:1',
                 'id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $type=Type::select()->where('id_type',$request->input("id_type"))->first();        
                 $service=Service::select()->where('id',$request->input("id"))->first();
               
                 if(count($type)>0){
                         if(count($service)>0){
                              $val= DB::select('select * from service_type where service_id=? and type_id=?', [$service->id,$type->id_type]);
                                if(count($val)==0){
                                    $typenum=intval($type->category_id);
                                    $servicenum=intval($service->category_id);
                                    if (strcmp($typenum,$servicenum)==0){
                                      $typeservice=new Service_Type;
                                      $typeservice->service_id=$service->id;
                                      $typeservice->type_id=$type->id_type;
                                      if($typeservice->save()){
                                           return response()->json('Add Type');  
                                       }
                                     }else{
                                        return response()->json('Does not belong to category'); 
                                    }
                                }else{
                                     return response()->json('The type was already selected');
                                } 
                         }else{
                             return response()->json('Service not found'); 
                         }   
                 }else{
                       return response()->json('Type not found'); 
                 }
          }
    }
       
    //Muestra todos los type-service
    public function GetTypeService(request $request){
          $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $getrent = DB::table('service')->join('service_type','service.id', '=','service_type.service_id')
               ->join('type','type.id_type','=','service_type.type_id')
               ->select('service.title','service.date','type.name as name')
               ->where('service.user_id','=',$request->input("user_id"))
               ->get();
               if(count($getrent)>0){
                      return response()->json($getrent);
               }
               else{
                    return response()->json("Type not fount");
               }
        }
    } 
      
    //Elimina un service-type
    public function DeleteTypeService(request $request){
          $rule=[
                    'id'=>'required|numeric|min:1'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
             return response()->json($validator->errors()->all());
             }
             else{
                 $servicetype = Service_Type::select()->where('id',$request->input("id"))->first();
                 if($servicetype){
                    DB::table('service_type')->where('id',$servicetype->id)->delete();
                    return response()->json('Service-Type Delete');
                 }else{
                    return response()->json('Service-Type Not delete');   
                 }
              }   
    }     
          
     //Muestra un service con sus caracteristicas seleccionadas (category,duration,accommodation)
    public function GetUserService(Request $request){
        $rule=[
                    'user_id'=>'required|numeric|min:1'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
             return response()->json($validator->errors()->all());
             }
             else{
                 $getrent = DB::table('user')->join('service','user.id', '=','service.user_id')
                 ->join('category','service.category_id','=','category.id')
                 ->join('duration','service.duration_id','=','duration.id')
                 ->join('accommodation','service.accommodation_id','=','accommodation.id')
                 ->select('user.name as user','user.secondname','user.email','service.title as service','service.date','service.description','service.address','category.name as category','duration.type as duration','accommodation.name as accomodation')
                 ->where('user.id','=',$request->input("user_id"))
                 ->get();
                 if(count($getrent)>0){
                        return response()->json($getrent); 
                 }else{
                       return response()->json("Service not fount"); 
                 }
            }
    }   

    //Agrega a un service un dia de la semana ->tabla (service_calender)
    public function AddServiceCalendar(request $request){
          $rule=[
                 'id'=>'required|numeric|min:1',
                 'codigo_id'=>'required|numeric|min:1|max:8' 
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $calendar=Calendar::select()->where('codigo_id',$request->input("codigo_id"))->first();        
                 $service=Service::select()->where('id',$request->input("id"))->first();
                 $val= DB::select('select * from service_calendar where service_id = ? and calendar_id=?', [$request->input("id"),$request->input("codigo_id")]);
                 
                 if(count($service)>0){
                     if(count($val)==0){
                       $newcalendar=new Service_Calendar;
                       $newcalendar->service_id=$service->id;
                       $newcalendar->calendar_id=$calendar->codigo_id;
                       $newcalendar->save();
                       return response()->json('Add date to service');  
                     }else{
                         return response()->json('The amenite was already selected'); 
                     }
                    }else{
                        return response()->json('Service not found'); 
                    }

                }
    }

    //Muestra un service-Calendar en especifico
    public function ReadCalendarService(Request $request){
      $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
                    $getrent = DB::table('service')->join('service_calendar','service.id', '=','service_calendar.service_id')
               ->join('calendar','service_calendar.calendar_id','=','calendar.codigo_id')
               ->select('service.title','service.date','calendar.day')
               ->where('service.user_id','=',$request->input("user_id"))
               ->get();
               if(count($getrent)>0){
                     return response()->json($getrent); 
               }else{
                   return response()->json('Service_Calendar not found'); 
               }
           
        }
    }

    //Elimina Service-Calender
    public function DeleteServiceCalendar(Request $request){
          $rule=[
                    'codigo'=>'required|numeric|min:1'
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
                
               if(count($getrent)>0){
                      return response()->json($getrent);
               }else{
                   return response()->json("Amenite-Service not found");
               }

              }   
    }

    //Agrega a un service amenities nota:solo category 1 y 2 tienen amenites 
    public function AddServiceAmentines(Request $request){
        $rule=['codigo'=>'required|numeric|min:1',
                 'id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $amenite=Amenite::select()->where('codigo',$request->input("codigo"))->first();        
                 if(count($amenite)>0){
                 $service=Service::select()->where('id',$request->input("id"))->first();
                 $val= DB::select('select * from service_amenites where service_id = ? and amenite_id=?', [$request->input("id"),$request->input("codigo")]);
                 if(count($val)==0){
                    if(count($service)>0){
                        $amenitenum=intval($amenite->category_id);
                        $servicenum=intval($service->category_id);
                        if (strcmp($amenitenum,$servicenum)==0){
                            $newserviceame=new Service_Amenite;
                            $newserviceame->service_id=$service->id;
                            $newserviceame->amenite_id=$amenite->codigo;
                            $newserviceame->save();
                            return response()->json('Add Amenitie Service');  
                       }else{
                           return response()->json('Does not belong to category'); 
                       }
                  }else{
                    return response()->json('Service not found'); 
                }}else{
                    if(count($val)==1){
                   return response()->json('The amenite was already selected'); 
                }
             }
             }else{
                  return response()->json('Amenite not found'); 
             }
         } 
    }

    //Muestra la tabla  Service-Amenites
    public function ReadServiceAmenite(Request $request){
       $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
               $getrent = DB::table('service')->join('service_amenites','service.id', '=','service_amenites.service_id')
               ->join('amenities','service_amenites.amenite_id','=','amenities.codigo')
               ->select('service.title','service.date','amenities.name as amenities')
               ->where('service.user_id','=',$request->input("user_id"))
               ->get();
               if(count($getrent)>0){
                      return response()->json($getrent);
               }else{
                   return response()->json("Amenite-Service not found");
               }
              
        }
    }
     
    //Elimina tabla  Service-Amenites
    public function DeleteServiceAmenite(request $request){
             $rule=[
                    'id'=>'required|numeric|min:1'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
             }
             else{
                 $seramenite = Service_Amenite::select()->where('id',$request->input("id"))->first();
                 if(count($seramenite)>0){
                    DB::table('service_amenites')->where('id',$seramenite->id)->delete();
                    return response()->json('Service-Amenitie Delete');
                 }else{
                    return response()->json('Service-Amenitie Not delete');   
                 }
              }   
    }

    Public function ReadServiceUser(Request $request){
            $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
              $service = DB::select('select * from service where user_id=?', [$request->input("user_id")]);
              if(count($service)>0){
                  return response()->json($service);
              }else{
                  return response()->json("service not found");
              }
              
        }
    }  
}

    
