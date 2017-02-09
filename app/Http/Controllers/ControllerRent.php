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
                'initial_date'=>'required',
                'end_date'=>'required'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                 $user=User::select()->where('id',$request->input("user_id"))->first();        
                 $service=Service::select()->where('id',$request->input("service_id"))->first();
                 if(count($service)>0){
                     if(count($user)>0){
                          $newrent=new Rent;
                          $newrent->user_id=$user->id;    
                          $newrent->service_cod=$service->id;
                          if($service->date<$request->input("initial_date")){
                              if($request->input("end_date")>$request->input("initial_date")){
                               $newrent->end_date=$request->input("end_date");
                               $newrent->initial_date=$request->input("initial_date");
                              if($newrent->save()){
                                return response()->json('Add Rent');           
                               }
                               }else{
                                return response()->json('The end date must be greater');
                            }
                         }else{
                              return response()->json('Is not on the selected service date');  
                          }                             
                              
                     }else{
                    return response()->json('User not found'); 
                  }
               }else{
                   return response()->json('Service not found');
               }
            }
    }
    
    //Muestra la renta seleccionada
    public function ReadRent(Request $request){
        $rule=[
           'rent_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $rent=Rent::where('id','=',$request->input('rent_id'))->first();
            if(count($rent)>0){
               return response()->json($rent);
                    }
            else{
                return response()->json("Rent not found!");
            }
        }
    }
    
    //Verifica si una renta esta  reservada para una fecha si lo esta el usuario elegira otra 
    public function VerificationRent(request $request){
         $rule=[
                'rent_id'=>'required|numeric|min:1',
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{      
                 $rent=Rent::select()->where('id',$request->input("rent_id"))->first();
                   if(count($rent)>0){  
                      if($request->input("initial_date")>$rent->initial_end){
                          if($request->input("end_date")>$request->input("initial_date")){
                            $newrent=new Rent();
                            $newrent->user_id=$rent->user_id;
                            $newrent->service_cod=$rent->service_cod;
                            $newrent->initial_date=$request->input("initial_date");
                            $newrent->end_date=$request->input("end_date");
                            if($newrent->save()){
                                return response()->json('Add Rent');           
                               }
                            }else{
                                return response()->json('The end date must be greater');
                            }
                    }else{
                       return response()->json('Is not on the selected service date');     
                     }               
                 }else{
                    return response()->json('Rent not found');
                 }
           }
         }
        
        //Elimina Renta
        public function DeleteRent(request $request){
            $rule=[
                    'id'=>'required|numeric|min:1'
             ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                return response()->json($validator->errors()->all());
             }
             else{
                 $rent = Rent::select()->where('id',$request->input("id"))->first();
                 if(count($rent)>0){
                    DB::table('rent')->where('id',$rent->id)->delete();
                    return response()->json('Rent Delete');
                 }else{
                    return response()->json('Rent Not found');   
                 }
              }   
        } 

        //Consulta de la tabla rent con los atributos name de user que lo esta solicitando y la fecha final e inicial
      public function GetUserServiceRent(){
          $getrent = DB::table('user')->join('rent','user.id', '=','rent.user_id')
         ->join('service','service.id','=','rent.service_cod')
         ->select('user.name as user','service.name as service','rent.end_date','rent.initial_date')
         ->get();
         if(count($getrent)>0){
             return response()->json($getrent); 
         }else{
             return response()->json("Rent not fount"); 
         }
      }
}
