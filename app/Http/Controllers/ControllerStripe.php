<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use Stripe\Charge;
use Stripe\Stripe;
use DateTime;
use DB;

class ControllerStripe extends Controller
{
     public function stripePayment(Request $request){
        // Creamos las reglas de validación
        $rules = [
           'user_id'  => 'required|numeric',
            'stripeToken'  => 'required',
            'bill_id'=>'required|numeric'
        ];
        // Ejecutamos el validador, en caso de que falle devolvemos la respuesta
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        else{
          $bill= DB::select('select * from bill where id=? and user_id=?', [$request->input("bill_id"),$request->input("user_id")]);
           if(count($bill)){
               Stripe::setApiKey();//api que genera el user de stripe
               try{
                   Charge::create(array(
                       "type"=>'ideal',
                       "amount" => $bill->price,
                       "currency" => "usd",
                       "source" => $request->input('stripeToken'), 
                       "description" => 'Test Charge'
                ));
               }
                catch(\Exception $e){
                    return response()->json($e->getMessage());
                }
                return response()->json('Payment completed successfully');
           }else{
                return response()->json('Bill not found');               
           }
        }
    }
}