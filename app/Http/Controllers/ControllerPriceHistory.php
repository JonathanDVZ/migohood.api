<?php
namespace App\Http\Controllers;
use App\Models\Message;
use App\Models\Inbox;
use App\Models\User;
use App\Models\Service;
use App\Models\Price_History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DateTime;
use DateInterval;
use DB;


class ControllerPriceHistory extends Controller
{
    //Agregar un price 
    public function AddPryceHistory(Request $request){
        // Ejecutamos el validador, en caso de que falle devolvemos la respuesta
        $rule=[
            'service_id'=>"required|numeric|min:1",
            'price'=>'required|numeric|min:1',
            'endDate'=>'date_format:Y-m-d|after:today'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        else{  
            $service = Service::select()->where('id',$request->input("service_id"))->first();
             
                if(count($service)>0){ 
                          $pricehistory= DB::select('select * from price_history where service_id = ?',[$service->id]);
                         
                              $newprice=new Price_History();
                              $dt = new DateTime();
                              $newprice->starDate=$dt->format('Y-m-d H:i:s');
                             // $newprice->endDate=$request->input('endDate');
                              $newprice->price=$request->input('price');
                              $newprice->service_id=$service->id; 
                              if( $newprice->save()){
                                  return response()->json("Price_History Create"); 
                               }
                     }else{
                          return response()->json("Service not found"); 
                      }
             }
    }


        public function UpdatePrice(Request $request){
         $rule=[
          'service_id' => 'required|numeric|min:1',
           'price'=>'required|numeric|min:1'
         ];
           $validator=Validator::make($request->all(),$rule);
           if ($validator->fails()) {
               return response()->json($validator->errors()->all());
           }else{
                 $newprice = Price_History::select()->where('endDate','=',null)->where('service_id','=',$request->input('service_id'))->first();
                 if(count($newprice)>0){
                     $dt = new DateTime();
                     DB::table('price_history')->where('endDate','=',$newprice->endDate)->where('service_id','=',$newprice->service_id)->update(
                     ['endDate' => $dt->format('Y-m-d H:i:s'),
                     ]);
                     $newhistory=new Price_History();
                     $newhistory->starDate=$dt->format('Y-m-d H:i:s');
                     $newhistory->price= $request->input('price');
                     $newhistory->service_id=$request->input('service_id');
                     if($newhistory->save()){
                                return response()->json("Price Update!!!");
                     }
                }else{
                     return response()->json("Price-History not found"); 
                }

            }
        }
    public function GetHistory(Request $request){
         $rule=[
          'service_id' => 'required|numeric|min:1'
         ];
           $validator=Validator::make($request->all(),$rule);
           if ($validator->fails()) {
               return response()->json($validator->errors()->all());
           }else{
                 $service = Service::select()->where('id',$request->input("service_id"))->first();
             
                if(count($service)>0){ 
                       $pricehistory= DB::select('select starDate,endDate,price from price_history where service_id = ?',[$service->id]);
                       if(count($pricehistory)>0){
                            return response()->json($pricehistory);
                       }else{
                            return response()->json("Price-History not found");
                       }
                }else{
                    return response()->json("Service not found");
                }

           }
    }

        
        
}