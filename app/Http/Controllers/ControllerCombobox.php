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
            $amenitie = DB::table('amenities')->select('code','name')->where('languaje','=',$request->input("languaje"))->where('category_id','=',1)->get();
            if(count($amenitie)>0){
                  return response()->json($amenitie);
            }else{ 
                  return response()->json("Amenities not found");
            }
      }
    }

  
}
    
    
