<?php

	namespace App\Http\Controllers;

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
	use App\Models\Service_Type_Category;
	use App\Models\Service_Category;
	use App\Models\Type_Category;
	use App\Models\Guest;
	use App\Models\Service;
	use App\Models\User;
	use App\Models\Category;
	use App\Models\Languaje;
	use App\Models\Service_Languaje;
	use App\Models\Check_in;
	use App\Models\Price_history_has_duration;
	use App\Models\Duration;
	use App\Models\Payment;
	use App\Models\Service_Payment;
	use App\Models\Price_History;
	use App\Models\Amenite;
	use App\Models\State;
	use App\Models\Check_out;
	use App\Models\City;
	use App\Models\Country;
	use App\Models\Service_Rules;
	use App\Models\Service_Reservation;
	use App\Models\Service_Description;
	use App\Models\SpecialDate;
	use App\Models\Service_Amenite;
	use App\Models\Service_Type;
	use App\Models\Image;
	use App\Models\Type;
	use App\Models\Service_Calendar;
	use App\Models\Service_Accommodation;
	use App\Models\Calendar;
	use App\Models\Availability;
	use App\Models\Emergency_Number;
	use App\Models\Service_Emergency;
	use App\Models\Image_Duration;

	class ServiceController extends Controller {
		//Muestra todo los service
	    public function ReadService(){
	    //Se obtiene todos los servicios que se crean
	    return Service::all();
    	}

    	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    //Agrega un servicio(web)
	    public function AddNewServiceStep(Request $request){
	         $rule=[
	            'user_id' => 'required|numeric|min:1',
	            'category_code'=>'required|numeric|min:1',
	        ];
	        $validator=Validator::make($request->all(),$rule);
	        if ($validator->fails()) {
	            return response()->json($validator->errors()->all());
	        }else{
              $user=User::select()->where('id',$request->input("user_id"))->first();
              $code=4;
              $category=Category::select('id')->where('code',$code)->get();
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


	     public function ReturnStep(Request $request){
		           $rule=[
		           'service_id' => 'required|numeric|min:1'
		      ];
		      $validator=Validator::make($request->all(),$rule);
		      if ($validator->fails()) {
		            return response()->json($validator->errors()->all());
		      }else{
		             $getreturn = DB::table('service')->join('service_category','service.id','=','service_category.service_id')
		              ->join('service_category','service.id','=','service_category.service_id')
		              ->join('category','category.id','=','service_category.category_id')
		              ->where('service_id','=',$request->input("service_id"))
		              ->where('service_category.category_id','=','4')
		              ->where('category.languaje','=',$request->input("languaje"))
		              ->select('service.id','service.date','category.name')
		             ->get();
		             if(count($getreturn)>0){
		                    return response()->json($getreturn);
		             }else{
		                     return response()->json('Return Not Found');
		             }

		      }
		    }

		    //Agregar Service(service-step1)-Web
	    public function AddNewServiceStep1(Request $request){
	        $rule=[
	            // Comente esto, ya que aun no poseo ningun id service
	            'service_id' => 'required|numeric|min:1',
	            'recreational' => 'boolean',
	            'family' => 'boolean',
	            'sporty' => 'boolean',
	            'category' => 'boolean',
	            'category2' => 'boolean',
	            'num_guest_day' => 'required|numeric|min:0',
	            'num_guest_service' => 'required|numeric|min:0',
	            'time_service' =>'required|numeric'
	        ];
	        $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
            	 $service=Service::where('id',$request->input("service_id"))->first();
            	 if(count($service)>0){
            	 	$valtypecate=Service_Type_Category::where('service_id',$service->id)->get();
            	 	if(count($valtypecate)==0){
	                try{
	                	 $newrules=new Service_Type_Category;
		                 $newrules->service_id=$service->id;
		                 $newrules->type_categories_id=1;
		                 $newrules->check=$request->input("recreational");
		                 $newrules->save();
	                	 $newrules=new Service_Type_Category;
		                 $newrules->service_id=$service->id;
		                 $newrules->type_categories_id=2;
		                 $newrules->check=$request->input("family");
		                 $newrules->save();
	                	 $newrules=new Service_Type_Category;
		                 $newrules->service_id=$service->id;
		                 $newrules->type_categories_id=3;
		                 $newrules->check=$request->input("sporty");
		                 $newrules->save();
	                	 $newrules=new Service_Type_Category;
		                 $newrules->service_id=$service->id;
		                 $newrules->type_categories_id=4;
		                 $newrules->check=$request->input("category");
		                 $newrules->save();
	                	 $newrules=new Service_Type_Category;
		                 $newrules->service_id=$service->id;
		                 $newrules->type_categories_id=5;
		                 $newrules->check=$request->input("category2");
		                 $newrules->save();
		                 $newguest=new Guest;
		                 $newguest->service_id=$service->id;
		                 $newguest->num_guest_service=$request->input("num_guest_service");
		                 $newguest->num_guest_day=$request->input("num_guest_day");
		                 $newguest->entry=$request->input("entry");
		                 $newguest->until=$request->input("until");
		                 $newguest->time_service=$request->input("time_service");
		                 $newguest->duration=$request->input("duration");
		                 $newguest->save();

		                 return response()->json('Add Step-1');
	                }catch(Exception $e){
	                       return response()->json($e);
	                }
	            }else{
	                DB::table('service_type_category')->where('service_id',$service->id)->delete();
	                 $newrules=new Service_Type_Category;
	                 $newrules->service_id=$service->id;
	                 $newrules->type_categories_id=1;
	                 $newrules->check=$request->input("recreational");
	             	 $newrules->save();
	            	 $newrules=new Service_Type_Category;
	                 $newrules->service_id=$service->id;
	                 $newrules->type_categories_id=2;
	                 $newrules->check=$request->input("family");
	                 $newrules->save();
	            	 $newrules=new Service_Type_Category;
	                 $newrules->service_id=$service->id;
	                 $newrules->type_categories_id=3;
	                 $newrules->check=$request->input("sporty");
	                 $newrules->save();
	            	 $newrules=new Service_Type_Category;
	                 $newrules->service_id=$service->id;
	                 $newrules->type_categories_id=4;
	                 $newrules->check=$request->input("category");
	                 $newrules->save();
	            	 $newrules=new Service_Type_Category;
	                 $newrules->service_id=$service->id;
	                 $newrules->type_categories_id=5;
	                 $newrules->check=$request->input("category2");
	                 $newrules->save();
	                 DB::table('guests')->where('service_id',$service->id)->delete();
	                 $newguest= new Guest;
	                 $newguest->service_id= $service->id;
	                 $newguest->num_guest_service= $request->input("num_guest_service");
	                 $newguest->num_guest_day= $request->input("num_guest_day");
	                 $newguest->entry= $request->input("entry");
	                 $newguest->until= $request->input("until");
	                 $newguest->time_service= $request->input("time_service");
	                 $newguest->duration= $request->input("duration");
	                 $newguest->save();

	                 return response()->json('Update Step-1');
	             }
	    	}else{
                 return response()->json('Service not found');
            	}
			}	
		}

		


		public function ReturnStep1(Request $request){
		            $rule=[
		           'service_id' => 'required|numeric|min:1',
		      ];
		      $validator=Validator::make($request->all(),$rule);
		      if ($validator->fails()) {
		            return response()->json($validator->errors()->all());
		      }else{
		      	$getstep1 = DB::table('service')
		      	->join('service_type_category','service.id','=','service_type_category.service_id')
		      	->join('type_categories','type_categories.code','=','service_type_category.type_categories_id')
		      	->join('guests','service.id','=','guests.service_id')
		      	->where('service.id','=',$request->input("service_id"))
		      	->select('service.id','service_type_category.type_categories_id','service_type_category.check as Check','guests.entry as Entry','guests.until as Until','guests.num_guest_day as num_guest_day','guests.num_guest_service as num_guest_service','guests.time_service as time_service','guests.duration as duration')
              	->get() ;
	              if(count($getstep1)>0){
	                    return response()->json($getstep1);
	              }else{
	                    return response()->json('Return Not Found');
	              }

		      }
		}


			//Agregar Service(service-step2)-Web
	    public function AddNewServiceStep2(Request $request){
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
	                if(count($service)>0 && count($payment)==0 && count($duration)==0){
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
                      DB::table('check_out')->where('service_id',$service->id)->delete();
                      $newcheck_out=new Check_out;
                      $newcheck_out->departure_time=$request->input("until");
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
	                      return response()->json('Add Step-2');
	                    }catch(exception $e){
	                       return response()->json($e);
	                    }
	                }else{
	                	DB::table('price_history')->where('service_id',$service->id)->delete();
	                      $newhistory=new Price_History;
	                      $dt = new DateTime();
	                      $newhistory->starDate=$dt->format('Y-m-d (H:i:s)');
	                      $newhistory->service_id=$service->id;
	                      $newhistory->price=$request->input("price");
	                      $newhistory->currency_id=$request->input("currency_id");
	                      $newhistory->save();
	                      DB::table('check_in')->where('service_id',$service->id)->delete();
	                      $newcheck_in=new Check_in;
	                      $newcheck_in->time_entry=$request->input("time_entry");
	                      $newcheck_in->until=$request->input("until");
	                      $newcheck_in->service_id=$service->id;
	                      $newcheck_in->save();
                      DB::table('check_out')->where('service_id',$service->id)->delete();
                      $newcheck_out=new Check_out;
                      $newcheck_out->departure_time=$request->input("until");
                      $newcheck_out->service_id=$service->id;
                      $newcheck_out->save();
	                      DB::table('service_payment')->where('service_id',$service->id)->delete();
	                     foreach($payment as $payments){
	                         $newpayment=new Service_Payment;
	                         $newpayment->service_id=$service->id;
	                         $newpayment->payment_id=$payments->id;
	                         $newpayment->save();
	                       }
	                       DB::table('price_history_has_duration')->where('price_history_service_id',$service->id)->delete();
	                        foreach($duration as $durations){
	                         $newpriceduration=new Price_history_has_duration;
	                         $newpriceduration->price_history_starDate=$newhistory->starDate;
	                         $newpriceduration->price_history_service_id=$newhistory->service_id;
	                         $newpriceduration->duration_id=$durations->id;
	                         $newpriceduration->save();
	                       }
	                    return response()->json('Update Step 2');
	                }
	                return response()->json('Service Not Found');
	            }
	    }


	    public function ReturnStep2(Request $request){
	           $rule=[
	           'service_id' => 'required|numeric',
	           'languaje' => 'required'
	      ];
	    $validator=Validator::make($request->all(),$rule);
	      if ($validator->fails()) {
	            return response()->json($validator->errors()->all());
	      }else{
	         $getstep2=DB::table('service')
	        ->join('service_payment','service_payment.service_id','=','service.id')
	        ->join('payment','payment.code','=','service_payment.payment_id')
	        ->join('price_history','price_history.service_id','=','service.id')
	        ->join('price_history_has_duration','price_history_has_duration.price_history_service_id','=','price_history.service_id')
	        ->join('duration','duration.id','=','price_history_has_duration.duration_id')
	        ->join('check_in','check_in.service_id','=','service.id')
	        ->join('currency','currency.id','=','price_history.currency_id')
	        ->where('service.id','=',$request->input('service_id'))
	        ->where('payment.languaje','=',$request->input("languaje"))
	        ->where('duration.languaje','=',$request->input("languaje"))
	        ->select('payment.type as Type-Payment','duration.type as Type-Duration'
	        ,'price_history.price as Price','currency.currency_iso as Currency-Name'
	        ,'check_in.time_entry as Time-Entry','check_in.until as Until')
	        ->get();
	         if(count($getstep2)>0){
	                return response()->json($getstep2);
	        }else{
	                return response()->json("Not Found");
	        }
	      }
	    }
	    
	 	public function AddNewServiceStep3(Request $request){
	          $rule=[
	           'service_id' => 'required|numeric|min:1',
	           'AptoDe2a12'=>'boolean',
	           'AptoDe0a2'=>'boolean',
	           'SeadmitenMascotas'=>'boolean',
	           'PermitidoFumar'=>'boolean',
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
	               $valdescription=Service_Description::where('service_id',$service->id)->get();
	               if(count($valtrules)==0 && count($valdescription)==0){
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
	                 $newrules->rules_id=6;
	                 $newrules->description=$request->input("Desc_Otro_Evento");
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
	                 $des_guest=new Service_Description;
	                 $des_guest->service_id=$service->id;
	                 $des_guest->description_id=13;
	                 $des_guest->content=$request->input("desc_guest");
	                 $des_guest->save();
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
	                  $des_guest=new Service_Description;
	                  $des_guest->service_id=$service->id;
	                  $des_guest->description_id=13;
	                  $des_guest->content=$request->input("desc_guest");
	                  $des_guest->save();
	                 return response()->json('Add Step-3');
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
	                 $newrules->rules_id=6;
	                 $newrules->description=$request->input("Desc_Otro_Evento");
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
	                 $serv_desc = Service_Description::all();
	                 foreach ($serv_desc as $sdl ) {
	                 	if($sdl->description_id == 1 OR $sdl->description_id == 8 OR $sdl->description_id == 13){

	                 	DB::table('service_description')->where('service_id',$service->id)->where('description_id',$sdl->description_id)->delete();
	                 	}
	                 }

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
	                  $des_guest=new Service_Description;
	                  $des_guest->service_id=$service->id;
	                  $des_guest->description_id=13;
	                  $des_guest->content=$request->input("desc_guest");
	                  $des_guest->save();
	                 return response()->json('Update Step-3');

	                }
	            }else{
	                 return response()->json('Service not found');
	            }
	       }
	   }

		public function ReturnStep3(Request $request){
	            $rule=[
	           'service_id' => 'required|numeric'
	      ];
	    $validator=Validator::make($request->all(),$rule);
	      if ($validator->fails()) {
	            return response()->json($validator->errors()->all());
	      }else{
	        $getstep3=DB::table('service')
	        ->join('service_rules','service_rules.service_id','=','service.id')
	        ->join('house_rules','house_rules.id','=','service_rules.rules_id')
	        ->join('service_description','service_description.service_id','=','service.id')
        	->join('description','description.id','=','service_description.description_id')
	        ->where('service.id','=',$request->input("service_id"))
	        ->select('service_rules.description as Description','service_rules.check as Check','service_rules.rules_id','service_description.content','service_description.description_id')
	        ->get();
	        if(count($getstep3)>0){
	                return response()->json($getstep3);
	        }else{
	                return response()->json("Not Found");
	        }
	      }
	    }

	   public function AddNewServiceStep4(Request $request){
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
	                    	'version' 	  =>env('S3_VERSION'),
	                        'region'      => env('S3_REGION'),
	                        'http'    => [
						        'verify' => false
						    ],
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
	                    // unlink('files/images/'.$name);
	                    $newruta->service_id=$service->id;
	                    $newruta->ruta=$image_link.$name;
	                    $newruta->description=$request->input("description");
	                    $newruta->save();
	                    // Borramos el arrchivo de la carpeta temporal

	                    // Actualizamos la fila thumbnail del usuario respectivo
	                    /*DB::table('imagen')->where('service_id', $service->id )->update(['ruta' => $image_link.$name,
	                    'description'=>$request->input("description")]);*/

	                    return json_encode('Update completed!', true);
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

	    public function ReturnStep4(Request $request)
	    {

	       $rule=[
	          'service_id' => 'required|numeric',
	          //'image_id'=>'required'
	        ];
	      $validator=Validator::make($request->all(),$rule);
	      if ($validator->fails()) {
	            return response()->json($validator->errors()->all());
	      }else{
	          $image=DB::table('image')->where('service_id',$request->input("service_id"))/*->where('id',$request->input("image_id"))*/->get();
	            if(count($image)>0){
	                  return response()->json($image);
	          }else{
	                return response()->json("Not Found");
	          }
	      }
	    }

		    
	     public function AddNewSpaceStep5(Request $request){
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
	                              $serv_desloc = Service_Description::all();
	                                  foreach ($serv_desloc as $sdl) {
	                                    if($sdl->description_id >= 2 ){
	                                      if($sdl->description_id <= 7){
	                                        DB::table('service_description')->where('service_id',$servicespace->id)->where('description_id',$sdl->description_id)->delete();
	                                      }
	                                    }
	                                  }
	                                  // DB::table('service_description')->where('service_id',$servicespace->id)->delete();
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


	    public function ReturnStep5(Request $request){
	           $rule=[
	           'service_id' => 'required|numeric'
	      ];
	      $validator=Validator::make($request->all(),$rule);
	      if ($validator->fails()) {
	            return response()->json($validator->errors()->all());
	      }else{
	            $getstep5 = DB::table('service')
	             ->join('service_description','service_description.service_id','=','service.id')
	            ->join('description','service_description.description_id','=','description.id')
	            ->join('city','service.city_id','=','city.id')
	            ->join('state','city.state_id','=','state.id')
	            ->join('country','country.id','=','state.country_id')
	            ->where('service.id','=',$request->input("service_id"))
	            ->select('service.id','service.zipcode','city.name as city','state.name as state','state.id as state_id','country.name as country','country.id as country_id','description.type','service_description.content')
	            ->get();
	            if(count($getstep5)>0){
	                 return response()->json($getstep5);
	            }else{

	             return response()->json('Not Found');
	            }
	      }
	    }

		public function AddNewSpaceStep6(Request $request){
		        $rule=[
		        'service_id'=>'required|numeric',
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
		           $newnote1->emergency_id=21;
		           $newnote1->content=$request->input("desc_anything");
		           $newnote1->save();

		           $newnote2=new Service_Emergency;
		           $newnote2->service_id=$service->id;
		           $newnote2->emergency_id=22;
		           $newnote2->check=$request->input("bool_alcohol");
		           $newnote2->save();

		           $newnote3=new Service_Emergency;
		           $newnote3->service_id=$service->id;
		           $newnote3->emergency_id=23;
		           $newnote3->check=$request->input("bool_certification");
		           $newnote3->content=$request->input("desc_requiments");
		           $newnote3->save();

		           $newnote4=new Service_Emergency;
		           $newnote4->service_id=$service->id;
		           $newnote4->emergency_id=24;
		           $newnote4->check=$request->input("bool_aditional");
		           $newnote4->save();

		           $newnote5=new Service_Emergency;
		           $newnote5->service_id=$service->id;
		           $newnote5->emergency_id=25;
		           $newnote5->content=$request->input("desc_aditional");
		           $newnote5->save();

		           $newnote6=new Service_Emergency;
		           $newnote6->service_id=$service->id;
		           $newnote6->emergency_id=26;
		           $newnote6->check=$request->input("bool_norequiments");
		           $newnote6->save();

		           $newnote7=new Service_Emergency;
		           $newnote7->service_id=$service->id;
		           $newnote7->emergency_id=27;
		           $newnote7->check=$request->input("bool_food");
		           $newnote7->content=$request->input("desc_food");
		           $newnote7->save();

		           $newnote8=new Service_Emergency;
		           $newnote8->service_id=$service->id;
		           $newnote8->emergency_id=28;
		           $newnote8->check=$request->input("bool_snacks");
		           $newnote8->content=$request->input("desc_snacks");
		           $newnote8->save();

		           $newnote9=new Service_Emergency;
		           $newnote9->service_id=$service->id;
		           $newnote9->emergency_id=29;
		           $newnote9->check=$request->input("bool_drinks");
		           $newnote9->content=$request->input("desc_drinks");
		           $newnote9->save();

		           $newnote10=new Service_Emergency;
		           $newnote10->service_id=$service->id;
		           $newnote10->emergency_id=30;
		           $newnote10->check=$request->input("bool_transport");
		           $newnote10->content=$request->input("desc_transport");
		           $newnote10->save();

		           $newnote11=new Service_Emergency;
		           $newnote11->service_id=$service->id;
		           $newnote10->emergency_id=31;
		           $newnote11->check=$request->input("bool_accommodation");
		           $newnote11->content=$request->input("desc_accommodation");
		           $newnote11->save();

		           $newnote12=new Service_Emergency;
		           $newnote12->service_id=$service->id;
		           $newnote12->emergency_id=32;
		           $newnote12->check=$request->input("bool_other");
		           $newnote12->content=$request->input("desc_other");
		           $newnote12->save();

		           $newnote13=new Service_Emergency;
		           $newnote13->service_id=$service->id;
		           $newnote13->emergency_id=33;
		           $newnote13->check=$request->input("bool_nooffers");
		           $newnote13->save();

		           return response()->json('Add Note emergency');
		         }else{
		                DB::table('service_emergency')->where('service_id',$service->id)->delete();

		           $newnote1=new Service_Emergency;
		           $newnote1->service_id=$service->id;
		           $newnote1->emergency_id=21;
		           $newnote1->content=$request->input("desc_anything");
		           $newnote1->save();

		           $newnote2=new Service_Emergency;
		           $newnote2->service_id=$service->id;
		           $newnote2->emergency_id=22;
		           $newnote2->check=$request->input("bool_alcohol");
		           $newnote2->save();

		           $newnote3=new Service_Emergency;
		           $newnote3->service_id=$service->id;
		           $newnote3->emergency_id=23;
		           $newnote3->check=$request->input("bool_certification");
		           $newnote3->content=$request->input("desc_requiments");
		           $newnote3->save();

		           $newnote4=new Service_Emergency;
		           $newnote4->service_id=$service->id;
		           $newnote4->emergency_id=24;
		           $newnote4->check=$request->input("bool_aditional");
		           $newnote4->save();

		           $newnote5=new Service_Emergency;
		           $newnote5->service_id=$service->id;
		           $newnote5->emergency_id=25;
		           $newnote5->content=$request->input("desc_aditional");
		           $newnote5->save();

		           $newnote6=new Service_Emergency;
		           $newnote6->service_id=$service->id;
		           $newnote6->emergency_id=26;
		           $newnote6->check=$request->input("bool_norequiments");
		           $newnote6->save();

		           $newnote7=new Service_Emergency;
		           $newnote7->service_id=$service->id;
		           $newnote7->emergency_id=27;
		           $newnote7->check=$request->input("bool_food");
		           $newnote7->content=$request->input("desc_food");
		           $newnote7->save();

		           $newnote8=new Service_Emergency;
		           $newnote8->service_id=$service->id;
		           $newnote8->emergency_id=28;
		           $newnote8->check=$request->input("bool_snacks");
		           $newnote8->content=$request->input("desc_snacks");
		           $newnote8->save();

		           $newnote9=new Service_Emergency;
		           $newnote9->service_id=$service->id;
		           $newnote9->emergency_id=29;
		           $newnote9->check=$request->input("bool_drinks");
		           $newnote9->content=$request->input("desc_drinks");
		           $newnote9->save();

		           $newnote10=new Service_Emergency;
		           $newnote10->service_id=$service->id;
		           $newnote10->emergency_id=30;
		           $newnote10->check=$request->input("bool_transport");
		           $newnote10->content=$request->input("desc_transport");
		           $newnote10->save();

		           $newnote11=new Service_Emergency;
		           $newnote11->service_id=$service->id;
		           $newnote10->emergency_id=31;
		           $newnote11->check=$request->input("bool_accommodation");
		           $newnote11->content=$request->input("desc_accommodation");
		           $newnote11->save();

		           $newnote12=new Service_Emergency;
		           $newnote12->service_id=$service->id;
		           $newnote12->emergency_id=32;
		           $newnote12->check=$request->input("bool_other");
		           $newnote12->content=$request->input("desc_other");
		           $newnote12->save();

		           $newnote13=new Service_Emergency;
		           $newnote13->service_id=$service->id;
		           $newnote13->emergency_id=33;
		           $newnote13->check=$request->input("bool_nooffers");
		           $newnote13->save();

		            return response()->json('Update Note emergency');
		        }

		        }else{
		            return response()->json('Service not found');
		        }
		    }
		    }


		    public function ReturnStep6(Request $request)
		    {
		          $rule=[
		           'service_id' => 'required|numeric',
		           'languaje'=>'required'
		        ];
		      $validator=Validator::make($request->all(),$rule);
		      if ($validator->fails()) {
		            return response()->json($validator->errors()->all());
		      }else{
		             $getstep11=DB::table('service')
		                          ->join('service_emergency','service_emergency.service_id','=','service.id')
		                          ->join('note_emergency','note_emergency.id','=','service_emergency.emergency_id')
		                          ->where('service.id','=',$request->input("service_id"))
		                          ->where('note_emergency.languaje','=',$request->input("languaje"))
		                          ->select('service_emergency.content','service_emergency.check','note_emergency.type','service_emergency.emergency_id')
		                          ->get();
		          if(count($getstep11)>0){
		                  return response()->json($getstep11);
		          }else{
		                return response()->json("Not Found");
		          }
		      }
		    }


		    public function GetOverviews(Request $request)
		    {$rule=[
		           'service_id' => 'required|numeric',
		           'languaje'=>'required',
		        ];
		      $validator=Validator::make($request->all(),$rule);
		      if ($validator->fails()) {
		            return response()->json($validator->errors()->all());
		      }else{

		             $previews=DB::table('service')
		             ->join('user','user.id','=','service.user_id')
		             ->join('city','service.city_id','=','city.id')
		             ->join('state','city.state_id','=','state.id')
		             ->join('country','country.id','=','state.country_id')
		             ->join('service_description','service_description.service_id','=','service.id')
		             ->join('description','description.id','=','service_description.description_id')
		             ->join('check_in','check_in.service_id','=','service.id')
		             ->join('service_category','service_category.service_id','=','service.id')
		             ->join('category','category.id','=','service_category.category_id')
		             ->join('service_payment','service_payment.service_id','=','service.id')
		             ->join('payment','payment.id','=','service_payment.payment_id')
		             ->where('service.id','=',$request->input("service_id"))
		             ->where('category.languaje','=',$request->input("languaje"))
		             ->where('payment.languaje','=',$request->input("languaje"))
		             ->where('description.id','=',1)
		             ->where('category.code','=',4)
		             ->select('service.user_id', 'user.id as userid','user.avatar','user.name','country.name as country','payment.type as prices','state.name as state','service_description.content as title','check_in.time_entry as check_in','category.name as category','user.lastname','service.id')
		             ->first();
		               if(count($previews)>0){
		                  return response()->json($previews);
		          }else{
		                return response()->json("Not Found");
		          }

		      }

		    }


		    public function GetLocationMap(Request $request)
		    {$rule=[
		           'service_id' => 'required|numeric',
		        ];
		      $validator=Validator::make($request->all(),$rule);
		      if ($validator->fails()) {
		            return response()->json($validator->errors()->all());
		      }else{

		              $previews=DB::table('service')
		              ->join('service_description','service_description.service_id','=','service.id')
		                ->join('city','service.city_id','=','city.id')
		                ->join('state','city.state_id','=','state.id')
		                ->join('country','country.id','=','state.country_id')
		                 ->where('service.id','=',$request->input("service_id"))
		                   ->select('service_description.id','country.name as country','state.name as state','city.name as city')
		                   ->first();
		                     if(count($previews)>0){
		                  return response()->json($previews);
		          }else{
		                return response()->json("Not Found");
		          }

		      }
		    }

		    public function GetLocationMapLongitude(Request $request)
		    {$rule=[
		           'service_id' => 'required|numeric',
		        ];
		      $validator=Validator::make($request->all(),$rule);
		      if ($validator->fails()) {
		            return response()->json($validator->errors()->all());
		      }else{

		              $previews=DB::table('service')
		                ->join('service_description','service_description.service_id','=','service.id')
		                 ->join('description','description.id','=','service_description.description_id')
		                 ->where('service.id','=',$request->input("service_id"))
		                  ->where('description.id','=',6)
		                  //->where('description.id','=',10)
		                   ->select('service_description.content')
		                   ->first();
		                     if(count($previews)>0){
		                  return response()->json($previews);
		          }else{
		                return response()->json("Not Found");
		          }

		      }
		    }

		    public function GetLocationMapLatitude(Request $request)
		    {$rule=[
		           'service_id' => 'required|numeric',
		        ];
		      $validator=Validator::make($request->all(),$rule);
		      if ($validator->fails()) {
		            return response()->json($validator->errors()->all());
		      }else{

		              $previews=DB::table('service')
		                ->join('service_description','service_description.service_id','=','service.id')
		                 ->join('description','description.id','=','service_description.description_id')
		                 ->where('service.id','=',$request->input("service_id"))
		                  ->where('description.id','=',7)
		                  //->where('description.id','=',10)
		                   ->select('service_description.content')
		                   ->first();
		                     if(count($previews)>0){
		                  return response()->json($previews);
		          }else{
		                return response()->json("Not Found");
		          }

		      }
		    }



    }
