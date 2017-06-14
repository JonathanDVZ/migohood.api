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
use App\Models\Languaje;
use App\Models\Service_Languaje;
use App\Models\Country;
use App\Models\Service_Rules;
use App\Models\Price_history_has_duration;
use App\Models\Service_Reservation;
use App\Models\Service_Description;
use App\Models\Category;
use App\Models\SpecialDate;
use App\Models\Service_Amenite;
use App\Models\Service_Type;
use App\Models\Image;
use App\Models\Type;
use App\Models\Duration;
use App\Models\Payment;
use App\Models\Service_Category;
use App\Models\Service_Calendar;
use App\Models\Service_Accommodation;
use App\Models\Service_Payment;
use App\Models\Calendar;
use App\Models\Availability;
use App\Models\Price_History;
use App\Models\Emergency_Number;
use App\Models\Service_Emergency;
use App\Models\Image_Duration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
use DateTime;
use DB;
class ControllerService extends Controller
{
    //Muestra todo los service
    public function ReadService(){
    //Se obtiene todos los servicios que se crean
    return Service::all();
    }

    //Agreg New Step 1 -Movil
    public function AddNewStep(Request $request){
             //Regla de validacion
              $rule=[
                    'service_id'=>'required|numeric|min:1',
                    'accommodation_code'=>'required|numeric|min:1',
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
               }
             else{
                 //Busca el usuario
                 $service=Service::where('id',$request->input("service_id"))->first();
                 $accommodation=Accommodation::select('id')->where('code',$request->input("accommodation_code"))->get();
                 if(count($service)>0){///Verifica el usuario
                   if(count($accommodation)>0){
                      $valacco=Service_Accommodation::select()->where('service_id',$service->id)->get();
                      if(count($valacco)==0){
                        foreach ($accommodation as $accommodations){
                        $newserviacco=new Service_Accommodation;
                        $newserviacco->service_id=$service->id;
                        $newserviacco->accommodation_id=$accommodations->id;
                        $newserviacco->save();
                         return response()->json($newserviacco);
                        }
                    }else{
                          DB::table('service_description')->where('service_id',$service->id)->delete();
                          foreach ($accommodation as $accommodations){
                          $newserviacco=new Service_Accommodation;
                          $newserviacco->service_id=$service->id;
                          $newserviacco->accommodation_id=$accommodations->id;
                          $newserviacco->save();
                          return response()->json($newserviacco);
                          }
                     }
                    }else{
                        return response()->json('Accommodation not found');
                    }
            }else{
                return response()->json('Service not found');
            }
    }
    }


     //Agrega New Step-1-Movil
    public function AddNewStep1(Request $request){
          $rule=['type_code'=>'required|numeric|min:1',
                 'service_id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $type=Type::select('id')->where('category_id',1)->where('code',$request->input("type_code"))->get();
                    //  $accommodation=Accommodation::select('id')->where('code',$request->input("accommodation_code"))->get();
                 if(count($type)>0){
                      $service=Service::select()->where('id',$request->input("service_id"))->first();
                         if(count($service)>0){
                               $valtype=Service_Type::select()->where('service_id',$service->id)->get();
                               if(count($valtype)==0){
                               foreach ($type as $types){
                                 $newservitype=new Service_Type;
                                 $newservitype->service_id=$service->id;
                                 $newservitype->type_id=$types->id;
                                 $newservitype->save();
                                 }
                                     return response()->json('Add Type');
                                }else{
                                 DB::table('service_type')->where('service_id',$service->id)->delete();
                                 foreach ($type as $types){
                                 $newservitype=new Service_Type;
                                 $newservitype->service_id=$service->id;
                                 $newservitype->type_id=$types->id;
                                 $newservitype->save();
                                }
                                   return response()->json('Update Type');
                                }
                       }else{
                             return response()->json('Service not found');
                         }
                 }else{
                       return response()->json('Type not found');
                 }
          }
    }

   //Agrega New Step 2-Movil
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
                if(count($service)>0){

                     $service->num_guest=$request->input("num_guests");
                     $service->save();
                     for($i=1;$i<=$request->input("num_bedroom");$i++){
                           $bedroom=new Bedroom;
                           $bedroom->service_id=$service->id;
                           $bedroom->save();
                     }
                     return response()->json($bedroom);
                }else{
                     return response()->json('Service not found');
                }

            }
    }


    //Agrega New Step 3-Movil
    public function AddNewStep3(Request $request){
         $rule=[ // 'service_id'=>'required|numeric|min:1',
              //'num_bathroom'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                if(count($service)>0){
                    $service->num_bathroom=$request->input("num_bathroom");
                    $service->save();
                    return response()->json('Add Step 3');
                }else{
                    return response()->json('Service not found');
                }
            }
    }


   //Agrega New Step 4-Movil
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
        $rule=[
            'amenitie_code'=>'required|numeric|min:1',
            'service_id'=>'required|numeric|min:1'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
            // Selecciono los amenites que posean el código recibido
            $amenites=Amenite::select('id')->where('category_id','=',1)->where('code',$request->input("amenitie_code"))->get();
            if(count($amenites)>0){
                // Selecciono el service que posea el id recibido
                $service=Service::select()->where('id',$request->input("service_id"))->first();
                if(count($service)>0){
                    // Recorro el array amenities e inserto cada uno en la tabla interseccion Service_Amenite junto con el service correspondiente
                      foreach ($amenites as $amenite){
                       $newserviceame=new Service_Amenite;
                       $newserviceame->service_id=$service->id;
                       $newserviceame->amenite_id=$amenite->id;
                       $newserviceame->save();
                      }

                    return response()->json('Add Step 5');
                }
                else{
                    return response()->json('Service not found');
                }
            }
            else{
                return response()->json('Amenite not found');
            }
        }
    }

    //Agrega step6 movil
    public function AddNewStep6(Request $request){
                $rule=[  'service_id'=>'required|numeric|min:1',
                'politic_payment_code'=>'required|numeric|min:1|max:3',
                'price'=>'required|numeric',
                'currency_id'=>'required|numeric',
                'duration_code'=>'required|numeric'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                $payment=Payment::select('id')->where('code',$request->input("politic_payment_code"))->get();
                $duration=Duration::select('id')->where('code',$request->input("duration_code"))->get();
                if(count($service)>0 and count($payment)>0){
                      $newhistory=new Price_History;
                      $dt = new DateTime();
                      $newhistory->starDate=$dt->format('Y-m-d (H:i:s)');
                      $newhistory->service_id=$service->id;
                      $newhistory->price=$request->input("price");
                      $newhistory->currency_id=$request->input("currency_id");
                      $newhistory->save();
                      foreach($payment as $payments){
                         $newpayment=new Service_Payment;
                         $newpayment->service_id=$service->id;
                         $newpayment->payment_id=$payments->id;
                         $newpayment->save();
                       }
                       foreach($duration as $durations){
                         $newpriceduration=new Price_history_has_duration;
                         $newpriceduration->price_history_starDate=$newhistory->starDate;
                         $newpriceduration->price_history_service_id=$newhistory->service_id;
                         $newpriceduration->duration_id=$durations->id;
                         $newpriceduration->save();
                       }
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
                    $valrules=Service_Rules::where('service_id',$service->id)->first();
                    if(count($valtrules)==0){
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
                          DB::table('service_rules')->where('service_id',$service->id)->delete();
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
                          return response()->json('Update Rules');
                    }
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
         $rule=[  'service_id'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                $service=Service::where('id','=',$request->input("service_id"))->first();
                if(count($service)>0){
                  $valreservation=Service_Reservation::where('service_id',$service->id)->get();
                 if(count($valreservation)==0){
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
                  }catch(\Exception $e){
                      return response()->json($e);
                  }}else{
                      DB::table('service_reservation')->where('service_id',$service->id)->delete();
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
                    return response()->json('Update Preference Reservation');
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
               ->join('amenities','service_amenites.amenite_id','=','amenities.id')
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
    public function CreateSpace(Request $request){
         $rule=[
                    'service_id'=>'required|numeric|min:1',
                    'accommodation_code'=>'required|numeric|min:1',
                    'type_code'=>'required|numeric|min:1',
                    'num_guests'=>'required|numeric|min:0',
                    'num_bedroom'=>'required|numeric|min:0',
                    'num_bathroom'=>'required|numeric|min:1',
                    'city_id'=>'numeric|min:1',
                    'zipcode'=>'numeric|min:1',
                    'amenitie_code'=>'required|numeric|min:1',
                    'politic_payment_code'=>'required|numeric|min:1|max:3',
                    'price'=>'required|numeric',
                    'currency_id'=>'required|numeric',
                    'duration_code'=>'required|numeric',
                    'des_title'=>'required',
                    'description'=>'required',
                    'AptoDe2a12'=>'boolean',
                    'AptoDe0a2'=>'boolean',
                    'SeadmitenMascotas'=>'boolean',
                    'PremitidoFumar'=>'boolean',
                    'Eventos'=>'boolean'

             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
               }
             else{
                 //Busca el usuario
                 $service=Service::where('id',$request->input("service_id"))->first();
                 $accommodation=Accommodation::select('id')->where('code',$request->input("accommodation_code"))->get();
                 $type=Type::select('id')->where('category_id',1)->where('code',$request->input("type_code"))->get();
                 $city=City::where('id',$request->input("city_id"))->first();
                 $amenites=Amenite::select('id')->where('category_id','=',1)->where('code',$request->input("amenitie_code"))->get();
                 $payment=Payment::select('id')->where('code',$request->input("politic_payment_code"))->get();
                 $duration=Duration::select('id')->where('code',$request->input("duration_code"))->get();
                 if(count($service)>0){
                   if(count($duration)>0){
                      if(count($amenites)>0){
                        if(count($city)){
                          if(count($type)>0){
                             if(count($payment)>0){///Verifica el usuario
                               if(count($accommodation)>0){
                                 foreach ($accommodation as $accommodations){
                                    $newserviacco=new Service_Accommodation;
                                    $newserviacco->service_id=$service->id;
                                    $newserviacco->accommodation_id=$accommodations->id;
                                    $newserviacco->save();
                                  }
                                  for($i=1;$i<=$request->input("num_bedroom");$i++){
                                    $bedroom=new Bedroom;
                                    $bedroom->service_id=$service->id;
                                    $bedroom->save();
                                   }
                                   $service->num_guest=$request->input("num_guests");
                                   $service->num_bathroom=$request->input("num_bathroom");
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
                                   foreach ($amenites as $amenite){
                                       $newserviceame=new Service_Amenite;
                                       $newserviceame->service_id=$service->id;
                                       $newserviceame->amenite_id=$amenite->id;
                                       $newserviceame->save();
                                    }
                                    $newhistory=new Price_History;
                                    $dt = new DateTime();
                                    $newhistory->starDate=$dt->format('Y-m-d (H:i:s)');
                                    $newhistory->service_id=$service->id;
                                    $newhistory->price=$request->input("price");
                                    $newhistory->currency_id=$request->input("currency_id");
                                    $newhistory->save();
                                    foreach($payment as $payments){
                                       $newpayment=new Service_Payment;
                                       $newpayment->service_id=$service->id;
                                       $newpayment->payment_id=$payments->id;
                                       $newpayment->save();
                                    }
                                    foreach($duration as $durations){
                                       $newpriceduration=new Price_history_has_duration;
                                       $newpriceduration->price_history_starDate=$newhistory->starDate;
                                       $newpriceduration->price_history_service_id=$newhistory->service_id;
                                       $newpriceduration->duration_id=$durations->id;
                                       $newpriceduration->save();
                                    }
                                    $des_title=new Service_Description;
                                    $des_title->service_id=$service->id;
                                    $des_title->description_id=1;
                                    $des_title->content=$request->input("des_title");
                                    $des_title->save();
                                    $description=new Service_Description;
                                    $description->service_id=$service->id;
                                    $description->description_id=8;
                                    $description->content=$request->input("description");
                                    $description->save();
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
                                    $newcheck_in=new Check_in;
                                    $newcheck_in->time_entry=$request->input("time_entry");
                                    $newcheck_in->until=$request->input("until");
                                    $newcheck_in->service_id=$service->id;
                                    $newcheck_in->save();
                                    $newcheck_out=new Check_out;
                                    $newcheck_out->departure_time=$request->input("departure_time");
                                    $newcheck_out->service_id=$service->id;
                                    $newcheck_out->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=1;
                                    $newreservation->check=$request->input("compliant_guests");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=8;
                                    $newreservation->check=$request->input("compliant_guests");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=2;
                                    $newreservation->check=$request->input("send_request");
                                    $newreservation->save();
                                     $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=9;
                                    $newreservation->check=$request->input("send_request");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=3;
                                    $newreservation->check=$request->input("phone_and_email");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=10;
                                    $newreservation->check=$request->input("phone_and_email");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=4;
                                    $newreservation->check=$request->input("thumbnail");
                                    $newreservation->save();
                                      $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=11;
                                    $newreservation->check=$request->input("thumbnail");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=5;
                                    $newreservation->check=$request->input("data_payment");
                                    $newreservation->save();
                                     $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=12;
                                    $newreservation->check=$request->input("data_payment");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=6;
                                    $newreservation->check=$request->input("id");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=13;
                                    $newreservation->check=$request->input("id");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=7;
                                    $newreservation->check=$request->input("positive_valuation");
                                    $newreservation->save();
                                    $newreservation=new Service_Reservation;
                                    $newreservation->service_id=$service->id;
                                    $newreservation->preference_id=14;
                                    $newreservation->check=$request->input("positive_valuation");
                                    $newreservation->save();

                                       return  response()->json("Space Create Complete!");
                               }else{
                                         return  response()->json("Accommodation Not Found");
                               }
                             }else{
                                       return  response()->json("Payment Not Found");
                             }
                            }else{
                                      return  response()->json("Type Not Found");
                            }
                           }else{
                                    return  response()->json("City Not Found");
                           }
                          }else{
                                    return  response()->json("Amenitie Not Found");
                          }
                        }else{
                                  return  response()->json("Duration Not Found");
                        }
                      }else{
                                return  response()->json("Service Not Found");
                      }
                    }
                   }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Agrega un espacio(web)
    public function AddNewSpaceStep(Request $request){
         $rule=[
            'user_id' => 'required|numeric|min:1',
            'category_code'=>'required|numeric|min:1',
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
              $user=User::select()->where('id',$request->input("user_id"))->first();
              $category=Category::select('id')->where('code',$request->input("category_code"))->get();
              if(count($user)>0){
                 if(count($category)>0){
                     $newspace=new Service;
                     $dt = new DateTime();
                     $newspace->date=$dt->format('Y-m-d (H:m:s)');
                     $newspace->user_id=$user->id;
                     $newspace->save();
                     foreach ($category as $categorys){
                            $newservicateg=new Service_Category;
                            $newservicateg->service_id=$newspace->id;
                            $newservicateg->category_id=$categorys->id;
                            $newservicateg->save();
                           }
                     return  response()->json($newspace);
                }else{
                     return  response()->json("Category Not Found");
                 }
              }else{
                 return  response()->json("User Not Found");
              }
        }

    }

   //Agregar Service(space-step1)-Web
    public function AddNewSpaceStep1(Request $request){
        $rule=[
            // Comente esto, ya que aun no poseo ningun id service
            'service_id' => 'required|numeric|min:1',
            'type_code'=>'required|numeric|min:1',
            'live'=>'required|boolean',
            'accommodation_code'=>'required|numeric|min:1'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        } else {
            $service=Service::select()->where('id',$request->input("service_id"))->first();
            $type=Type::select('id')->where('category_id','=',1)->where('code','=',$request->input("type_code"))->get();
            $accommodation=Accommodation::select('id')->where('code',$request->input("accommodation_code"))->get();
                if(count($service)>0){
                    if(count($type)>0){
                        if(count($accommodation)>0){
                          $valacco=Service_Accommodation::select('id')->where('service_id',$service->id)->get();
                          $valtype=Service_Type::select()->where('service_id',$service->id)->first();
                          try{
                              if(count($valacco)==0 && count($valtype)==0){
                                DB::table('service')->where('id',$service->id)->update(
                                ['live'=>$request->input("live"),
                                ]);

                                foreach($accommodation as $accommodations){
                                  $newacco=new Service_Accommodation;
                                  $newacco->service_id=$service->id;
                                  $newacco->accommodation_id=$accommodations->id;
                                  $newacco->save();
                                }
                                foreach($type as $types){
                                  $newtype=new Service_Type;
                                  $newtype->service_id = $service->id;
                                  $newtype->type_id=$types->id;
                                  $newtype->save();
                                 }
                                return response()->json('Add Accommodaton and Type ');
                            }else{
                            DB::table('service_type')->where('service_id',$service->id)->delete();
                            DB::table('service_accommodation')->where('service_id',$service->id)->delete();
                            DB::table('service')->where('id',$service->id)->update(
                                ['live'=>$request->input("live"),
                                ]);

                                foreach($accommodation as $accommodations){
                                  $newacco=new Service_Accommodation;
                                  $newacco->service_id=$service->id;
                                  $newacco->accommodation_id=$accommodations->id;
                                  $newacco->save();
                                }
                                foreach($type as $types){
                                  $newtype=new Service_Type;
                                  $newtype->service_id = $service->id;
                                  $newtype->type_id=$types->id;
                                  $newtype->save();
                                 }
                                  return response()->json('Update Accommodation and Type ');
                            }
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
        }
    }

    //Agregar Service(space-step2)-Web
    public function AddNewSpaceStep2(Request $request){
        $rule=[
           'service_id' => 'required|numeric|min:1',
           'num_guest'=>'required|numeric|min:0',
           'num_bedroom'=>'required|numeric'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        } else {
            $servicespace=Service::select()->where('id',$request->input("service_id"))->first();
            /* Buscas si existen habitaciones previamente y cuantas habitaciones hay y sus respectivos id, si el numero que se esta pasando es mayor entonces agregas el restante, si es menor debes eliminar las sobrantes */
            // Habia un error, la variable se llama servicespace, no service
            if(count($servicespace)>0){

                $servicespace->num_guest=$request->input("num_guest");
                //$servicespace->save();
                DB::table('service')->where('id',$servicespace->id)->update(
                            ['num_guest'=> $request->input("num_guest"),
                            ]);
                $val=Bedroom::where('service_id',$servicespace->id)->first();
                if(count($val)==0){
                    for($i=1;$i<=$request->input("num_bedroom");$i++){
                        $bedroom=new Bedroom;
                        // Habia un error, la variable se llama servicespace, no service
                        $bedroom->service_id=$servicespace->id;
                        $bedroom->save();
                    }
                }else{
                    DB::table('bedroom')->where('service_id',$servicespace->id)->delete();
                    for($i=1;$i<=$request->input("num_bedroom");$i++){
                        $bedroom=new Bedroom;
                        // Habia un error, la variable se llama servicespace, no service
                        $bedroom->service_id=$servicespace->id;
                        $bedroom->save();
                    }
                }
                /**
                *   Envio como respuesta el servicio junto con el numero de habitaciones,
                *   ya que para la vista siguiente son necesarios dichos datos
                */
                return response()->json("Add Space Bedroom");

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
           'other_bed'=>'numeric|min:0'

        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
            $servicespace=Service::select()->where('id',$request->input("service_id"))->first();
            if(count($servicespace)>0){
              $bedroom=Bedroom::select()->where('service_id','=',$servicespace->id)->where('id','=',$request->input("bedroom_id"))->first();
              if(count($bedroom)>0){
                $val=Bedroom_Bed::select()->where('bedroom_id','=',$bedroom->id)->first();
                if(count($val)==0){
                try{
                 $newbedroomdouble=new Bedroom_Bed;
                 $newbedroomdouble->bedroom_id=$bedroom->id;
                 $newbedroomdouble->bed_id=1;
                 $newbedroomdouble->quantity=$request->input("double_bed");
                 $newbedroomdouble->save();
                 $newbedroomdouble=new Bedroom_Bed;
                 $newbedroomdouble->bedroom_id=$bedroom->id;
                 $newbedroomdouble->bed_id=6;
                 $newbedroomdouble->quantity=$request->input("double_bed");
                 $newbedroomdouble->save();
                 $newbedroomqueen=new Bedroom_Bed;
                 $newbedroomqueen->bedroom_id=$bedroom->id;
                 $newbedroomqueen->bed_id=2;
                 $newbedroomqueen->quantity=$request->input("queen_bed");
                 $newbedroomqueen->save();
                 $newbedroomqueen=new Bedroom_Bed;
                 $newbedroomqueen->bedroom_id=$bedroom->id;
                 $newbedroomqueen->bed_id=7;
                 $newbedroomqueen->quantity=$request->input("queen_bed");
                 $newbedroomqueen->save();
                 $newbedroomindividual=new Bedroom_Bed;
                 $newbedroomindividual->bedroom_id=$bedroom->id;
                 $newbedroomindividual->bed_id=3;
                 $newbedroomindividual->quantity=$request->input("individual_bed");
                 $newbedroomindividual->save();
                 $newbedroomindividual=new Bedroom_Bed;
                 $newbedroomindividual->bedroom_id=$bedroom->id;
                 $newbedroomindividual->bed_id=8;
                 $newbedroomindividual->quantity=$request->input("individual_bed");
                 $newbedroomindividual->save();
                 $newbedroomsofa=new Bedroom_Bed;
                 $newbedroomsofa->bedroom_id=$bedroom->id;
                 $newbedroomsofa->bed_id=4;
                 $newbedroomsofa->quantity=$request->input("sofa_bed");
                 $newbedroomsofa->save();
                 $newbedroomsofa=new Bedroom_Bed;
                 $newbedroomsofa->bedroom_id=$bedroom->id;
                 $newbedroomsofa->bed_id=9;
                 $newbedroomsofa->quantity=$request->input("sofa_bed");
                 $newbedroomsofa->save();
                 $newbedroomother=new Bedroom_Bed;
                 $newbedroomother->bedroom_id=$bedroom->id;
                 $newbedroomother->bed_id=5;
                 $newbedroomother->quantity=$request->input("other_bed");
                 $newbedroomother->save();
                 $newbedroomother=new Bedroom_Bed;
                 $newbedroomother->bedroom_id=$bedroom->id;
                 $newbedroomother->bed_id=10;
                 $newbedroomother->quantity=$request->input("other_bed");
                 $newbedroomother->save();
                    return response()->json('Add Bedroom-Beds');
                }catch(Exception $e){
                    return response()->json($e);
                }
            }else{
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',1)->update(
                            ['quantity'=>$request->input("double_bed"),
                            ]);
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',6)->update(
                            ['quantity'=>$request->input("double_bed"),
                            ]);
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',2)->update(
                            ['quantity'=>$request->input("queen_bed"),
                            ]);
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',7)->update(
                            ['quantity'=>$request->input("queen_bed"),
                            ]);
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',3)->update(
                            ['quantity'=>$request->input("individual_bed"),
                            ]);
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',8)->update(
                            ['quantity'=>$request->input("individual_bed"),
                            ]);
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',4)->update(
                            ['quantity'=>$request->input("sofa_bed"),
                            ]);
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',9)->update(
                            ['quantity'=>$request->input("sofa_bed"),
                            ]);
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',5)->update(
                            ['quantity'=>$request->input("other_bed"),
                            ]);
                     DB::table('bedroom_bed')->where('bedroom_id',$bedroom->id)->where('bed_id',10)->update(
                            ['quantity'=>$request->input("other_bed"),
                            ]);

                     return response()->json('Update  Bedroom-Beds');
                 }
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
          // 'country_id'=>'numeric|min:1',
           'city_id'=>'numeric|min:1',
        //   'state_id'=>'numeric|min:1',

      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $servicespace=Service::select()->where('id',$request->input("service_id"))->first();
        //    $country=Country::select()->where('id',$request->input("country_id"))->first();
          //  $state=State::select()->where('country_id',$country->id)->where('id',$request->input("state_id"))->first();
            $city=City::select()->where('id',$request->input("city_id"))->first();
            if(count($servicespace)>0){
                if(count($city)>0){
                   // if(count($state)>0){
                     //   if(count($country)>0){
                            $val=Service_Description::select()->where('service_id',$request->input("service_id"))->first();
                            if(count($val)==0){
                                  DB::table('service')->where('id',$servicespace->id)->update(
                                    ['city_id'=>$request->input("city_id"),
                               ]);
                                 DB::table('service')->where('id',$servicespace->id)->update(
                                    ['zipcode'=>$request->input("zipcode"),
                               ]);
                                $des_address1=new Service_Description;
                                $des_address1->service_id=$servicespace->id;
                                $des_address1->description_id=2;
                                $des_address1->content=$request->input("address1");
                                $des_address1->save();
                                $des_apt=new Service_Description;
                                $des_apt->service_id=$servicespace->id;
                                $des_apt->description_id=3;
                                $des_apt->content=$request->input("apt");
                                $des_apt->save();
                                $des_neighborhood=new Service_Description;
                                $des_neighborhood->service_id=$servicespace->id;
                                $des_neighborhood->description_id=4;
                                $des_neighborhood->content=$request->input("des_neighborhood");
                                $des_neighborhood->save();
                                $des_around=new Service_Description;
                                $des_around->service_id=$servicespace->id;
                                $des_around->description_id=5;
                                $des_around->content=$request->input("des_around");
                                $des_around->save();
                                $des_longitude=new Service_Description;
                                $des_longitude->service_id=$servicespace->id;
                                $des_longitude->description_id=6;
                                $des_longitude->content=$request->input("des_longitude");
                                $des_longitude->save();
                                $des_latitude=new Service_Description;
                                $des_latitude->service_id=$servicespace->id;
                                $des_latitude->description_id=7;
                                $des_latitude->content=$request->input("des_latitude");
                                $des_latitude->save();
                                return response()->json('Add Location');
                            }else{
                                   DB::table('service_description')->where('service_id',$servicespace->id)->delete();
                                     DB::table('service')->where('id',$servicespace->id)->update(
                                    ['city_id'=>$request->input("city_id"),
                               ]);
                                 DB::table('service')->where('id',$servicespace->id)->update(
                                    ['zipcode'=>$request->input("zipcode"),
                               ]);
                                $des_address1=new Service_Description;
                                $des_address1->service_id=$servicespace->id;
                                $des_address1->description_id=2;
                                $des_address1->content=$request->input("address1");
                                $des_address1->save();
                                $des_apt=new Service_Description;
                                $des_apt->service_id=$servicespace->id;
                                $des_apt->description_id=3;
                                $des_apt->content=$request->input("apt");
                                $des_apt->save();
                                $des_neighborhood=new Service_Description;
                                $des_neighborhood->service_id=$servicespace->id;
                                $des_neighborhood->description_id=4;
                                $des_neighborhood->content=$request->input("des_neighborhood");
                                $des_neighborhood->save();
                                $des_around=new Service_Description;
                                $des_around->service_id=$servicespace->id;
                                $des_around->description_id=5;
                                $des_around->content=$request->input("des_around");
                                $des_around->save();
                                $des_longitude=new Service_Description;
                                $des_longitude->service_id=$servicespace->id;
                                $des_longitude->description_id=6;
                                $des_longitude->content=$request->input("des_longitude");
                                $des_longitude->save();
                                $des_latitude=new Service_Description;
                                $des_latitude->service_id=$servicespace->id;
                                $des_latitude->description_id=7;
                                $des_latitude->content=$request->input("des_latitude");
                                $des_latitude->save();
                                return response()->json('Update Location');
                            }
                   /*     }else{
                           return response()->json('Country not found');
                        }

                    }else{
                         return response()->json('State not found');
                    } */
                }else{
                     return response()->json('City not found');
                }

            }else{
                return response()->json('Service not found');
            }

        }

     }


    //Agregar Service(space-step5)-Web
  /*  public function AddNewSpaceStep5Amenities(Request $request){
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
   }*/


    //Agregar Service(space-step6)-Web
    public function AddNewSpaceStep6(Request $request){
        $rule=[  'service_id'=>'required|numeric|min:1',
                  'currency_id'=>'required',
                  'price'=>'numeric|min:0',
                  'duration_code'=>'required',
                  'politic_payment_code'=>'numeric'

            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $service=Service::where('id',$request->input("service_id"))->first();
                $payment=Payment::select('id')->where('code',$request->input("politic_payment_code"))->get();
                $duration=Duration::select('id')->where('code',$request->input("duration_code"))->get();
                if(count($service)>0 && count($payment)>0 && count($duration)>0){
                try{
                      $newhistory=new Price_History;
                      $dt = new DateTime();
                      $newhistory->starDate=$dt->format('Y-m-d (H:i:s)');
                      $newhistory->service_id=$service->id;
                      $newhistory->price=$request->input("price");
                      $newhistory->currency_id=$request->input("currency_id");
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
                     foreach($payment as $payments){
                         $newpayment=new Service_Payment;
                         $newpayment->service_id=$service->id;
                         $newpayment->payment_id=$payments->id;
                         $newpayment->save();
                       }
                        foreach($duration as $durations){
                         $newpriceduration=new Price_history_has_duration;
                         $newpriceduration->price_history_starDate=$newhistory->starDate;
                         $newpriceduration->price_history_service_id=$newhistory->service_id;
                         $newpriceduration->duration_id=$durations->id;
                         $newpriceduration->save();
                       }
                     /* $newoptionalprice=new SpecialDate;
                      $newoptionalprice->service_id=$service->id;;
                      $newoptionalprice->stardate=$request->input("stardate");
                      $newoptionalprice->finishdate=$request->input("finishdate");
                      $newoptionalprice->price=$request->input("price_optional");
                      $newoptionalprice->save();*/
                      return response()->json('Add Step-6');
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
                $valdescription=Service_Description::where('service_id',$service->id)->get();
                if(count($valdescription)==0){
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
                  $des_crib->description_id=9;
                  $des_crib->content=$request->input("desc_crib");
                  $des_crib->save();
                  $des_acc=new Service_Description;
                  $des_acc->service_id=$service->id;
                  $des_acc->description_id=10;
                  $des_acc->content=$request->input("desc_acc");
                  $des_acc->save();
                  $socialize=new Service_Description;
                  $socialize->service_id=$service->id;
                  $socialize->description_id=11;
                  $socialize->check=$request->input("bool_socialize");
                  $socialize->save();
                  $available=new Service_Description;
                  $available->service_id=$service->id;
                  $available->description_id=12;
                  $available->check=$request->input("bool_available");
                  $available->save();
                  $des_guest=new Service_Description;
                  $des_guest->service_id=$service->id;
                  $des_guest->description_id=13;
                  $des_guest->content=$request->input("desc_guest");
                  $des_guest->save();
                  $des_note=new Service_Description;
                  $des_note->service_id=$service->id;
                  $des_note->description_id=14;
                  $des_note->content=$request->input("desc_note");
                  $des_note->save();
                  return response()->json('Add Step-7');
                }catch(Exception $e){
                    return response()->json($e);
               }
            }else{
               DB::table('service_description')->where('service_id',$service->id)->delete();
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
                  $des_crib->description_id=9;
                  $des_crib->content=$request->input("desc_crib");
                  $des_crib->save();
                  $des_acc=new Service_Description;
                  $des_acc->service_id=$service->id;
                  $des_acc->description_id=10;
                  $des_acc->content=$request->input("desc_acc");
                  $des_acc->save();
                  $socialize=new Service_Description;
                  $socialize->service_id=$service->id;
                  $socialize->description_id=11;
                  $socialize->check=$request->input("bool_socialize");
                  $socialize->save();
                  $available=new Service_Description;
                  $available->service_id=$service->id;
                  $available->description_id=12;
                  $available->check=$request->input("bool_available");
                  $available->save();
                  $des_guest=new Service_Description;
                  $des_guest->service_id=$service->id;
                  $des_guest->description_id=13;
                  $des_guest->content=$request->input("desc_guest");
                  $des_guest->save();
                  $des_note=new Service_Description;
                  $des_note->service_id=$service->id;
                  $des_note->description_id=14;
                  $des_note->content=$request->input("desc_note");
                  $des_note->save();
                  return response()->json('Update Step-7');
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
           'PermitidoFumar'=>'boolean',
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
               $valtrules=Service_Rules::where('service_id',$service->id)->get();
               if(count($valtrules)==0){
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
                 $newrules->check=$request->input("PermitidoFumar");
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
                DB::table('service_rules')->where('service_id',$service->id)->delete();
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
                 $newrules->check=$request->input("PermitidoFumar");
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
                 return response()->json('Update Step-8');

                }
            }else{
                 return response()->json('Service not found');
            }
       }
   }

   public function AddLanguaje(Request $request){
    $rule=[
        'service_id'=>'required|numeric',
        'languaje_id'=>'required|numeric'
    ];
    $validator=Validator::make($request->all(),$rule);
    if ($validator->fails()) {
            return response()->json($validator->errors()->all());
    }else{
        $service=Service::where('id',$request->input("service_id"))->first();
        $languaje=Languaje::where('id',$request->input("languaje_id"))->first();
        if(count($service)>0 && count($languaje)>0){
            $val=Service_Languaje::where("languaje_id",$languaje->id)->where("service_id",$service->id)->first();
            if(count($val)==0){
            $newlanguaje=new Service_Languaje;
            $newlanguaje->service_id=$service->id;
            $newlanguaje->languaje_id=$languaje->id;
            $newlanguaje->save();
            return response()->json($newlanguaje);
            }else{
               return response()->json('It is already selected');
            }
        }else{
               return response()->json('Service or Languaje not found');
        }

       }
   }

   public function AddNewSpaceStep9(Request $request){
        $rule=[
           'service_id' => 'required|numeric|min:1',
           'image'=>'required|image'
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
                    $image_link = 'https://s3.'.env('S3_REGION').'.amazonaws.com/'.env('S3_BUCKET').'/files/images/';
                    // Obtenemos el campo file definido en el formulario
                    $file = $request->file('image');
                    // Creamos un nombre para nuestro imagen
                    $name = 'image'.str_random(20).'_service_'.$service->id.'.'.$file->getClientOriginalExtension();
                    // Movemos el archivo a la caperta temporal
                    $file->move('files/images/',$name);
                    $newruta=new Image();
                    $old_image = str_replace($image_link,'',$newruta->ruta);
                    $s3->putObject([
                    'Bucket' => env('S3_BUCKET'),
                    'Key'    => 'files/images/'.$name,
                    'Body'   => fopen('files/images/'.$name,'r'),
                    'ACL'    => 'public-read'
                    ]);
                     unlink('files/images/'.$name);
                    $newruta->service_id=$service->id;
                    $newruta->ruta=$image_link.$name;
                    $newruta->description=$request->input("description");
                    $newruta->save();
                    // Borramos el arrchivo de la carpeta temporal

                    // Actualizamos la fila thumbnail del usuario respectivo
                    /*DB::table('imagen')->where('service_id', $service->id )->update(['ruta' => $image_link.$name,
                    'description'=>$request->input("description")]);*/

                    return json_encode('completed!', true);
                    return response()->json('Update completed!', true);
                }
                catch (\Exception $e){
                    return response()->json($e->getMessage());
                }
            }else{
              return response()->json('Service not found');
            }

        }
    }

    public function AddNewSpaceStep10(Request $request)
    {$rule=[
           'service_id' => 'required|numeric|min:1',
           'image'=>'required|image',
           'duration_code'=>'required|numeric|min:1',
           'price'=>'required|numeric|min:1',
           'currency_id'=>'required|numeric|min:1'
           ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $service=Service::where('id',$request->input("service_id"))->first();
            if(count($service)>0){
            // Se definen las credenciales del cliente s3 de amazon
                    $s3 = new S3Client([
                        'version'     => env('S3_VERSION'),
                        'region'      => env('S3_REGION'),
                        'credentials' => [
                            'key'    => env('S3_KEY'),
                            'secret' => env('S3_SECRET')
                        ]
                    ]);
                    $image_link = 'https://s3.'.env('S3_REGION').'.amazonaws.com/'.env('S3_BUCKET').'/files/service_images/';
                    // Obtenemos el campo file definido en el formulario
                    $file = $request->file('image');
                    // Creamos un nombre para nuestro imagen
                    $name = 'image'.str_random(20).'_service-image_'.$service->id.'.'.$file->getClientOriginalExtension();
                    // Movemos el archivo a la caperta temporal
                    $file->move('files/service_images/',$name);
                    $newruta=new Image();
                    $old_image = str_replace($image_link,'',$newruta->ruta);
                    $s3->putObject([
                    'Bucket' => env('S3_BUCKET'),
                    'Key'    => 'files/service_images/'.$name,
                    'Body'   => fopen('files/service_images/'.$name,'r'),
                    'ACL'    => 'public-read'
                    ]);
                     unlink('files/service_images/'.$name);
                    $newruta->service_id=$service->id;
                    $newruta->ruta=$image_link.$name;
                    $newruta->description=$request->input("description");
                    $newruta->save();
                    $duration=Duration::select('id')->where('code',$request->input("duration_code"))->get();
                    if(count($duration)>0){
                     $dt = new DateTime();
                    $newhistory=new Price_History;
                    $newhistory->starDate=$dt->format('Y-m-d (H:i:s)');
                     $newhistory->service_id=$service->id;
                     $newhistory->image_id=$newruta->id;
                     $newhistory->price=$request->input("price");
                     $newhistory->currency_id=$request->input("currency_id");
                     $newhistory->save();
                     foreach($duration as $durations){
                         $imageduration=new Image_Duration;
                         $imageduration->image_id=$newruta->id;
                         $imageduration->duration_id=$durations->id;
                         $imageduration->save();
                     }
                     return response()->json('Add Service-Images');
                   }else{
                          return response()->json('Duration not found');
                    }
                  }else{
                        return response()->json('Service not found');
              }
        }
    }

    public function AddNewSpaceStep11(Request $request){
        $rule=[
        'service_id'=>'required|numeric',
        'bool_smoke'=>'boolean',
        'bool_carbon'=>'boolean',
        'bool_first'=>'boolean',
        'bool_safety'=>'boolean',
        'bool_fire'=>'boolean'
    ];
    $validator=Validator::make($request->all(),$rule);
    if ($validator->fails()) {
            return response()->json($validator->errors()->all());
    }else{
        $service=Service::where('id',$request->input("service_id"))->first();
        if(count($service)>0){
          $val=Service_Emergency::where("service_id",$service->id)->first();
          if(count($val)==0){

           $newnote1=new Service_Emergency;
           $newnote1->service_id=$service->id;
           $newnote1->emergency_id=1;
           $newnote1->content=$request->input("desc_anything");
           $newnote1->save();

           $newnote10=new Service_Emergency;
           $newnote10->service_id=$service->id;
           $newnote10->emergency_id=11;
           $newnote10->content=$request->input("desc_anything");
           $newnote10->save();

           $newnote2=new Service_Emergency;
           $newnote2->service_id=$service->id;
           $newnote2->emergency_id=2;
           $newnote2->check=$request->input("bool_smoke");
           $newnote2->save();

           $newnote2=new Service_Emergency;
           $newnote2->service_id=$service->id;
           $newnote2->emergency_id=12;
           $newnote2->check=$request->input("bool_smoke");
           $newnote2->save();

           $newnote3=new Service_Emergency;
           $newnote3->service_id=$service->id;
           $newnote3->emergency_id=3;
           $newnote3->check=$request->input("bool_carbon");
           $newnote3->save();

           $newnote3=new Service_Emergency;
           $newnote3->service_id=$service->id;
           $newnote3->emergency_id=13;
           $newnote3->check=$request->input("bool_carbon");
           $newnote3->save();

           $newnote4=new Service_Emergency;
           $newnote4->service_id=$service->id;
           $newnote4->emergency_id=4;
           $newnote4->check=$request->input("bool_first");
           $newnote4->save();

           $newnote4=new Service_Emergency;
           $newnote4->service_id=$service->id;
           $newnote4->emergency_id=14;
           $newnote4->check=$request->input("bool_first");
           $newnote4->save();

           $newnote5=new Service_Emergency;
           $newnote5->service_id=$service->id;
           $newnote5->emergency_id=5;
           $newnote5->check=$request->input("bool_safety");
           $newnote5->save();

           $newnote5=new Service_Emergency;
           $newnote5->service_id=$service->id;
           $newnote5->emergency_id=15;
           $newnote5->check=$request->input("bool_safety");
           $newnote5->save();

           $newnote6=new Service_Emergency;
           $newnote6->service_id=$service->id;
           $newnote6->emergency_id=6;
           $newnote6->check=$request->input("bool_fire");
           $newnote6->save();

           $newnote6=new Service_Emergency;
           $newnote6->service_id=$service->id;
           $newnote6->emergency_id=16;
           $newnote6->check=$request->input("bool_fire");
           $newnote6->save();

           $newnote7=new Service_Emergency;
           $newnote7->service_id=$service->id;
           $newnote7->emergency_id=7;
           $newnote7->content=$request->input("desc_fire");
           $newnote7->save();

           $newnote7=new Service_Emergency;
           $newnote7->service_id=$service->id;
           $newnote7->emergency_id=17;
           $newnote7->content=$request->input("desc_fire");
           $newnote7->save();

           $newnote8=new Service_Emergency;
           $newnote8->service_id=$service->id;
           $newnote8->emergency_id=8;
           $newnote8->content=$request->input("desc_alarm");
           $newnote8->save();

           $newnote8=new Service_Emergency;
           $newnote8->service_id=$service->id;
           $newnote8->emergency_id=18;
           $newnote8->content=$request->input("desc_alarm");
           $newnote8->save();

           $newnote9=new Service_Emergency;
           $newnote9->service_id=$service->id;
           $newnote9->emergency_id=9;
           $newnote9->content=$request->input("desc_gas");
           $newnote9->save();

           $newnote9=new Service_Emergency;
           $newnote9->service_id=$service->id;
           $newnote9->emergency_id=19;
           $newnote9->content=$request->input("desc_gas");
           $newnote9->save();

           $newnote10=new Service_Emergency;
           $newnote10->service_id=$service->id;
           $newnote10->emergency_id=10;
           $newnote10->content=$request->input("desc_exit");
           $newnote10->save();

           $newnote10=new Service_Emergency;
           $newnote10->service_id=$service->id;
           $newnote10->emergency_id=20;
           $newnote10->content=$request->input("desc_exit");
           $newnote10->save();

           return response()->json('Add Note emergency');
         }else{
                $val=DB::table('service_emergency')->where('service_id',$service->id)->delete();

                $newnote1=new Service_Emergency;
           $newnote1->service_id=$service->id;
           $newnote1->emergency_id=1;
           $newnote1->content=$request->input("desc_anything");
           $newnote1->save();

           $newnote10=new Service_Emergency;
           $newnote10->service_id=$service->id;
           $newnote10->emergency_id=11;
           $newnote10->content=$request->input("desc_anything");
           $newnote10->save();

           $newnote2=new Service_Emergency;
           $newnote2->service_id=$service->id;
           $newnote2->emergency_id=2;
           $newnote2->check=$request->input("bool_smoke");
           $newnote2->save();

           $newnote2=new Service_Emergency;
           $newnote2->service_id=$service->id;
           $newnote2->emergency_id=12;
           $newnote2->check=$request->input("bool_smoke");
           $newnote2->save();

           $newnote3=new Service_Emergency;
           $newnote3->service_id=$service->id;
           $newnote3->emergency_id=3;
           $newnote3->check=$request->input("bool_carbon");
           $newnote3->save();

           $newnote3=new Service_Emergency;
           $newnote3->service_id=$service->id;
           $newnote3->emergency_id=13;
           $newnote3->check=$request->input("bool_carbon");
           $newnote3->save();

           $newnote4=new Service_Emergency;
           $newnote4->service_id=$service->id;
           $newnote4->emergency_id=4;
           $newnote4->check=$request->input("bool_first");
           $newnote4->save();

           $newnote4=new Service_Emergency;
           $newnote4->service_id=$service->id;
           $newnote4->emergency_id=14;
           $newnote4->check=$request->input("bool_first");
           $newnote4->save();

           $newnote5=new Service_Emergency;
           $newnote5->service_id=$service->id;
           $newnote5->emergency_id=5;
           $newnote5->check=$request->input("bool_safety");
           $newnote5->save();

           $newnote5=new Service_Emergency;
           $newnote5->service_id=$service->id;
           $newnote5->emergency_id=15;
           $newnote5->check=$request->input("bool_safety");
           $newnote5->save();

           $newnote6=new Service_Emergency;
           $newnote6->service_id=$service->id;
           $newnote6->emergency_id=6;
           $newnote6->check=$request->input("bool_fire");
           $newnote6->save();

           $newnote6=new Service_Emergency;
           $newnote6->service_id=$service->id;
           $newnote6->emergency_id=16;
           $newnote6->check=$request->input("bool_fire");
           $newnote6->save();

           $newnote7=new Service_Emergency;
           $newnote7->service_id=$service->id;
           $newnote7->emergency_id=7;
           $newnote7->content=$request->input("desc_fire");
           $newnote7->save();

           $newnote7=new Service_Emergency;
           $newnote7->service_id=$service->id;
           $newnote7->emergency_id=17;
           $newnote7->content=$request->input("desc_fire");
           $newnote7->save();

           $newnote8=new Service_Emergency;
           $newnote8->service_id=$service->id;
           $newnote8->emergency_id=8;
           $newnote8->content=$request->input("desc_alarm");
           $newnote8->save();

           $newnote8=new Service_Emergency;
           $newnote8->service_id=$service->id;
           $newnote8->emergency_id=18;
           $newnote8->content=$request->input("desc_alarm");
           $newnote8->save();

           $newnote9=new Service_Emergency;
           $newnote9->service_id=$service->id;
           $newnote9->emergency_id=9;
           $newnote9->content=$request->input("desc_gas");
           $newnote9->save();

           $newnote9=new Service_Emergency;
           $newnote9->service_id=$service->id;
           $newnote9->emergency_id=19;
           $newnote9->content=$request->input("desc_gas");
           $newnote9->save();

           $newnote10=new Service_Emergency;
           $newnote10->service_id=$service->id;
           $newnote10->emergency_id=10;
           $newnote10->content=$request->input("desc_exit");
           $newnote10->save();

           $newnote10=new Service_Emergency;
           $newnote10->service_id=$service->id;
           $newnote10->emergency_id=20;
           $newnote10->content=$request->input("desc_exit");
           $newnote10->save();

            return response()->json('Update Note emergency');
        }

        }else{
            return response()->json('Service not found');
        }
    }
    }

    public function AddDate(Request $request){
         $rule=[
        'service_id'=>'required|numeric',
        'date'=>'required|date_format:Y-m-d|after:today',
        'lock'=>'bool'
    ];
    //dd($request->all);
    $validator=Validator::make($request->all(),$rule);
    if ($validator->fails()) {
            return response()->json($validator->errors()->all());
    }else{
        $service=Service::where('id',$request->input("service_id"))->first();
        if(count($service)>0){
           $val=Availability::where('service_id',$service->id)->where('day',$request->input("date"))->first();
           if(count($val)==0){
             $newdate=new Availability;
             $newdate->day=$request->input("date");
             $newdate->service_id=$request->input("service_id");
             $newdate->lock=$request->input("lock");
             $newdate->save();
             return response()->json($newdate);
           }else{
             $valid=DB::table('availability')->where('service_id',$service->id)->where('day',$request->input("date"))->update(
                             ['lock'=>$request->input("lock"),
                             ]);
            $val=Availability::where('service_id',$service->id)->where('day',$request->input("date"))->first();
             return response()->json($val);
           }
        }else{
            return response()->json('Service not found');
        }
    }
   }

   public function UpdateDate(Request $request){

    $rule=[
        'service_id'=>'required|numeric',
        'date'=>'required|date_format:Y-m-d',
        'lock'=>'bool'
    ];
    //return $request->all();
    $validator=Validator::make($request->all(),$rule);
    if ($validator->fails()) {
            return response()->json($validator->errors()->all());
    }else{
       $service=Service::where('id',$request->input("service_id"))->first();
       if(count($service)>0){
          $valid=Availability::where('service_id',$service->id)->where('day',$request->input("date"))->first();
          if(count($valid)>0){
            $val=DB::table('availability')->where('service_id',$service->id)->where('day',$request->input("date"))->update(
                            ['lock'=>$request->input("lock"),
                            ]);

            return response()->json($valid);
          }else{
            return response()->json('Date not found');
          }
      }else{
        return response()->json('Service not found');
      }
    }
   }

   public function GetDate(Request $request){
    $rule=[
        'service_id'=>'required|numeric',
    ];
    $validator=Validator::make($request->all(),$rule);
    if ($validator->fails()) {
            return response()->json($validator->errors()->all());
    }else{

       $service=DB::table('availability')->where('service_id','=',$request->input("service_id"))->where('lock','=',1)->get();
       if(count($service)>0){
            return response()->json($service);
        }else{
            return response()->json('Nothing found');
        }
    }
   }

   public function DeleteLanguaje(Request $request){
        $rule=[
        'service_id'=>'required|numeric',
        'languaje_id'=>'required|numeric'
    ];
    $validator=Validator::make($request->all(),$rule);
    if ($validator->fails()) {
            return response()->json($validator->errors()->all());
    }else{
         $service=Service::where('id',$request->input("service_id"))->first();
       if(count($service)>0){
                $val=DB::table('service_languaje')->where('languaje_id',$request->input("languaje_id"))->where('service_id',$service->id)->delete();
                if($val==0){
                   return response()->json('Languaje Not Found');
                }else{
                   return response()->json('Languaje Delete!!');
                }
       }else{
          return response()->json('Service not found');
       }
     }
   }

 public function AddNewEmergency(Request $request)
 {
     $rule=[
        'service_id'=>'required|numeric',
        'number'=>'required|numeric|min:1',
        'name'=>'required|string'
    ];
    $validator=Validator::make($request->all(),$rule);
    if ($validator->fails()) {
            return response()->json($validator->errors()->all());
    }else{
        $service=Service::where('id',$request->input("service_id"))->first();
        if(count($service)>0){
            $newnumber=New Emergency_Number;
            $newnumber->service_id=$service->id;
            $newnumber->name=$request->input("name");
            $newnumber->number=$request->input("number");
            $newnumber->save();
            return response()->json($newnumber);
        }else{
            return response()->json('Service not found');
        }
    }
 }

 public function DeleteNewEmergency(Request $request)
 { $rule=[
        'service_id'=>'numeric',
        'number_id'=>'numeric'
    ];
    $validator=Validator::make($request->all(),$rule);
    if ($validator->fails()) {
            return response()->json($validator->errors()->all());
    }else{
       $number=DB::table('number_emergency')->where('service_id',$request->input("service_id"))->where('id',$request->input("number_id"))->delete();
       if($number!=0){
             return response()->json('Number Emergency Delete!');
       }else{
              return response()->json('Number Emergency not found');
       }
    }

 }

}
