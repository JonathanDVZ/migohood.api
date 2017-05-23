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
              'service_id' => 'required|numeric|min:1',
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
            ->join('description','service_description.description_id','=','description.description_id')
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
        ->join('amenities','amenities.code','=','service_amenites.amenite_id')
        ->where('service.id','=',$request->input("service_id"))
        ->where('amenities.languaje','=',$request->input("languaje"))
        ->select('service.id','amenities.name', 'amenities.type_amenities_id', 'amenities.code')
        ->get();
        if(count($getstep5)>0){
                return response()->json($getstep5); 
        }else{
                return response()->json("Not Found"); 
        }

             
              }
    }
  
}
    
    
