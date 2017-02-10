<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Phone;
use App\Models\State;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DB;

class ControllerUser extends Controller
{
    
   
    public function Create(Request $request)
    {   
        // Ejecutamos el validador, en caso de que falle devolvemos la respuesta
        $rule=[
            'name'=>"required|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45",
            'email'=>'email|required',
            'password'=>'required',
            'lastname'=>"required|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45",
            'city_id'=>'numeric'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        else{ //devuelve un email
            $user = User::select()->where('email', strtolower($request->input("email")))->first();        
                if ($user==null){//si es null es por que no esta registrado
                       $newUser=new User();
                       $newUser->name=ucwords(strtolower($request->input('name')));
                       $newUser->email=strtolower($request->input('email'));
                       $newUser->password=Crypt::encrypt($request->input('password'));
                       $newUser->thumbnail=$request->input('thumbnail');
                       $newUser->secondname=ucwords(strtolower($request->input('secondname')));
                       $newUser->lastname=ucwords(strtolower($request->input('lastname')));
                       $newUser->remember_token=str_random(100);
                       $newUser->confirm_token=str_random(100);
                       $newUser->address=strtolower($request->input('address'));
                       $newUser->city_id=$request->input('city_id');
                       if( $newUser->save()){
                          return response()->json("User Create"); 
                       }
                    }
                    else{
                         return response()->json("User Existente"); 
                    }
            }
        }
  
    //Muestra todos los usuarios
    public function Read( ){
        return User::all();
    }

     //Actualiza Name
    public function UpdateName(Request $request){
      $rule=[
           'id' => 'required|numeric',
          'name'=>"required|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45"
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $date->name=ucwords(strtolower($request->input('name')));
                  if($date->save()){
                       return response()->json('Update Name');
                   }
              }else{
                   return response()->json('User not found ');
             }
        }
    } 
 
   //Actualiza Email
    public function UpdateEmail(Request $request){
      $rule=[
           'id' => 'required|numeric',
          'email'=>"required|email"
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $date->email=strtolower($request->input('email'));
                  if($date->save()){
                       return response()->json('Update Email');
                   }
              }else{
                  return response()->json('User not found ');
              }
        }
    } 
    
    //Actualiza Password 
    public function UpdatePassword(Request $request){
       // Creamos las reglas de validación
        $rules = [
            'id'  => 'required|numeric',
            'current-password'  => 'required',
            'new-password'  => 'required',
            'confirm-new-password'  => 'required'
            ];
        // Ejecutamos el validador, en caso de que falle devolvemos la respuesta
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        else{
            // Buscamos el usuario que posea el id ingresado, una vez que se halle entra a la condición
            $user = User::select()->where('id',$request->input("id"))->first();
            if ($user){
                try {
                    // Desencriptamos la contraseña del usuario y se compara con la contraseña ingresada, si coinciden entra a la condición
                    $decrypted = Crypt::decrypt($user->password);
                    if (strcmp($decrypted,$request->input("current-password"))==0){

                        // Se comparan los campos que contienen la contraseña nueva, si coinciden entra a la condición
                        if (strcmp($request->input("confirm-new-password"),$request->input("new-password"))==0){
                            //Se actualiza la contraseña del usuario
                            DB::table('User')->where('id',$user->id)->update(['password' => Crypt::encrypt($request->input('new-password'))]);
                            return response()->json('Your password has been successfully updated!');
                        }
                        else
                            return response()->json('The respective fields for the new password do not match');  

                    }
                    else
                        return response()->json('The password is incorrect');
                    
                } catch (DecryptException $e) {
                    return response()->json($e);
                }
            }
            else
                return response()->json('The user is not exist!');
        }
    } 

    //Actualiza thumbnail 
    public function UpdateThumbnail(Request $request){
      $rules = array(
            'id'  => 'required|numeric',
            'thumbnail' => 'required|image'
        );
        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return response()->json($validator->errors()->all());
        }
        else
        {
             // Buscamos el usuario que posea el id ingresado, una vez que se halle entra a la condición
            $user = User::select()->where('id',$request->input("id"))->first();
            if ($user){
                try{
                    // Obtenemos el campo file definido en el formulario
                    $file = $request->file('thumbnail');            
                    // Creamos un nombre para nuestro thumnail
                    $name = 'thumbnail_user_'.Auth::user()->id.'.'.$file->getClientOriginalExtension();            
                    // Movemos el archivo a la caperta que deseamos
                    $file->move('files/thumbnails/',$name);
                    // Actualizamos la fila thumbnail del usuario respectivo
                    DB::table('user')->where('id', $user->id )->update(['thumbnail' => url('/files/thumbnails/'.$name)]);
                    return json_encode('Update completed!', true);
                }
                catch (\Exception $e){
                    return response()->json($e->getMessage());
                }
            }
            else
                return response()->json('The user is not exist!');
        }
    } 

    //Actualiza SecondName
    public function UpdateSecondname(Request $request){
      $rule=[
           'id' => 'required|numeric',
           'secondname'=>"regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45"
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $date->secondname=ucwords(strtolower($request->input('secondname')));  
                  if($date->save()){
                       return response()->json('Update Secondname');
                   }
              }else{
                  return response()->json('User not found ');
              }
        }
    } 

    //Actualiza LastName
    public function UpdateLastname(Request $request){
      $rule=[
           'id' => 'required|numeric',
           'lastname' => "required|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45",
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                 $date->lastname=ucwords(strtolower($request->input('lastname'))); 
                  if($date->save()){
                       return response()->json('Update Lastname');
                   }
              }else{
                  return response()->json('User not found ');
              }
        }
    } 

   //Actualiza Address
    public function UpdateAddress(Request $request){
      $rule=[
           'id' => 'required|numeric'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                 $date->address=strtolower($request->input('address')); 
                  if($date->save()){
                       return response()->json('Update Address');
                   }
              }else{
                  return response()->json('User not found ');
              }
        }
    } 

   //Actualiza City
    public function UpdateCity(Request $request){
      $rule=[
           'id' => 'required|numeric',
           'city_id'=>'numeric'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $date->city_id=$request->input('city_id');
                  if($date->save()){
                       return response()->json('Update City');
                   }
              }else{
                  return response()->json('User not found ');
              }
        }
    } 
 
    //Elimina User
     public function Delete(Request $request){
        $rule=[
            'id' => 'required|numeric'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $date->delete();
                  return response()->json('User Delete');
              }else{
                 return response()->json('User not found');
               }
            }      
       }
     
     //Verifica si el email y password son correcto es decir si estan registrados
     public function verificationLogin(Request $request)
     {
        $rule=[
            'email'=>'required|email',
            'password'=>'required'
            ];
        $validator=Validator::make($request->all(),$rule);     
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
            $user = User::select()->where('email', strtolower($request->input("email")))->first();
            if(count($user)>0){
            $decrypted = Crypt::decrypt($user->password);
            if (strcmp($decrypted,$request->input("password"))==0) {
                      if(strcmp($user->email,$request->input("email"))==0){
                          return response()->json("true");
                      }
            }else{
                //Es false si no existe el usuario o ingreso sus datos erroneos
                return response()->json("Password False");   
            }
            }else{
                return response()->json("User false"); 
            }            
        }
    }

   //Agregar telefono de usuario
   public function AddPhone(request $request)
   {
      $rule=[
            'id'=>'required|numeric',
            'number'=>'required|min:7',
            'type'=>'required'
            ];
        $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{//Busca si el usuario se encuentra registrado
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $addphone=new Phone();
                  $addphone->number=$request->input('number');
                  $addphone->type=$request->input('type');
                  $addphone->user_id=$date->id;
                  if($addphone->save()){
                       return response()->json('Add Phone');
                   }
              }else{
                   return response()->json('User not found ');
             }
        }        
   }

//Modificar Phone
public function UpdatePhone(Request $request){
      $rule=[
           'id' => 'required|numeric',
           'number'=>'required|numeric|min:7',
           'type'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
               $phone = Phone::select()->where('id',$request->input("id"))->first();
                 if (count($phone)>0){
                      DB::table('phone_number')->where('id',$phone->id)->update(
                            ['number'=>$request->input("number"),
                              'type'=>$request->input("type"),
                            ]);
                       return response()->json('Update Phone ');     
                  }else{
                  return response()->json('Phone not found ');
                  }
              }
 }
  
   //Muestra  los Telefonos de un Usuario    
    public function GetPhone()
   {
      $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $user=User::where('id','=',$request->input('user_id'))->first();
            if(count($user)>0){
                 $phone = DB::select('select * from phone where user_id=?', [$user->id]);
               if(count($phone)>0){
                    return response()->json($phone);
               }else{
                    return response()->json("The user does not have a registered phone");
               }
            }
            else{
                return response()->json("User not found");
            }
        }
   }

 //Elimina Phone
   public function DeletePhone(Request $request){
       $rule=[
            'id' => 'required|numeric'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $phone=Phone::where('id','=',$request->input('id'))->first();
             if($phone!=null){
                  $phone->delete();
                  return response()->json('Phone Delete');
              }else{
                 return response()->json('Phone not found');
               }
            }      
    }

    //Devuelve datos de un user en especifico
    public function GetUser(Request $request){
            $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $id=$request->input("user_id");
            $user= DB::select('select * from user where id = ?',[$id] );
            if(count($user)>0){
                 return response()->json($user);
            }
            else{
                return response()->json("User not found");
            }
        }
    }

    //Devuelve datos de un city de user
    public function GetCity(Request $request){
            $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $user=User::where('id','=',$request->input('user_id'))->first();
            if(count($user)>0){
                $city= DB::select('select * from city where id = ?',[$user->city_id] );
                 return response()->json($city);
            }
            else{
                return response()->json("User not found");
            }
        }
    }

    public function GetState(Request $request){
            $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $user=User::where('id','=',$request->input('user_id'))->first();
            if(count($user)>0){
               $city=City::where('id','=',$user->city_id)->first();
               $state=State::where('id','=',$city->state_id)->first(); 
               return response()->json($state);
            }
            else{
                return response()->json("User not found");
            }
        }
    }

    public function GetCountry(Request $request){
            $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $user=User::where('id','=',$request->input('user_id'))->first();
            if(count($user)>0){
               $city=City::where('id','=',$user->city_id)->first();
               $state=State::where('id','=',$city->state_id)->first();
               $country=Country::where('id','=',$state->country_id)->first(); 
               return response()->json($country);
            }
            else{
                return response()->json("User not found");
            }
        }
    }
    
}