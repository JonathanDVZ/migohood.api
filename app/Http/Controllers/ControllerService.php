<?php
namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\User;
use App\Models\Amenite;
use App\Models\Accommodation;
use App\Models\Bedroom;
use App\Models\State;
use App\Models\Check_in;
use App\Models\Bedroom_Bed;
use App\Models\Check_out;
use App\Models\City;
use App\Models\Service_Rules;
use App\Models\Service_Reservation;
use App\Models\Service_Description;
use App\Models\Category;
use App\Models\SpecialDate;
use App\Models\Service_Cancellation;
use App\Models\Service_Amenite;
use App\Models\Service_Type;
use App\Models\Type;
use App\Models\Payment;
use App\Models\Service_Calendar;
use App\Models\Calendar;
use App\Models\Price_History;
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
   
    //Agreg New Step 1 
    public function AddNewStep(Request $request){
             //Regla de validacion       
              $rule=[
                    'user_id'=>'required|numeric|min:1',
                    'accommodation_id'=>'required|numeric|min:1',
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
               }
             else{
                 //Busca el usuario
                 $user = User::select()->where('id',$request->input("user_id"))->first(); 
                 $accommodation = Accommodation::where('id',$request->input("accommodation_id"))->first(); 
                 if(count($user)>0){///Verifica el usuario
                 if(count($accommodation)>0){
                       $newservice=new Service();
                       $dt = new DateTime();
                       $newservice->user_id=$user->id;
                       $newservice->date=$dt->format('Y-m-d (H:m:s)');
                       $newservice->category_id=1;
                       $newservice->accommodation_id=$request->input("accommodation_id");
                       $newservice->save();
                       if($newservice->save()){
                              return response()->json($newservice);
                       }
                 }else{
                        return response()->json('Accommodation not found');                 
                 }  
                }else{
                    return response()->json('User not found');  
                }
            } 
    }
     
   
     //Agrega New Step-1
    public function AddNewStep1(Request $request){
          $rule=['type_id'=>'required|numeric|min:1',
                 'service_id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $type=Type::select()->where('id_type',$request->input("type_id"))->first();        
                 $service=Service::select()->where('id',$request->input("service_id"))->first();
               
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
                                           return response()->json('Add Step 2');  
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
   
   //Agrega New Step 2
    public function AddNewStep2(Request $request){
        //Reglas de validacion
        $rule=[  'service_id'=>'required|numeric|min:1',
                 'num_guests'=>'required|numeric|min:0',
                 'num_bedroom'=>'required|numeric|min:0'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::select()->where('id',$request->input("service_id"))->first();
                 $val= DB::select('select * from bedroom where service_id = ?', [$service->id]); 
                if(count($service)>0){
                    if(count($val)==0){
                     $service->num_guest=$request->input("num_guests");
                     $service->save();
                     for($i=1;$i<=$request->input("num_bedroom");$i++){
                           $bedroom=new Bedroom;
                           $bedroom->service_id=$service->id; 
                           $bedroom->save();
                    }
                     return response()->json('Add Step2');  
                    }else{
                         return response()->json('The service already has selected rooms');
                     }  
                      
                }else{
                     return response()->json('Service not found'); 
                }

            }
    }  

    
    //Agrega New Step 3
    public function AddNewStep3(Request $request){
         $rule=[  'service_id'=>'required|numeric|min:1',
              'num_bathroom'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::select()->where('id',$request->input("service_id"))->first();
                if(count($service)>0){
                    $service->num_bathroom=$request->input("num_bathroom");
                    if($service->save()){
                        return response()->json('Add Step 3'); 
                    }
                }else{
                    return response()->json('Service not found'); 
                }
            }
    }


   //Agrega New Step 4
    public function AddNewStep4(Request $request){
        $rule=[  'service_id'=>'required|numeric|min:1',
              'city_id'=>'numeric|min:1',
               'zipcode'=>'numeric|min:1'   
         ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                $city=City::where('id',$request->input("city_id"))->first();
                if(count($service)>0){
                    if(count($city)>0){
                     try{   
                        $service->city_id=$request->input("city_id");
                        $service->zipcode=$request->input("zipcode");
                        $service->save();
                        $des_address1=new Service_Description;
                        $des_address1->service_id=$service->id;
                        $des_address1->description_id=2;
                        $des_address1->content=$request->input("address1");
                        $des_address1->save();
                        $des_address2=new Service_Description;
                        $des_address2->service_id=$service->id;
                        $des_address2->description_id=3;
                        $des_address2->content=$request->input("address2");
                        $des_address2->save();
                        $des_neighborhood=new Service_Description;
                        $des_neighborhood->service_id=$service->id;
                        $des_neighborhood->description_id=4;
                        $des_neighborhood->content=$request->input("des_neighborhood");
                        $des_neighborhood->save();
                        $desc_surroundings=new Service_Description;
                        $desc_surroundings->service_id=$service->id;
                        $desc_surroundings->description_id=5;
                        $desc_surroundings->content=$request->input("desc_surroundings");
                        $desc_surroundings->save();
                        $desc_length=new Service_Description;
                        $desc_length->service_id=$service->id;
                        $desc_length->description_id=6;
                        $desc_length->content=$request->input("desc_length");
                        $desc_length->save();
                        $desc_latitude=new Service_Description;
                        $desc_latitude->service_id=$service->id;
                        $desc_latitude->description_id=7;
                        $desc_latitude->content=$request->input("desc_latitud");
                        $desc_latitude->save();
                        return response()->json('Add Step 4');
                     }catch(Exception $e){
                         return response()->json($e);
                     }
                    }else{
                        return response()->json('City not found');
                    }
                }else{
                    return response()->json('Service not found');
                }        
            }
    }

    //Agrega(step5) a un service amenities nota:solo category 1 y 2 tienen amenities 
    public function AddNewStep5(Request $request){
        $rule=['codigo'=>'required|numeric|min:1',
                 'id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $amenite=Amenite::where('category_id','=',1)->where('codigo',$request->input("codigo"))->first();        
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
                            return response()->json('Add Step 5');  
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
 
    //Agrega step6 movil
    public function AddNewStep6(Request $request){
                $rule=[  'service_id'=>'required|numeric|min:1',
                'politic_payment'=>'required|numeric:1|min:1|max:3'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                $payment=Payment::where('id',$request->input("politic_payment"))->first();
                if(count($service)>0 and count($payment)>0){      
                      $newhistory=new Price_History;
                      $dt = new DateTime();
                      $newhistory->starDate=$dt->format('Y-m-d (H:i:s)');
                      $newhistory->service_id=$service->id;
                      $newhistory->price=$request->input("price");
                      $newhistory->currency_id=$request->input("currency_id");
                      $newhistory->duration_id=$request->input("duration_id");
                      $newhistory->save();
                      $service->payment_id=$request->input("politic_payment");
                      $service->save() ;
                      return response()->json($newhistory);
                   }else{
                      return response()->json('Service or Payment not found');
                   }
                }

        
    }

    //Agrega titulo
    public function AddNewTitle(Request $request){
        $rule=[  'service_id'=>'required|numeric|min:1',
              'des_title'=>'required'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                if(count($service)>0){
                      $des_title=new Service_Description;
                      $des_title->service_id=$service->id;
                      $des_title->description_id=1;
                      $des_title->content=$request->input("des_title");
                      $des_title->save();
                      return response()->json('Add title');  
                }else{
                     return response()->json('Service not found');   
                }
            }    
      }
     
     //Agrega  description de servicio
     public function AddNewDescription(Request $request){
        $rule=[  'service_id'=>'required|numeric|min:1',
              'description'=>'required'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                if(count($service)>0){
                      $des_title=new Service_Description;
                      $des_title->service_id=$service->id;
                      $des_title->description_id=8;
                      $des_title->content=$request->input("description");
                      $des_title->save();
                      return response()->json('Add Description');  
                }else{
                     return response()->json('Service not found');   
                }
           }   
     }

    //Agregar reglas de casa
    public function AddNewRulesHouse(Request $request){
        $rule=[  'service_id'=>'required|numeric|min:1',
              'AptoDe2a12'=>'boolean',
              'AptoDe0a2'=>'boolean',
              'SeadmitenMascotas'=>'boolean',
              'PremitidoFumar'=>'boolean',
              'Eventos'=>'boolean'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                if(count($service)>0){
                    $newrules=new Service_Rules;
                    $newrules->service_id=$service->id;
                    $newrules->rules_id=1;
                    $newrules->check=$request->input("AptoDe2a12");
                    $newrules->save();
                    $newrules=new Service_Rules;
                    $newrules->service_id=$service->id;
                    $newrules->rules_id=2;
                    $newrules->check=$request->input("AptoDe0a2");
                    $newrules->save();
                    $newrules=new Service_Rules;
                    $newrules->service_id=$service->id;
                    $newrules->rules_id=3;
                    $newrules->check=$request->input("SeadmitenMascotas");
                    $newrules->save();
                    $newrules=new Service_Rules;
                    $newrules->service_id=$service->id;
                    $newrules->rules_id=4;
                    $newrules->check=$request->input("PremitidoFumar");
                    $newrules->save();
                    $newrules=new Service_Rules;
                    $newrules->service_id=$service->id;
                    $newrules->rules_id=5;
                    $newrules->check=$request->input("Eventos");
                    $newrules->save();
                    return response()->json('Add Rules'); 
               }else{
                    return response()->json('Service not found'); 
                }
            }
    } 

    //Agregar Check_in y check_out
    public function AddNewCheckInCheckOut(Request $request){
          $rule=[  'service_id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                if(count($service)>0){
                   try{
                      $newcheck_in=new Check_in;
                      $newcheck_in->time_entry=$request->input("time_entry");
                      $newcheck_in->until=$request->input("until");
                      $newcheck_in->service_id=$service->id;
                      $newcheck_in->save();
                      $newcheck_out=new Check_out;
                      $newcheck_out->departure_time=$request->input("departure_time");
                      $newcheck_out->service_id=$service->id;
                      $newcheck_out->save();
                      return response()->json('Add Check_In and Check_Out'); 
                    }catch(exception $e){
                       return response()->json($e); 
                    }
                }else{
                    return response()->json('Service not found'); 
                }
            }
     }
    
   //Agregar Prefernacia de reservacion 
   public function AddNewReservationPrerence(Request $request){
         $rule=[  'service_id'=>'required|numeric|min:1',
              'accommodation_id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                $service=Service::where('id','=',$request->input("service_id"))->first();
                if(count($service)>0){
                  try{
                    $newreservation=new Service_Reservation;
                    $newreservation->service_id=$service->id;
                    $newreservation->preference_id=1;
                    $newreservation->check=$request->input("compliant_guests");
                    $newreservation->save();
                    $newreservation=new Service_Reservation;
                    $newreservation->service_id=$service->id;
                    $newreservation->preference_id=2;
                    $newreservation->check=$request->input("send_request");
                    $newreservation->save();
                    $newreservation=new Service_Reservation;
                    $newreservation->service_id=$service->id;
                    $newreservation->preference_id=3;
                    $newreservation->check=$request->input("phone_and_email");
                    $newreservation->save();
                    $newreservation=new Service_Reservation;
                    $newreservation->service_id=$service->id;
                    $newreservation->preference_id=4;
                    $newreservation->check=$request->input("thumbnail");
                    $newreservation->save(); 
                    $newreservation=new Service_Reservation;
                    $newreservation->service_id=$service->id;
                    $newreservation->preference_id=5;
                    $newreservation->check=$request->input("data_payment");
                    $newreservation->save();
                    $newreservation=new Service_Reservation;
                    $newreservation->service_id=$service->id;
                    $newreservation->preference_id=6;
                    $newreservation->check=$request->input("id");
                    $newreservation->save();
                    $newreservation=new Service_Reservation;
                    $newreservation->service_id=$service->id;
                    $newreservation->preference_id=7;
                    $newreservation->check=$request->input("positive_valuation");
                    $newreservation->save();       
                    return response()->json('Add Preference Reservation');             
                  }catch(Exception $e){
                      return response()->json($e); 
                  }                 
                }else{
                     return response()->json('Service not found'); 
                }
    }
   }

   //Agregar accommodation
    public function AddAccommodation(Request $request){
        $rule=[  'service_id'=>'required|numeric|min:1',
              'accommodation_id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id','=',$request->input("service_id"))->first();
                $accommodation=Accommodation::where('id','=',$request->input("accommodation_id"))->first();
                if(count($service)>0){
                    if(count($accommodation)>0){
                     DB::table('service')->where('id',$service->id)->update(
                            ['accommodation_id'=>$request->input("accommodation_id")
                            ]);
                         return response()->json('Add Accommodation'); 
                    }else{
                        return response()->json('Accommodation not found');
                    }    
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

   //Agregar Service(space-step1)-Web
    public function AddNewSpaceStep1(Request $request){
        $rule=[
            // Comente esto, ya que aun no poseo ningun id service
            //'service_id' => 'required|numeric|min:1',
            'type_id'=>'required|numeric|min:1',
            'accommodation_id'=>'required|numeric|min:1',
            'live'=>'required|boolean',
            'user_id'=>'required|numeric|min:1'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        } else {
            $user = User::select()->where('id',$request->input("user_id"))->first(); 
            $category=Category::select()->where('id',1)->first();  
            $type=Type::select()->where('category_id','=',$category->id)->where('id_type','=',$request->input("type_id"))->first();  
            $accommodation=Accommodation::select()->where('id',$request->input("accommodation_id"))->first(); 
            if(count($user)>0){
                if(count($category)>0){
                    if(count($type)>0){
                        if(count($accommodation)>0){
                          try{
                            $dt = new DateTime();
                            $newspace=new Service;
                            $newspace->user_id=$user->id;
                            $newspace->date=$dt->format('Y-m-d');
                            $newspace->category_id=$category->id;
                            $newspace->accommodation_id = $accommodation->id;
                            $newspace->live=$request->input("live");
                            $newspace->save();
                            $newtype=new Service_Type;
                            $newtype->service_id = $newspace->id;
                            $newtype->type_id = $type->id_type;
                            $newtype->save();
                            // Permito que me devuelva el arreglo, ya que necesito el id del servicio creado
                            //return response()->json('Add Service');
                            return response()->json($newspace);
                          }catch(exception $e){
                              return response()->json($e);
                          }

                        }else{
                          return response()->json('Accommodation not found');
                        }
                            
                    }else{
                       return response()->json('Type not found');
                    }
                    
                }else{
                   return response()->json('Category not found'); 
                }
              
            }else{
                return response()->json('User not found'); 
            }
        }
    }

    //Agregar Service(space-step2)-Web
    public function AddNewSpaceStep2(Request $request){
        $rule=[
           'service_id' => 'required|numeric|min:1',
           'num_guest'=>'required|numeric|min:0'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        } else {
            $servicespace=Service::select()->where('id',$request->input("service_id"))->first();
            // Habia un error, la variable se llama servicespace, no service
            if(count($servicespace)>0){
                try{ 
                    $servicespace->num_guest=$request->input("num_guest");
                    $servicespace->save();
                    for($i=1;$i<=$request->input("num_bedroom");$i++){
                        $bedroom=new Bedroom;
                        // Habia un error, la variable se llama servicespace, no service
                        $bedroom->service_id=$servicespace->id; 
                        $bedroom->save();
                    }
                    /** 
                    *   Envio como respuesta el servicio junto con el numero de habitaciones,
                    *   ya que para la vista siguiente son necesarios dichos datos
                    */
                    return response()->json("Add Space Bedroom"); 
                    //$servicespace->num_bedrooms = $request->input("num_bedroom");
                    //return response()->json($servicespace);  
                 } catch(Exception $e) {
                     return response()->json($e); 
                 }  
            } else {
                return response()->json('Service not found'); 
            }
        }
      
   } 

   //Agregar Service(space-step2-beds)-Web
   public function AddNewSpaceStep2Beds(Request $request){
         $rule=[
           'service_id' => 'required|numeric|min:1',
           'bedroom_id'=>'required|numeric|min:0',
           'double_bed'=>'numeric|min:0',
           'queen_bed'=>'numeric|min:0',
           'individual_bed'=>'numeric|min:0',
           'sofa_bed'=>'numeric|min:0',
           'other_bed'=>'numeric|min:0|max:1'
           
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $servicespace=Service::select()->where('id',$request->input("service_id"))->first();
            if(count($servicespace)>0){
              $bedroom=Bedroom::select()->where('service_id','=',$servicespace->id)->where('id','=',$request->input("bedroom_id"))->first();
              if(count($bedroom)>0){
                 $newbedroomdouble=new Bedroom_Bed;
                 $newbedroomdouble->bedroom_id=$bedroom->id;
                 $newbedroomdouble->bed_id=1;
                 $newbedroomdouble->quantity=$request->input("double_bed");
                 $newbedroomdouble->save();
                 $newbedroomqueen=new Bedroom_Bed;
                 $newbedroomqueen->bedroom_id=$bedroom->id;
                 $newbedroomqueen->bed_id=2;
                 $newbedroomqueen->quantity=$request->input("queen_bed");
                 $newbedroomqueen->save();
                 $newbedroomindividual=new Bedroom_Bed;
                 $newbedroomindividual->bedroom_id=$bedroom->id;
                 $newbedroomindividual->bed_id=3;
                 $newbedroomindividual->quantity=$request->input("individual_bed");
                 $newbedroomindividual->save();
                 $newbedroomsofa=new Bedroom_Bed;
                 $newbedroomsofa->bedroom_id=$bedroom->id;
                 $newbedroomsofa->bed_id=4;
                 $newbedroomsofa->quantity=$request->input("sofa_bed");
                 $newbedroomsofa->save();
                 $newbedroomother=new Bedroom_Bed;
                 $newbedroomother->bedroom_id=$bedroom->id;
                 $newbedroomother->bed_id=5;
                 $newbedroomother->quantity=$request->input("other_bed");
                 $newbedroomother->save();
              }else{
                    return response()->json('Bedroom not found'); 
              }
            }else{
                   return response()->json('Service not found');
            }
        }
     }
      
    //Agregar Service(space-step4)-Web 
     public function AddNewSpaceStep4Location(Request $request){
            $rule=[
           'service_id' => 'required|numeric|min:1',
           'country_id'=>'numeric|min:1',
           'city_id'=>'numeric|min:1',
           'zipcode'=>'numeric|min:0',
           'state_id'=>'numeric|min:1',

      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $servicespace=Service::select()->where('id',$request->input("service_id"))->first();
            $country=Country::select()->where('id',$request->input("country_id"))->first();
            $state=State::select()->where('country_id',$country->id)->where('id',$request->input("state_id"))->first();
            $city=City::select()->where('state_id','=',$state->id)->where('id',$request->input("city_id"))->first();
            if(count($servicespace)>0){
                if(count($city)>0){
                    if(count($state)>0){
                        if(count($country)>0){
                            try{
                                $servicespace->city_id=$request->input("city_id");
                                $servicespace->save();
                                $des_address1=new Service_Description;
                                $des_address1->service_id=$servicespace->id;
                                $des_address1->description_id=2;
                                $des_address1->content=$request->input("address1");
                                $des_address1->save();
                                $des_apt=new Service_Description;
                                $des_apt->service_id=$servicespace->id;
                                $des_apt->description_id=9;
                                $des_apt->content=$request->input("apt");
                                $des_apt->save();
                                $servicespace->zipcode=$request->input("zipcode");
                                $servicespace->save();
                                $des_neighborhood=new Service_Description;
                                $des_neighborhood->service_id=$servicespace->id;
                                $des_neighborhood->description_id=4;
                                $des_neighborhood->content=$request->input("des_neighborhood");
                                $des_neighborhood->save();
                                $des_around=new Service_Description;
                                $des_around->service_id=$servicespace->id;
                                $des_around->description_id=10;
                                $des_around->content=$request->input("des_around");
                                $des_around->save();
                                return response()->json('Add Location');
                            }catch(Exception $e){
                                return response()->json($e);
                            }     
                        }else{
                           return response()->json('Country not found');     
                        }

                    }else{
                         return response()->json('State not found');  
                    }   
                }else{
                     return response()->json('City not found');  
                }

            }else{
                return response()->json('Service not found');   
            }

        }
     
     }
    
       
    //Agregar Service(space-step5)-Web
    public function AddNewSpaceStep5Amenities(Request $request){
            $rule=[
           'service_id' => 'required|numeric|min:1',
           'select_amenitie'=>'required|numeric|min:1',
           'select_offer'=>'required|numeric|min:1',
           'select_guests'=>'required|numeric|min:1'
           ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $servicespace=Service::where('id',$request->input("service_id"))->first();
           if(count($servicespace)>0){
               try{
                $service_amenities=new Service_Amenite;
                $service_amenities->service_id=$servicespace->id;
                $service_amenities->amenite_id=$request->input("select_amenitie");
                $service_amenities->save();
                $service_amenities_offer=new Service_Amenite;
                $service_amenities_offer->service_id=$servicespace->id;
                $service_amenities_offer->amenite_id=$request->input("select_offer");
                $service_amenities_offer->save();
                $service_amenities_guests=new Service_Amenite;
                $service_amenities_guests->service_id=$servicespace->id;
                $service_amenities_guests->amenite_id=$request->input("select_guests");
                $service_amenities_guests->save();
                return response()->json("Add Amenities");
               }catch(Exception $e){
                   return response()->json($e);
               }
            }else{
                 return response()->json("Service not found");
            }
         }
   }
   
        
    //Agregar Service(space-step6)-Web
    public function AddNewSpaceStep6(Request $request){
        $rule=[  'service_id'=>'required|numeric|min:1',
                  'currency_id'=>'required',
                  'price'=>'numeric|min:0',
                  'duration_id'=>'required',
                  'check_cancellation'=>'numeric|required',
                  'stardate'=>'required|date_format:Y-m-d|before:today',
                  'finishdate'=>'required|date_format:Y-m-d|after:today',
                  'price_optional'=>'numeric'

            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                if(count($service)>0){
                try{
                      $newhistory=new Price_History;
                      $dt = new DateTime();
                      $newhistory->starDate=$dt->format('Y-m-d (H:i:s)');
                      $newhistory->service_id=$service->id;
                      $newhistory->price=$request->input("price");
                      $newhistory->currency_id=$request->input("currency_id");
                      $newhistory->duration_id=$request->input("duration_id");
                      $newhistory->save();
                      $newcheck_in=new Check_in;
                      $newcheck_in->time_entry=$request->input("time_entry");
                      $newcheck_in->until=$request->input("until");
                      $newcheck_in->service_id=$service->id;
                      $newcheck_in->save();
                      $newcheck_out=new Check_out;
                      $newcheck_out->departure_time=$request->input("departure_time");
                      $newcheck_out->service_id=$service->id;
                      $newcheck_out->save();
                      $newcancellation=new Service_Cancellation;
                      $newcancellation->service_id=$service->id;
                      $newcancellation->cancellation_id=$request->input("check_cancellation");
                      $newcancellation->save();
                      $newoptionalprice=new SpecialDate;
                      $newoptionalprice->service_id=$service->id;;
                      $newoptionalprice->stardate=$request->input("stardate");
                      $newoptionalprice->finishdate=$request->input("finishdate");
                      $newoptionalprice->price=$request->input("price_optional");
                      $newoptionalprice->save();
                      return response()->json('Add Step6'); 
                    }catch(exception $e){
                       return response()->json($e); 
                    }
                }else{
                    return response()->json('Service not found'); 
                }
            }
    }
   
   public function AddNewSpaceStep7Description(Request $request){
          $rule=[
           'service_id' => 'required|numeric|min:1',
           'bool_socialize'=>'boolean',
           'bool_available'=>'boolean'
           ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $service=Service::where('id',$request->input("service_id"))->first();
            if(count($service)>0){
                try{
                  $des_title=new Service_Description;
                  $des_title->service_id=$service->id;
                  $des_title->description_id=1;
                  $des_title->content=$request->input("des_title");
                  $des_title->save();
                  $des_description=new Service_Description;
                  $des_description->service_id=$service->id;
                  $des_description->description_id=8;
                  $des_description->content=$request->input("description");
                  $des_description->save();
                  $des_crib=new Service_Description;
                  $des_crib->service_id=$service->id;
                  $des_crib->description_id=11;
                  $des_crib->content=$request->input("desc_crib");
                  $des_crib->save();
                  $des_acc=new Service_Description;
                  $des_acc->service_id=$service->id;
                  $des_acc->description_id=12;
                  $des_acc->content=$request->input("desc_acc");
                  $des_acc->save();
                  $socialize=new Service_Description;
                  $socialize->service_id=$service->id;
                  $socialize->description_id=13;
                  $socialize->content=$request->input("bool_socialize");
                  $socialize->save();
                  $available=new Service_Description;
                  $available->service_id=$service->id;
                  $available->description_id=14;
                  $available->content=$request->input("bool_available");
                  $available->save();
                  $des_guest=new Service_Description;
                  $des_guest->service_id=$service->id;
                  $des_guest->description_id=15;
                  $des_guest->content=$request->input("desc_guest");
                  $des_guest->save();
                  $des_note=new Service_Description;
                  $des_note->service_id=$service->id;
                  $des_note->description_id=16;
                  $des_note->content=$request->input("desc_note");
                  $des_note->save();
                  return response()->json('Add Step-7'); 
                }catch(Exception $e){
                    return response()->json($e); 
               }
            }else{
                return response()->json('Service not found'); 
            }
        }
   }

   public function AddNewSpaceStep8Rules(Request $request){
          $rule=[
           'service_id' => 'required|numeric|min:1',
           'AptoDe2a12'=>'boolean',
           'AptoDe0a2'=>'boolean',
           'SeadmitenMascotas'=>'boolean',
           'PremitidoFumar'=>'boolean',
           'Eventos'=>'boolean',
           'guest_phone'=>'boolean',
           'guest_email'=>'boolean',
           'guest_profile'=>'boolean',
           'guest_payment'=>'boolean',
           'guest_provided'=>'boolean',
           'guest_recomendation'=>'boolean'
          ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $service=Service::where('id',$request->input("service_id"))->first();
            if(count($service)>0){
                try{
                 $newrules=new Service_Rules;
                 $newrules->service_id=$service->id;
                 $newrules->rules_id=1;
                 $newrules->check=$request->input("AptoDe2a12");
                 $newrules->save();
                 $newrules=new Service_Rules;
                 $newrules->service_id=$service->id;
                 $newrules->rules_id=2;
                 $newrules->check=$request->input("AptoDe0a2");
                 $newrules->save();
                 $newrules=new Service_Rules;
                 $newrules->service_id=$service->id;
                 $newrules->rules_id=3;
                 $newrules->check=$request->input("SeadmitenMascotas");
                 $newrules->save();
                 $newrules=new Service_Rules;
                 $newrules->service_id=$service->id;
                 $newrules->rules_id=4;
                 $newrules->check=$request->input("PremitidoFumar");
                 $newrules->save();
                 $newrules=new Service_Rules;
                 $newrules->service_id=$service->id;
                 $newrules->rules_id=5;
                 $newrules->check=$request->input("Eventos");
                 $newrules->save();
                 $newrules=new Service_Rules; 
                 $newrules->service_id=$service->id;
                 $newrules->rules_id=6;
                 $newrules->description=$request->input("Desc_Otro_Evento ");
                 $newrules->save();
                 $newrequirement=new Service_Rules;
                 $newrequirement->service_id=$service->id;
                 $newrequirement->rules_id=7;
                 $newrequirement->check=$request->input("guest_phone");
                 $newrequirement->save();
                 $newrequirement=new Service_Rules;
                 $newrequirement->service_id=$service->id;
                 $newrequirement->rules_id=8;
                 $newrequirement->check=$request->input("guest_email");
                 $newrequirement->save();
                 $newrequirement=new Service_Rules;
                 $newrequirement->service_id=$service->id;
                 $newrequirement->rules_id=9;
                 $newrequirement->check=$request->input("guest_profile");
                 $newrequirement->save();
                 $newrequirement=new Service_Rules;
                 $newrequirement->service_id=$service->id;
                 $newrequirement->rules_id=10;
                 $newrequirement->check=$request->input("guest_payment");
                 $newrequirement->save();
                 $newrequirement=new Service_Rules;
                 $newrequirement->service_id=$service->id;
                 $newrequirement->rules_id=11;
                 $newrequirement->check=$request->input("guest_provided");
                 $newrequirement->save();
                 $newrequirement=new Service_Rules;
                 $newrequirement->service_id=$service->id;
                 $newrequirement->rules_id=12;
                 $newrequirement->check=$request->input("guest_recomendation");
                 $newrequirement->save();
                 $newrules=new Service_Rules; 
                 $newrules->service_id=$service->id;
                 $newrules->rules_id=13;
                 $newrules->description=$request->input("Desc_Instructions");
                 $newrules->save();
                 $newrules=new Service_Rules; 
                 $newrules->service_id=$service->id;
                 $newrules->rules_id=14;
                 $newrules->description=$request->input("Desc_Name_Network");
                 $newrules->save();
                 $newrules=new Service_Rules; 
                 $newrules->service_id=$service->id;
                 $newrules->rules_id=15;
                 $newrules->description=Crypt::encrypt($request->input("Password_Wifi"));
                 $newrules->save();
                 return response()->json('Add Step-8'); 
                }catch(Exception $e){
                       return response()->json($e); 
                }
            }else{
                 return response()->json('Service not found'); 
            }
       }
   }

   public function AddNewSpaceStep9(Request $request){
        $rule=[
           'service_id' => 'required|numeric|min:1',
           'ruta'=>'imagen'
           ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $service=Service::where('id',$request->input("service_id"))->first();
            if(count($service)>0){
                 try{
                    // Se definen las credenciales del cliente s3 de amazon
                    $s3 = new S3Client([
                        'version'     => env('S3_VERSION'),
                        'region'      => env('S3_REGION'),
                        'credentials' => [
                            'key'    => env('S3_KEY'),
                            'secret' => env('S3_SECRET')
                        ]
                    ]);
                    $image_link = 'https://s3.'.env('S3_REGION').'.amazonaws.com/'.env('S3_BUCKET').'/files/imagen/';
                    // Obtenemos el campo file definido en el formulario
                    $file = $request->file('imagen');            
                    // Creamos un nombre para nuestro thumnail
                    $name = 'imagen'.str_random(20).'_service_'.$service->id.'.'.$file->getClientOriginalExtension();         
                    // Movemos el archivo a la caperta temporal
                    $file->move('files/imagen/',$name);
                    //
                    $old_thumbnail = str_replace($image_link,'',$service->imagen);
                    //
                    $s3->deleteObject([
                        'Bucket' => env('S3_BUCKET'),
                        'Key'    => 'files/imagen/'.$old_thumbnail
                    ]);
                    //
                    $s3->putObject([
                        'Bucket' => env('S3_BUCKET'),
                        'Key'    => 'files/imagen/'.$name,
                        'Body'   => fopen('files/imagen/'.$name, 'r'),
                        'ACL'    => 'public-read'
                    ]);
                    //
                    // Borramos el arrchivo de la carpeta temporal
                    unlink('files/imagen/'.$name);
                    // Actualizamos la fila thumbnail del usuario respectivo
                    DB::table('service')->where('id', $service->id )->update(['ruta' => $image_link.$name,
                    'description'=>$request->input("description")]);

                    return json_encode('Update completed!', true);
                }
                catch (\Exception $e){
                    return response()->json($e->getMessage());
                }
            }else{
              return response()->json('Service not found'); 
            }

        }


   }

}

