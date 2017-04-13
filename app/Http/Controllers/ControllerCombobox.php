<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;
use validator;
use App\Models\Category;
use App\Models\Amenite;
use App\Models\Accommodation;
use App\Models\Calendar;
use App\Models\Duration;
use App\Models\House_Rules;
use App\Models\Type;
use DB;

class ControllerCombobox extends Controller
{
    public function GetCategory(){
         return Category::all();
    }

    public function GetAmenities(){
         return Amenite::all();
    }

   public function RulesHouse(){
         return House_Rules::all();
    }

    public function GetAccommodation(){
          return Accommodation::all();        
    }
    
    
    public function GetCalendar(){
          return Calendar::all();        
    }
    
    public function GetType(){
          return Type::all();        
    }

    public function GetDuration(){
          return Duration::all();        
    }

    public function GetCity(Request $request){
        $rule=[
           'city_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
              $getrent = DB::table('city')->join('state','city.state_id','=','state.id')
              ->join('country','country.id','=','state.country_id')
              ->where('city.id','=',$request->input("city_id"))
              ->select('country.name as country','state.name as state','city.name as city','country.iso','country.iso3','country.numcode','country.phonecode')
             ->get(); 
             if(count($getrent)>0){
               return response()->json($getrent);
             }else{
                return response()->json("city_id not found");
             }
        }
    }

    public function TypeGet(Request $request){
         
            $type=Type::select()->where('category_id','=',1)->get();
            if(count($type)>0){
                  return response()->json($type);
            }else{
                  return response()->json("Error"); 
            }
    }

    public function GetBedBedroom(Request $request){
      $rule=[
           'service_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
          $newbedbedroom=DB::table('bedroom')
                              ->leftjoin('bedroom_bed','bedroom_bed.bedroom_id','=','bedroom.id')
                              ->leftjoin('bed','bed.id','=','bedroom_bed.bed_id')
                              ->where('bedroom.service_id','=',$request->input("service_id"))
                              ->select('bedroom.id as bedroom_id','bedroom_bed.quantity as bed_quantity','bed.id as bed_id','bed.type as bed_type')
                              ->get();
          if(count($newbedbedroom)>0){
                return response()->json($newbedbedroom);
          }else{
                return response()->json('GetBedroom Not found'); 
          }
       }
  }

public function GetBedBedroomData(Request $request){
         $rule=[
           'user_id'=>'required|min:1',
           'bedroom_id'=>'required|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
               $newbedbedroomdata=DB::table('service')
               ->join('bedroom','bedroom.service_id','=','service.id')
               ->leftjoin('bedroom_bed','bedroom_bed.bedroom_id','=','bedroom.id')
               ->leftjoin('bed','bed.id','=','bedroom_bed.bed_id')
               ->where('service.user_id','=',$request->input("user_id"))
               ->where('bedroom.id','=',$request->input("bedroom_id"))
               ->select('bedroom_bed.*','service.id as service_id')
               ->get();
               //dd($newbedbedroomdata);
               if(count($newbedbedroomdata)>0){
                   return response()->json($newbedbedroomdata);
               }else{ 
                   return response()->json("The user does not have a room or user not found");
                    
               }

        }

  }
  
}
    
    
