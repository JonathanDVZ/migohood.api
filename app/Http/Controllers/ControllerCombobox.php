<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;
use validator;
use App\Models\Category;
use App\Models\Amenite;
use App\Models\Country;
use App\Models\Accommodation;
use App\Models\Calendar;
use App\Models\Duration;
use App\Models\House_Rules;
use App\Models\Type;
use App\Models\Currency;
use App\Models\Service;
use App\Models\Image;
use App\Models\Payment;
use App\Models\Bedroom_Bed;
use App\Models\Bedroom;
use DB;

class ControllerCombobox extends Controller
{
    public function GetCategory(Request $request){
            $rule=[
             'languaje' => 'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
               $category=Category::select('id','name','code')->where('languaje','=',$request->input("languaje"))->get();
            if(count($category)>0){
                  return response()->json($category);
            }else{
                  return response()->json("Category not found");
            }
    }
}

   public function RulesHouse(){
         return House_Rules::all();
    }

    public function GetAccommodation(Request $request){
             $rule=[
             'languaje' => 'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
               $accommodation=Accommodation::select('id','name','code')->where('languaje','=',$request->input("languaje"))->get();
            if(count($accommodation)>0){
                  return response()->json($accommodation);
            }else{
                  return response()->json("Accommodation not found");
            }
       }
    }


    public function GetCalendar(){
          return Calendar::all();
    }


    public function GetDuration(Request $request){
          $rule=[
              //'service_id' => 'required|numeric|min:1',
              'languaje'=>'required'
          ];
          $validator=Validator::make($request->all(),$rule);
          if ($validator->fails()) {
                return response()->json($validator->errors()->all());
          }else{
                $type=Duration::select('id','type','code')->where('languaje','=',$request->input("languaje"))->get();
                if(count($type)>0){
                      return response()->json($type);
                }else{
                      return response()->json("Duration not found");
                }
          }
    }

    public function GetCurrency(Request $request){
        $rule=[
            //'service_id' => 'required|numeric|min:1',
            'languaje'=>'required'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
            $currency=Currency::select('*')->where('language','=',$request->input("languaje"))->get();
            if(count($currency)>0){
                return response()->json($currency);
            }else{
                return response()->json("Currency not found");
            }
        }
    }

    public function GetPayment(Request $request){
        $rule=[
            //'service_id' => 'required|numeric|min:1',
            'languaje'=>'required'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
            $payment=Payment::select('*')->where('languaje','=',$request->input("languaje"))->get();
            if(count($payment)>0){
                return response()->json($payment);
            }else{
                return response()->json("Payment not found");
            }
        }
    }

    public function TypeGet(Request $request){
          $rule=[
                'languaje'=>'required'
          ];
          $validator=Validator::make($request->all(),$rule);
          if ($validator->fails()) {
                return response()->json($validator->errors()->all());
          }else{
                $type=Type::select('id','name','code')->where('category_id','=',1)->where('languaje','=',$request->input("languaje"))->get();
                if(count($type)>0){
                      return response()->json($type);
                }else{
                      return response()->json("Type not found");
                }
          }
    }

    public function GetBeds(Request $request){
        $rule=[
            'service_id' => 'required|numeric|min:1',
            //'languaje'=>'required'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        } else {

            $newbedbedroom=DB::table('bedroom')
                          //->leftjoin('bedroom_bed','bedroom_bed.bedroom_id','=','bedroom.id')
                          //->leftjoin('bed','bed.id','=','bedroom_bed.bed_id')
                          ->where('bedroom.service_id','=',$request->input("service_id"))
                          ->select('bedroom.id as bedroom_id'/*,'bedroom_bed.quantity as bed_quantity'*/)
                          ->get();
            //dd($newbedbedroom);
            if(count($newbedbedroom)>0){
                return response()->json($newbedbedroom);
            }else{
                return response()->json('GetBedroom Not found');
            }

        }
    }

    public function GetBedBedroom(Request $request){
        $rule=[
            'bedroom_id' => 'required|numeric|min:1',
            'languaje'=>'required'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        } else {
            $newbedbedroom=DB::table('bedroom')
                              ->leftjoin('bedroom_bed','bedroom_bed.bedroom_id','=','bedroom.id')
                              ->leftjoin('bed','bed.id','=','bedroom_bed.bed_id')
                              ->where('bedroom.id','=',$request->input("bedroom_id"))
                              ->where('bed.languaje','=',$request->input("languaje"))
                              ->select('bedroom.id as bedroom_id','bedroom_bed.quantity as bed_quantity','bed.type as bed_type','bed.id as bed_id')
                              ->get();
            //dd($newbedbedroom);
            //if(count($newbedbedroom)>0){
                return response()->json($newbedbedroom);
            /*}else{
                return response()->json('GetBedroom Not found');

            }*/
        }
    }

  public function GetBedBedroomData(Request $request){
      $rule=[
          'user_id'=>'required|min:1',
          'bedroom_id'=>'required|min:1',
          'languaje' => 'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
      }else{
        $exist=DB::table('service')
                  ->join('bedroom','bedroom.service_id','=','service.id')
                  ->where('service.user_id','=',$request->input("user_id"))
                  ->where('bedroom.id','=',$request->input("bedroom_id"))
                  ->select('bedroom.id')
                  ->get();
        if(count($exist)>0){
          $newbedbedroomdata=DB::table('service')
                              ->join('bedroom','bedroom.service_id','=','service.id')
                              ->leftjoin('bedroom_bed','bedroom_bed.bedroom_id','=','bedroom.id')
                              ->leftjoin('bed','bed.id','=','bedroom_bed.bed_id')
                              ->where('service.user_id','=',$request->input("user_id"))
                              ->where('bedroom.id','=',$request->input("bedroom_id"))
                              ->where('bed.languaje','=',$request->input("languaje"))
                              ->select('bedroom_bed.*', 'bed.type as type')
                              ->get();
          //if(count($newbedbedroomdata)>0){
          return response()->json($newbedbedroomdata);
          /*}else{
            return response()->json("The user does not have a room or user not found");

          }*/
        }else{
          return response()->json("The user does not have a room or user not found");

        }

      }

  }

  public function GetCountry(){

      $country = DB::table('country')->select('id','name')->get();
      if(count($country)>0){
            return response()->json($country);
      }else{
            return response()->json("Country not found");
      }
  }

  public function GetCity(Request $request){
        $rule=[
           'state_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
              $getrent = DB::table('city')->join('state','city.state_id','=','state.id')
              ->where('state.id','=',$request->input("state_id"))
              ->select('city.id as id','city.name as city')
             ->get();
             if(count($getrent)>0){
               return response()->json($getrent);
             }else{
                return response()->json('state not found');
             }
        }
    }

    public function GetState(Request $request){
      $rule=[
           'country_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
              $getrent = DB::table('country')->join('state','state.country_id','=','country.id')
              ->where('country.id','=',$request->input("country_id"))
              ->select('state.id as id','state.name as state')
             ->get();
             if(count($getrent)>0){
               return response()->json($getrent);
             }else{
                return response()->json('Country not found');
             }
        }
    }

    public function GetSpaceAmenities(Request $request){
      $rule=[
           'languaje' => 'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
            $amenitie = DB::table('amenities')->select('code','name','type_amenities_id')->where('languaje','=',$request->input("languaje"))->where('category_id','=',1)->get();
            if(count($amenitie)>0){
                  return response()->json($amenitie);
            }else{
                  return response()->json("Amenities not found");
            }
      }
    }
    public function ReturnStep(Request $request){
           $rule=[
           'service_id' => 'required|numeric'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
             $getreturn = DB::table('service')->join('service_category','service.id','=','service_category.service_id')
              ->join('category','category.code','=','service_category.category_id')
              ->where('service_id','=',$request->input("service_id"))
              ->where('category.languaje','=',$request->input("languaje"))
              ->select('service.id','service.date','category.name')
             ->get();
             if(count($getreturn)>0){
                    return response()->json($getreturn);
             }else{
                     return response()->json('Not Found');
             }

      }
    }
    public function ReturnStep1(Request $request){
            $rule=[
           'service_id' => 'required|numeric'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
               $getstep1 = DB::table('service')->join('service_accommodation','service.id','=','service_accommodation.service_id')
              ->join('accommodation','service_accommodation.accommodation_id','=','accommodation.id')
              ->join('service_type','service_type.service_id','=','service.id')
              ->join('type','type.id','=','service_type.type_id')
              ->where('service.id','=',$request->input("service_id"))
              ->where('type.languaje','=',$request->input("languaje"))
              ->where('accommodation.languaje','=',$request->input("languaje"))
              ->select('service.id','service.live','type.name as Type','accommodation.name as Accommodation')
              ->first() ;
              if(count($getstep1)>0){
                    return response()->json($getstep1);
              }else{
                    return response()->json('Not Found');
              }
      }

    }

    public function ReturnStep2(Request $request){
             $rule=[
           'service_id' => 'required|numeric'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
            $getstep2 = DB::table('service')->join('bedroom','service.id','=','bedroom.service_id')
            ->where('service.id','=',$request->input("service_id"))
            ->select('service.id','service.num_guest', DB::raw('count(*) as num_bedroom')/*'bedroom.id as num_bedroom'*/)
            ->orderBy('bedroom.id','desc')
            //->take(1)
            ->get();
            if(count($getstep2)>0){
                  return response()->json($getstep2);
            }else{
                  return response()->json('Not Found');
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
        $getstep3=Service::select('id','num_bathroom')->where('id','=',$request->input("service_id"))->first();
        if(count($getstep3)>0){
             return response()->json($getstep3);
        }else{
             return response()->json('Not Found');
        }
      }

    }

    public function ReturnStep4Location(Request $request){
           $rule=[
           'service_id' => 'required|numeric'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
            $getstep4 = DB::table('service')
             ->join('service_description','service_description.service_id','=','service.id')
            ->join('description','service_description.description_id','=','description.id')
            ->join('city','service.city_id','=','city.id')
            ->join('state','city.state_id','=','state.id')
            ->join('country','country.id','=','state.country_id')
            ->where('service.id','=',$request->input("service_id"))
            ->select('service.id','service.zipcode','city.name as city','state.name as state','state.id as state_id','country.name as country','country.id as country_id','description.type','service_description.content')
            ->get();
            if(count($getstep4)>0){
                 return response()->json($getstep4);
            }else{

             return response()->json('Not Found');
            }
      }
    }

    public function GetLanguaje(Request $request){
              $rule=[
           'service_id' => 'required|numeric'
      ];
    $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
                $languaje=DB::table('service')
                ->join('service_languaje','service.id','=','service_languaje.service_id')
                ->join('languaje','languaje.id','=','service_languaje.languaje_id')
                ->where("service.id","=",$request->input("service_id"))
                ->select("languaje.name")
                ->get();
                if(count($languaje)>0){
                        return response()->json($languaje);
                }else{
                    return response()->json("Not Found");
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
        $getstep5=DB::table('service')
        ->join('service_amenites','service_amenites.service_id','=','service.id')
        ->join('amenities','amenities.id','=','service_amenites.amenite_id')
        ->where('service.id','=',$request->input("service_id"))
        ->where('amenities.languaje','=',$request->input("languaje"))
        ->select('service.id','amenities.name', 'amenities.type_amenities_id', 'amenities.code','service_amenites.check')
        ->get();
        if(count($getstep5)>0){
                return response()->json($getstep5);
        }else{
                return response()->json("Not Found");
        }
      }
    }

    public function ReturnStep6(Request $request){
           $rule=[
           'service_id' => 'required|numeric'
      ];
    $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
         $getstep6=DB::table('service')
        ->join('service_payment','service_payment.service_id','=','service.id')
        ->join('payment','payment.code','=','service_payment.payment_id')
        ->join('price_history','price_history.service_id','=','service.id')
        ->join('price_history_has_duration','price_history_has_duration.price_history_service_id','=','price_history.service_id')
        ->join('duration','duration.id','=','price_history_has_duration.duration_id')
        ->join('check_in','check_in.service_id','=','service.id')
        ->join('check_out','check_out.service_id','=','service.id')
        ->join('currency','currency.id','=','price_history.currency_id')
        ->where('service.id','=',$request->input("service_id"))
        ->where('payment.languaje','=',$request->input("languaje"))
        ->where('duration.languaje','=',$request->input("languaje"))
        ->select('payment.type as Type-Payment','duration.type as Type-Duration'
        ,'price_history.price as Price','currency.currency_iso as Currency-Name'
        ,'check_in.time_entry as Time-Entry','check_in.until as Until'
        ,'check_out.departure_time as Departure-Time','price_history.starDate as startDate','price_history.endDate')
        ->get();
         if(count($getstep6)>0){
                return response()->json($getstep6);
        }else{
                return response()->json("Not Found");
        }
      }
    }

    public function ReturnStep7Description(Request $request){
           $rule=[
           'service_id' => 'required|numeric'
      ];
    $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
        $getstep7=DB::table('service')
        ->join('service_description','service_description.service_id','=','service.id')
        ->join('description','description.id','=','service_description.description_id')
        ->where('service_id','=',$request->input("service_id"))
        ->select('service_description.content','service_description.check','service_description.description_id')
        ->get();
          if(count($getstep7)>0){
                return response()->json($getstep7);
        }else{
                return response()->json("Not Found");
        }

      }
    }

    public function ReturnStep8Rules(Request $request){
            $rule=[
           'service_id' => 'required|numeric'
      ];
    $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
        $getstep8=DB::table('service')
        ->join('service_rules','service_rules.service_id','=','service.id')
        ->join('house_rules','house_rules.id','=','service_rules.rules_id')
        ->where('service.id','=',$request->input("service_id"))
        ->select('service_rules.description as Description','service_rules.check as Check','service_rules.rules_id')
        ->get();
        if(count($getstep8)>0){
                return response()->json($getstep8);
        }else{
                return response()->json("Not Found");
        }
      }
    }

    public function GetNumberEmergency(Request $request)
    {   $rule=[
           'service_id' => 'required|numeric'
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
          $number=DB::table('number_emergency')->where('service_id',$request->input("service_id"))->get();
          if(count($number)>0){
                  return response()->json($number);
          }else{
                return response()->json("Not Found");
          }
      }
    }

    public function ReturnStep9(Request $request)
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

    public function ReturnStep10(Request $request)
    { $rule=[
           'service_id' => 'required|numeric',
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
              $getstep10=DB::table('service')
        ->join('price_history','price_history.service_id','=','service.id')
        ->join('image','image.service_id','=','service.id')
        ->join('image_duration','image_duration.image_id','=','image.id')
        ->join('duration','duration.id','=','image_duration.duration_id')
        ->join('currency','currency.id','=','price_history.currency_id')
        ->where('service.id','=',$request->input("service_id"))
        ->where('duration.languaje','=',$request->input("languaje"))
        ->select('image.ruta','image.description','price_history.price','currency.money','currency.symbol','duration.type','currency.currency_iso')
        ->orderby('price_history.image_id','DESC')->take(1)->get();
           if(count($getstep10)>0){
                  return response()->json($getstep10);
          }else{
                return response()->json("Not Found");
          }
      }
    }

    public function ReturnStep11(Request $request)
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
           'languaje'=>'required'
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
             ->join('service_accommodation','service_accommodation.service_id','=','service.id')
             ->join('accommodation','accommodation.code','=','service_accommodation.accommodation_id')
             ->join('service_description','service_description.service_id','=','service.id')
             ->join('description','description.id','=','service_description.description_id')
             ->join('check_in','check_in.service_id','=','service.id')
             ->join('service_category','service_category.service_id','=','service.id')
             ->join('category','category.id','=','service_category.category_id')
             ->join('bedroom','bedroom.service_id','=','service.id')
             ->join('service_type','service_type.service_id','=','service.id')
             ->join('type','type.id','=','service_type.type_id')
             ->join('service_payment','service_payment.service_id','=','service.id')
             ->join('payment','payment.id','=','service_payment.payment_id')
             ->where('service.id','=',$request->input("service_id"))
             ->where('category.languaje','=',$request->input("languaje"))
             ->where('accommodation.languaje','=',$request->input("languaje"))
             ->where('type.languaje','=',$request->input("languaje"))
             ->where('payment.languaje','=',$request->input("languaje"))
             ->where('description.id','=',1)
             ->select('service.user_id as servid', 'user.id as userid','user.avatar','user.name','country.name as country','payment.type as prices','state.name as state','type.name as type','service_description.content as title','accommodation.name as accommodation','service.num_guest as guest','service.num_bathroom as bathrooms','check_in.time_entry as check_in','category.name as category','user.lastname')
             ->first();
               if(count($previews)>0){
                  return response()->json($previews);
          }else{
                return response()->json("Not Found");
          }

      }

    }

    public function GetOverviewsBedrooms(Request $request){
             $rule=[
           'service_id' => 'required|numeric'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
            $previews = DB::table('service')->join('bedroom','service.id','=','bedroom.service_id')
            ->where('service.id','=',$request->input("service_id"))
            ->select('service.id','service.num_guest', DB::raw('count(*) as num_bedrooms')/*'bedroom.id as num_bedroom'*/)
            ->orderBy('bedroom.id','desc')
            //->take(1)
            ->get();
            if(count($previews)>0){
                  return response()->json($previews);
            }else{
                  return response()->json('Not Found');
            }
      }
    }

    public function GetOverviewsBeds(Request $request)
    {  $rule=[
           'service_id' => 'required|numeric',
           'languaje'=>'required'
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
             $num=0;
             $bedroom=Bedroom::where('service_id','=',$request->input("service_id"))->get();
             foreach($bedroom as $bedrooms){
                $previews=DB::table('service')
                ->join('bedroom','bedroom.service_id','=','service.id')
                ->join('bedroom_bed','bedroom_bed.bedroom_id','=','bedroom.id')
                ->join('bed','bedroom_bed.bed_id','=','bed.code')
                ->where('service.id',"=",$request->input("service_id"))
                ->where('bed.languaje','=',$request->input("languaje"))
               ->where('bedroom.id','=',$bedrooms->id)
                ->select(DB::raw('count(bedroom_bed.quantity) as num_bed'))
                ->get();
                $num=$previews;
              }
                 if(count($previews)>0){
                  return response()->json($num);
          }else{
                return response()->json("Not Found");
          }

      }

    }

    public function GetOverviewsRules(Request $request)
    {
      $rule=[
           'service_id' => 'required|numeric',
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
              $previews=DB::table('service')
                ->join('service_rules','service_rules.service_id','=','service.id')
                ->join('house_rules','house_rules.id','=','service_rules.rules_id')
                ->where('service.id','=',$request->input("service_id"))
                ->select('house_rules.type','service_rules.description','service_rules.check')
                ->get();
                if(count($previews)>0){
                  return response()->json($previews);
          }else{
                return response()->json("Not Found");
          }


      }
    }

    public function GetOverviewsAmenities(Request $request)
    {
          $rule=[
           'service_id' => 'required|numeric',
           'languaje'=>'required'
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{

              $previews=DB::table('service')
                ->join('service_amenites','service_amenites.service_id','=','service.id')
                ->join('amenities','amenities.id','=','service_amenites.amenite_id')
                ->where('service.id','=',$request->input("service_id"))
                ->where('amenities.languaje','=',$request->input("languaje"))
                ->select('amenities.name as amenities','amenities.code','amenities.type_amenities_id','service_amenites.check')
                ->get();
                  if(count($previews)>0){
                  return response()->json($previews);
          }else{
                return response()->json("Not Found");
          }

      }

    }

    public function GetOverviewsEmergency(Request $request)
    {
      $rule=[
           'service_id' => 'required|numeric',
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{

              $previews=DB::table('service')
                ->join('number_emergency','number_emergency.service_id','=','service.id')
                ->where('service.id','=',$request->input("service_id"))
                ->select('number_emergency.name','number_emergency.number')
                ->get();
                      if(count($previews)>0){
                  return response()->json($previews);
          }else{
                return response()->json("Not Found");
          }
      }
    }

    public function GetOverviewsEmergencyNote(Request $request)
    { $rule=[
           'service_id' => 'required|numeric',
           'languaje'=>'required'
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
              $previews=DB::table('service')
                ->join('service_emergency','service_emergency.service_id','=','service.id')
                ->join('note_emergency','note_emergency.id','=','service_emergency.emergency_id')
                    ->where('service.id','=',$request->input("service_id"))
                    ->where('note_emergency.languaje','=',$request->input("languaje"))
                ->select('note_emergency.type','service_emergency.content','service_emergency.check')
                ->get();
                            if(count($previews)>0){
                  return response()->json($previews);
          }else{
                return response()->json("Not Found");
          }

      }
    }

    public function GetOverviewsEmergencyExit(Request $request)
    {$rule=[
           'service_id' => 'required|numeric',
           'languaje'=>'required'
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
              $previews=DB::table('service')
                ->join('service_emergency','service_emergency.service_id','=','service.id')
                ->join('note_emergency','note_emergency.code','=','service_emergency.emergency_id')
                    ->where('service.id','=',$request->input("service_id"))
                    ->where('note_emergency.languaje','=',$request->input("languaje"))
                    ->where('note_emergency.code','=',1)
                ->select('note_emergency.type','service_emergency.content')
                ->get();
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

    public function getDescription(Request $request){
           $rule=[
           'service_id' => 'required|numeric'
      ];
    $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
        $des=DB::table('service')
        ->join('service_description','service_description.service_id','=','service.id')
        ->join('description','description.id','=','service_description.description_id')
        ->where('service_description.service_id','=',$request->input("service_id"))
        ->where('service_description.description_id', '=', 8)
        ->orwhere('service_description.description_id', '=', 5)
        ->where('service_description.service_id','=',$request->input("service_id"))
        ->select('service_description.content','service_description.check','service_description.description_id')
        ->get();
          if(count($des)>0){
                return response()->json($des);
        }else{
                return response()->json("Not Found");
        }

      }
    }

    public function getTooKnow(Request $request){
           $rule=[
           'service_id' => 'required|numeric'
      ];
    $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
        $des=DB::table('service')
        ->join('service_description','service_description.service_id','=','service.id')
        ->join('description','description.id','=','service_description.description_id')
        ->where('service_id','=',$request->input("service_id"))
        ->where('service_description.description_id', '=', 14)
        ->select('service_description.content','service_description.check','service_description.description_id')
        ->first();
          if(count($des)>0){
                return response()->json($des);
        }else{
                return response()->json("Not Found");
        }

      }
    }

    public function ReturnStep2Beds(Request $request){
            $rule=[
          'user_id'=>'required|min:1',
          'bedroom_id'=>'required|min:1',
          'languaje' => 'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
      }else{
        $exist=DB::table('service')
                  ->join('bedroom','bedroom.service_id','=','service.id')
                  ->where('service.user_id','=',$request->input("user_id"))
                  ->where('bedroom.id','=',$request->input("bedroom_id"))
                  ->select('bedroom.id')
                  ->get();
        if(count($exist)>0){
          $newbedbedroomdata=DB::table('service')
                              ->join('bedroom','bedroom.service_id','=','service.id')
                              ->leftjoin('bedroom_bed','bedroom_bed.bedroom_id','=','bedroom.id')
                              ->leftjoin('bed','bed.id','=','bedroom_bed.bed_id')
                              ->where('service.user_id','=',$request->input("user_id"))
                              ->where('bedroom.id','=',$request->input("bedroom_id"))
                              ->where('bed.languaje','=',$request->input("languaje"))
                              ->select('bedroom_bed.*', 'bed.type as type')
                              ->get();
          //if(count($newbedbedroomdata)>0){
          return response()->json($newbedbedroomdata);
          /*}else{
            return response()->json("The user does not have a room or user not found");

          }*/
        }else{
          return response()->json("The user does not have a room or user not found");

        }

      }

    }

    public function GetImages(Request $request){
      $image=Image::all();
      return response()->json($image);
    }

    public function GetSpaces(Request $request)
    {$rule=[
           
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{
             $previews=DB::table('service')
             ->join('user','user.id','=','service.user_id')
             ->join('service_description','service_description.service_id','=','service.id')
             ->join('description','description.id','=','service_description.description_id')
             ->join('service_category','service_category.service_id','=','service.id')
             ->join('category','category.id','=','service_category.category_id')
             ->join('service_type','service_type.service_id','=','service.id')
             ->join('type','type.id','=','service_type.type_id')
             ->join('price_history','price_history.service_id','=','service.id')
             ->join('currency','currency.id','=','price_history.currency_id')
             ->join('price_history_has_duration','price_history_has_duration.price_history_service_id','=','service.id')
             ->join('duration','duration.id','=','price_history_has_duration.duration_id')
             ->join('image','image.service_id','=','service.id')
             ->where('category.languaje','=',$request->input("languaje"))
             ->where('type.languaje','=',$request->input("languaje"))
             ->where('currency.language','=',$request->input("languaje"))
             ->where('duration.languaje','=',$request->input("languaje"))
             ->where('description.id','=',1)
              ->where('category.code','=',1)
             ->select('service_description.service_id','service.user_id as servid','service_description.content as title','category.name as category','price_history.price','currency.currency_iso','duration.type as duration','image.ruta','image.description as imgdesc')
             ->groupby('service_id')
             ->orderby('service.id','DESC')
             ->get();
               if(count($previews)>0){
                  return response()->json($previews);
          }else{
                return response()->json("Not Found");
          }

      }

    }

    public function GetServices(Request $request)
    {$rule=[
           
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{


             $previews=DB::table('service')
             ->join('user','user.id','=','service.user_id')
                 ->join('service_description','service_description.service_id','=','service.id')
                 ->join('description','description.id','=','service_description.description_id')
                 ->join('service_category','service_category.service_id','=','service.id')
                 ->join('category','category.id','=','service_category.category_id')
                 ->join('price_history','price_history.service_id','=','service.id')
                 ->join('currency','currency.id','=','price_history.currency_id')
                 ->join('price_history_has_duration','price_history_has_duration.price_history_service_id','=','service.id')
                 ->join('duration','duration.id','=','price_history_has_duration.duration_id')
                 ->join('image','image.service_id','=','service.id')
                 ->where('currency.language','=',$request->input("languaje"))
                 ->where('duration.languaje','=',$request->input("languaje"))
                 ->where('category.languaje','=',$request->input("languaje"))
                 ->where('description.id','=',1)
                 ->where('category.code','=',4)
                 ->select('service_description.service_id','service.user_id as servid','service_description.content as title','category.name as category','service.id','price_history.price','currency.currency_iso','duration.type as duration','image.ruta','image.description as imgdesc')
                 ->groupby('service_id')
                 ->orderby('service.id','DESC')
                 ->get();
               if(count($previews)>0){
                  return response()->json($previews);
          }else{
                return response()->json("Not Found");
          }

      }

    }

    public function GetParkings(Request $request)
    {$rule=[
           
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{


             $previews=DB::table('service')
             ->join('user','user.id','=','service.user_id')
                 ->join('service_description','service_description.service_id','=','service.id')
                 ->join('description','description.id','=','service_description.description_id')
                 ->join('service_category','service_category.service_id','=','service.id')
                 ->join('category','category.id','=','service_category.category_id')
                 ->join('service_type','service_type.service_id','=','service.id')
                 ->join('type','type.id','=','service_type.type_id')
                 ->join('image','image.service_id','=','service.id')
                 ->join('price_history','price_history.service_id','=','service.id')
                 ->join('currency','currency.id','=','price_history.currency_id')
                 ->join('price_history_has_duration','price_history_has_duration.price_history_service_id','=','service.id')
                 ->join('duration','duration.id','=','price_history_has_duration.duration_id')
                 ->where('currency.language','=',$request->input("languaje"))
                 ->where('duration.languaje','=',$request->input("languaje"))
                 ->where('category.languaje','=',$request->input("languaje"))
                 ->where('description.id','=',1)
                 ->where('category.code','=',3)
                 ->where('type.languaje','=',$request->input("languaje"))
                 ->select('service_description.service_id','service.user_id as servid','service_description.content as title','category.name as category','service.id','type.name as type','image.ruta','image.description as imgdesc','price_history.price','currency.currency_iso','duration.type as duration')
             ->groupby('service_id')
             ->orderby('id','DESC')
             ->get();
               if(count($previews)>0){
                  return response()->json($previews);
          }else{
                return response()->json("Not Found");
          }

      }

    }

    public function GetWorkspaces(Request $request)
    {$rule=[
           
        ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
            return response()->json($validator->errors()->all());
      }else{


             $previews=DB::table('service')
             ->join('user','user.id','=','service.user_id')
             ->join('service_description','service_description.service_id','=','service.id')
             ->join('description','description.id','=','service_description.description_id')
             ->join('service_category','service_category.service_id','=','service.id')
             ->join('category','category.id','=','service_category.category_id')
             ->join('service_type','service_type.service_id','=','service.id')
             ->join('type','type.id','=','service_type.type_id')
             ->join('image','image.service_id','=','service.id')
             ->where('category.languaje','=',$request->input("languaje"))
             ->where('description.id','=',1)
             ->where('category.code','=',2)
             ->where('type.languaje','=',$request->input("languaje"))
             ->select('service_description.service_id','service.user_id as servid', 'service_description.content as title','category.name as category','service.id','type.name as type','image.ruta','image.description as imgdesc')
             ->orderby('id','DESC')
             ->groupby('service_id')
             ->get();
               if(count($previews)>0){
                  return response()->json($previews);
          }else{
                return response()->json("Not Found");
          }

      }

    }


}
