<?php
namespace App\Http\Controllers;
use App\Models\Card;
use App\Models\Paypal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;

class ControllerCardPaypal extends Controller
{
    public function ReadPaypal(){
        return Paypal::all();
    }
    
    public function ReadCard(){
        return Paypal::all();
    }

    public function AddPaypal(request $request){
        $rule=[
            'id'=>'required|numeric|min:1',
            'account'=>'required'
            ];
        $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{//Busca si el usuario se encuentra registrado
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $addpaypal=new Paypal();
                  $addpaypal->account=$request->input('account');
                  $addpaypal->user_id=$date->id;
                  if($addpaypal->save()){
                       return response()->json('Add Paypal');
                   }
              }else{
                   return response()->json('User not found ');
             }
        } 
    }

   //Agrega Card
    public function AddCard(request $request){
        $rule=[
            'id'=>'required|numeric',
            'number'=>'required',
            'exp_month'=>'required',
            'exp_year'=>'required',
            'cvc'=>'required',
            'name'=>'required'            
            ];
        $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{//Busca si el usuario se encuentra registrado
             $date=User::select()->where('id',$request->input("id"))->first(); 
             if(count($date)>0){
                  $addcard=new Card();
                  $addcard->user_id=$date->id;
                  $addcard->number=$request->input('number');
                  $addcard->exp_month=$request->input('exp_month');
                  $addcard->exp_year=$request->input('exp_year');
                  $addcard->cvc=$request->input('cvc');
                  $addcard->name=ucwords(strtolower($request->input('name')));
                  if($addcard->save()){
                       return response()->json('Add Card');
                   }
              }else{
                   return response()->json('User not found ');
             }
        } 
    }

    public function UpdatePaypal(request $request){
       $rule=[
           'id_paypal' => 'required|numeric',
           'account'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $updatepaypal=Paypal::where('id_paypal','=',$request->input('id_paypal'))->first();
             if($updatepaypal!=null){
                  $updatepaypal->account=$request->input('account');
                  if($updatepaypal->save()){
                       return response()->json('Update Paypal');
                   }
              }else{
                  return response()->json('Phone not found ');
              }
       }
    }

    public function UpdateNumber(request $request){
         $rule=[
           'id' => 'required|numeric',
           'number'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $card=Card::where('id','=',$request->input('id'))->first();
             if($card!=null){
                  $card->number=$request->input('number');
                  if($card->save()){
                       return response()->json('Update Number the Card');
                   }
              }else{
                  return response()->json('Card not found ');
              }
        }

    }
   
    public function UpdateExpMonth(request $request){
        $rule=[
           'id' => 'required|numeric',
           'exp_month'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $card=Card::where('id','=',$request->input('id'))->first();
             if($card!=null){
                  $card->exp_month=$request->input('exp_month');
                  if($card->save()){
                       return response()->json('Update Exp_month the Card');
                   }
              }else{
                  return response()->json('Card not found ');
              }
        }
        
    }
     
     public function UpdateExpYear(request $request){
           $rule=[
           'id' => 'required|numeric',
           'exp_year'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $card=Card::where('id','=',$request->input('id'))->first();
             if($card!=null){
                  $card->exp_year=$request->input('exp_year');
                  if($card->save()){
                       return response()->json('Update Exp_year the Card');
                   }
              }else{
                  return response()->json('Card not found ');
              }
        }
        
    }
     
    
    public function UpdateCvc(request $request){
          $rule=[
           'id' => 'required|numeric',
           'cvc'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $card=Card::where('id','=',$request->input('id'))->first();
             if($card!=null){
                  $card->cvc=$request->input('cvc');
                  if($card->save()){
                       return response()->json('Update cvc the Card');
                   }
              }else{
                  return response()->json('Card not found ');
              }
        }
        
    }
     
     public function UpdateName(request $request){
           $rule=[
           'id' => 'required|numeric',
           'name'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $card=Card::where('id','=',$request->input('id'))->first();
             if($card!=null){
                  $card->name=$request->input('name');
                  if($card->save()){
                       return response()->json('Update Name the Card');
                   }
              }else{
                  return response()->json('Card not found ');
              }
        }
        
    }

    public function DeletePaypal(Request $request){
        $rule=[
            'id_paypal' => 'required|numeric'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $paypal=Paypal::where('id_paypal','=',$request->input('id_paypal'))->first();
             if($paypal!=null){
                  $paypal->delete();
                  return response()->json('Paypal Delete');
              }else{
                 return response()->json('User not found');
               }
            }      
    }

    public function DeleteCard(Request $request){
        $rule=[
            'id' => 'required|numeric'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $paypal=Paypal::where('id','=',$request->input('id'))->first();
             if($paypal!=null){
                  $paypal->delete();
                  return response()->json('Card Delete');
              }else{
                 return response()->json('User not found');
               }
            }      
    }

     public function GetUserCardPaypal(Request $request){
           

       
    }

}