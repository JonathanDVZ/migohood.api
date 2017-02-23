<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Service;
use App\Models\Category;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DB;

class ControllerRent extends Controller
{
    public function AddRent(request $request){
          $rule=[
              'user_id'=>'required|numeric|min:1',
              'service_id'=>'required|numeric|min:1',
           'initial_date'=>'required|date_format:Y-m-d|after:today',
              'end_date'=>'required|date_format:Y-m-d|after:today'
          ];
          $validator=Validator::make($request->all(),$rule);
          if ($validator->fails()) {
              return response()->json($validator->errors()->all());
          }else{
              $user=User::select()->where('id',$request->input("user_id"))->first();        
              $service=Service::select()->where('id',$request->input("service_id"))->first();
              if(count($user)>0 && count($service)>0){
                   $val= DB::select('select * from rent where user_id = ? and service_cod=?',[$user->id,$service->id]);
                   if(count($val)>0){
                          $newserve=DB::select('select * from rent where service_cod=? and ((initial_date<=? and end_date>=?) or (initial_date<=? and end_date>=?))',
                          [
                             $service->id,
                             $request->input("initial_date"),
                             $request->input("initial_date"),
                             $request->input("end_date"),
                             $request->input("end_date")
                          ]);
                          if(count($newserve)==0){
                              $newrent=new Rent();
                              $newrent->user_id=$user->id;
                              $newrent->service_cod=$service->id;
                              $newrent->end_date=$request->input("end_date");
                              $newrent->initial_date=$request->input("initial_date");
                              $newrent->save();
                              return response()->json("Add Rent");
                          }else{
                               return response()->json("Can not rent at that time");
                         }
                   
                   }else{
                           $newrent=new Rent();
                           $newrent->user_id=$user->id;
                           $newrent->service_cod=$service->id;
                           $newrent->end_date=$request->input("end_date");
                           $newrent->initial_date=$request->input("initial_date");
                           $newrent->save();
                           return response()->json("Add Rent");
                       }
               }else{
                 return response()->json("User or Service not found");
               }
           }
        }
    
    
    //Muestra la renta seleccionada
    public function ReadRent(Request $request){
         $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $getrent = DB::table('user')->join('rent','user.id', '=','rent.user_id')
         ->join('service','service.id','=','rent.service_cod')
         ->where('rent.user_id','=',$request->input("user_id"))
         ->select('user.name as user','service.title','rent.end_date','rent.initial_date')
         ->get();
         if(count($getrent)>0){
             return response()->json($getrent); 
         }else{
             return response()->json("Rent not fount"); 
         }
           
        }
    }
    
    
        
        //Elimina Renta
        public function DeleteRent(request $request){
            $rule=[
                    'rent_id'=>'required|numeric|min:1'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
             }
             else{
                 $rent = Rent::select()->where('id',$request->input("rent_id"))->first();
                 if(count($rent)>0){
                    DB::table('rent')->where('id',$rent->id)->delete();
                    return response()->json('Rent Delete');
                 }else{
                    return response()->json('Rent Not found');   
                 }
              }   
        } 


}
