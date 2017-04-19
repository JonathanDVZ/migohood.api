<?php
namespace App\Http\Controllers;
use App\Models\Card;
use App\Models\Paypal;
use App\Models\Notification;
use App\Models\User;
use App\Models\Bill;
use App\Models\Rent;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use Stripe\Charge;
use Stripe\Stripe;
use DateTime;
use DB;

class ControllerPaymentBill extends Controller
{
    //Agrega Bill
    public function AddBill(Request $request){
           $rule=[
                 'rent_id'=>'required|numeric|min:1',
                 'card_id'=>'numeric|min:0',
                 'paypal_id'=>'numeric|min:0', 
                 'total'=>'required|numeric|min:1'
            ];
            $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{    
                $rent=Rent::select()->where('id',$request->input("rent_id"))->first();  
                if(count($rent)>0){
                   $card=Card::select()->where('id',$request->input("card_id"))->first(); 
                   $paypal=Paypal::select()->where('id_paypal',$request->input("paypal_id"))->first();
                     if(count($card)>0 || count($paypal)>0){
                         $dt = new DateTime();
                         $newbill=new Bill();
                         $newbill->date=$dt->format('Y-m-d H:i:s');
                         $newbill->user_id=$rent->user_id;
                         $newbill->total=$request->input("total");
                         if($request->input("card_id")==0){
                              $newbill->card_id=null;
                         }else{
                               if(count($card)==0 && $request->input("card_id")!=0){
                                      return response()->json('card not found'); 
                               }else{
                               /* try{
                                Stripe::setApiKey("");     
                                Charge::create(array(
                                  "type"=>'ideal',
                                  "amount" =>$request->input("total")*100,
                                  "currency" => "usd",
                                  "source" => $request->input('stripeToken'), 
                                  "description" => 'Test Charge'
                                ));
                                }catch(\Exception $e){
                                    return response()->json($e->getMessage());
                                }*/
                              $newbill->card_id=$card->id;
                             } 
                         }  
                             if( $request->input("paypal_id")==0){
                                  $newbill->paypal_id=null;    
                             }else{
                                 if(count($paypal)==0 && $request->input("paypal_id")!=0){
                                      return response()->json('Paypal not found'); 
                                 }else{
                                     $newbill->paypal_id=$paypal->id_paypal;
                                 }
                             }
                       
                                 $newbill->rent_id=$rent->id;
                                 if($newbill->save()){//envia una notificacion a la persona que  agrego al servicio;
                                     $userservice=Service::select()->where('id',$rent->service_cod)->first(); 
                                     $newnotification=new Notification();
                                     $newnotification->user_id=$userservice->user_id;
                                     $newnotification->bill_number=$newbill->id;
                                     $newnotification->save();
                                     return response()->json(['Message'=>'Bill Add','Date'=>$newbill->date,'Paypal'=>$newbill->paypal_id,'Card'=>$newbill->card_id,'Total'=>$newbill->total]); 
                                 }
                         }else{
                              return response()->json("Card and Paypal not found");
                         }
               }else{
                     return response()->json("Rent not found");
                 }
            }
    }
    
    //Muestra un Bill en especifico
    public function ReadBill(Request $request){
         $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $getrent = DB::table('bill')->join('rent','bill.user_id','=','rent.user_id')
         ->where('bill.user_id','=',$request->input("user_id"))
         ->select('rent.initial_date','rent.end_date','bill.card_id as card','bill.paypal_id as paypal','bill.total','bill.date')
         ->get();
         if(count($getrent)>0){
             return response()->json($getrent); 
         }else{
             return response()->json("Rent not fount"); 
         }
        }
    }

    //Delete Bill
    public function DeleteBill(Request $request){
        $rule=[
            'id' => 'required|numeric|min:1'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $bill=Bill::where('id','=',$request->input('id'))->first();
             if($bill!=null){
                  $bill->delete();
                  return response()->json('Bill Delete');
              }else{
                 return response()->json('Bill not found');
               }
            }      
    }
}
     




          