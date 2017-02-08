<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Phone;
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
  

    public function Read( ){
        return User::all();
    }

     //Actualiza Nombre
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
 
   //Actualiza Correo
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
    
      //Actualiza contraseña 
    public function UpdatePassword(Request $request){
      $rule=[
           'id' => 'required|numeric',
          'password'=>"required"
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $date->password=Crypt::encrypt($request->input('password'));
                  if($date->save()){
                       return response()->json('Update Password');
                   }
              }else{
                  return response()->json('User not found ');
              }
        }
    } 

      //Actualiza foto de  perfil 
    public function UpdateThumbnail(Request $request){
      $rule=[
           'id' => 'required|numeric',
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $date->thumbnail=$request->input('thumbnail');
                  if($date->save()){
                       return response()->json('Update thumbnail');
                   }
              }else{
                  return response()->json('User not found ');
              }
        }
    } 

     //Actualiza Segundo Nombre
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

      //Actualiza Apellido
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

   //Actualiza Direccion
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

   //Actualiza Ciudad
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
 
     //Elimina usuario
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

//Modificar telefono
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
    
    public function ReadPhone()
   {
        return Phone::all();  
   }
 //Elimina Usuario
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
                 return response()->json('User not found');
               }
            }      
    }

    //Encuentra telefono del usuario 
    public function GetUserPhone(request $request){
      $users = DB::select('select type,number from phone_number where user_id = ?', [$request->input("user_id")]);
             return response()->json($users);
    }

    //Encuentra telefono y usuario 
    public function GetUserLocation(){
       $userphone = DB::table('user')->join('city','user.id', '=','city.id')
       ->join('state','state.id','=','city.id')
       ->join('country','country.id','=','state.id')
       ->select('user.name as user','user.secondname','user.email','city.name as city','state.name as state','country.name as country','country.iso','country.iso3','country.numcode','country.phonecode')->get();
       if(count($userphone)>0){
            return response()->json($userphone); 
       }else{
           return response()->json("Phone not fount"); 
       }
    }

}