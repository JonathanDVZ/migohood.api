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
use DB;

class ControllerCardPaypal extends Controller
{
    public function ReadPaypal(){
        return Paypal::all();
    }
    
    public function ReadCard(){
        return Card::all();
    }

    public function AddPaypal(request $request){
        $rule=[
            'user_id'=>'required|numeric|min:1',
            'account'=>'required|numeric|min:1'
            ];
        $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{//Busca si el usuario se encuentra registrado
             $date=User::where('id','=',$request->input('user_id'))->first();
             if($date!=null){
                  $val= DB::select('select * from paypal where user_id=? and account=?',[$date->id,$request->input("account")]);
                   if(count($val)==0){
                      $addpaypal=new Paypal();
                      $addpaypal->account=$request->input('account');
                      $addpaypal->user_id=$date->id;
                      if($addpaypal->save()){
                          return response()->json(['message' => 'Add Paypal', 'paypal_id' => $addpaypal->id]);
                      }
                   }else{
                     return response()->json(['message' => 'The paypal is already registered','account'=> $request->input("account")]);
                   }   
              }else{
                   return response()->json('User not found');
             }
        } 
    }

   //Agrega Card
    public function AddCard(request $request){
        $rule=[
            'user_id'=>'required|numeric|min:1',
            'number'=>'required|numeric|min:1',
            'exp_month'=>'required',
            'exp_year'=>'required',
            'cvc'=>'required|numeric|unique:card,cvc|min:1',
            'name'=>'required|string'            
            ];
        $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{//Busca si el usuario se encuentra registrado
             $date=User::select()->where('id',$request->input("user_id"))->first(); 
             if(count($date)>0){
                 $val= DB::select('select * from card where number=? and user_id=?',[$request->input("number"),$date->id]);
                 if(count($val)==0){
                      $addcard=new Card;
                      $addcard->user_id=$date->id;
                      $addcard->number=$request->input('number');
                      $addcard->exp_month=$request->input('exp_month');
                      $addcard->exp_year=$request->input('exp_year');
                      $addcard->cvc=$request->input('cvc');
                      $addcard->name=ucwords(strtolower($request->input('name')));
                      if($addcard->save()){
                          return response()->json(['message' => 'Add Card', 'card_id' => $addcard->id]);
                      }
                  }else{
                      return response()->json(['message'=>'The card is already registered','number'=>$request->input("number")]);

                  }
                 }else{
                     return response()->json('User not found ');
                 }
        } 
    }

  /*  public function UpdatePaypal(request $request){
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

    }*/
   
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
     
    
  /*  public function UpdateCvc(request $request){
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
        
    }*/
     
     public function UpdateName(request $request){
           $rule=[
           'id' => 'required|numeric|min:1',
           'name'=>'required|string'
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
            'id_paypal' => 'required|numeric|min:1'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $paypal=Paypal::where('id_paypal','=',$request->input('id_paypal'))->first();
             if(count($paypal)>0){
                 DB::table('paypal')->where('id_paypal',$paypal->id_paypal)->delete();
                  return response()->json('Paypal Delete');
              }else{
                 return response()->json('Paypal not found');
               }
            }      
    }

    public function DeleteCard(Request $request){
        $rule=[
            'card_id' => 'required|numeric|min:1'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $card=Card::where('id','=',$request->input('card_id'))->first();
             if($card!=null){
                  //$card->delete();
                   DB::table('card')->where('id',$card->id)->delete();
                  return response()->json('Card Delete');
              }else{
                 return response()->json('Card not found');
               }
            }      
    }
 //Muestra todos los paypal que tiene un user en especifico
     public function GetUserPaypal(Request $request){
          $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $user=User::where('id','=',$request->input('user_id'))->first();
            if(count($user)>0){
                 $paypal = DB::select('select * from paypal where user_id=?', [$user->id]);
               if(count($paypal)>0){
                    return response()->json($paypal);
               }else{
                    return response()->json("The user does not have a registered paypal");
               }
            }
            else{
                return response()->json("User not found");
            }
        }
    }

    public function GetUserCard(Request $request){
          $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $user=User::where('id','=',$request->input('user_id'))->first();
            if(count($user)>0){
                 $paypal = DB::select('select * from card where user_id=?', [$user->id]);
               if(count($paypal)>0){
                    return response()->json($paypal);
               }else{
                    return response()->json("The user does not have a registered card");
               }
            }
            else{
                return response()->json("User not found");
            }
        }

    }

}