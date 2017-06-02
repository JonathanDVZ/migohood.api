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
use Illuminate\Support\Facades\Auth;
use validator;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\imagecreatefromjpeg;


class ControllerUser extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/
   
    public function Create(Request $request)
    {   
        // Ejecutamos el validador, en caso de que falle devolvemos la respuesta
        $rule=[
            'name'=>"required|string|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45",
            'email'=>'required|email|unique:user,email',
            'password'=>'required',
            'lastname'=>"required|string|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45"
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        else{ //devuelve un email
           try{
                       $newUser=new User();
                       $newUser->name=ucwords(strtolower($request->input('name')));
                       $newUser->email=strtolower($request->input('email'));
                       $newUser->password=Crypt::encrypt($request->input('password'));
                       $newUser->avatar=url('/').'/files/avatars/avatar_default.png';
                       $newUser->avatar_original = url('/').'/files/avatars_originals/avatar_original_default.png';
                       $newUser->lastname=ucwords(strtolower($request->input('lastname')));
                       $newUser->remember_token=str_random(100);
                       $newUser->confirm_token=str_random(100);
                       $newUser->address=strtolower($request->input('address'));
                       if( $newUser->save()){
                          return response()->json($newUser); 
                       }
                 
            }catch(Exception $e){
                return response()->json($e);
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
           'user_id' => 'required|numeric|min:1',
          'name'=>"required|string|regex:/^[a-zA-Z_áéíóúàèìòùñ'\s]*$/|max:45"
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('user_id'))->first();
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
           'user_id' => 'required|numeric|min:1',
           'email'=>'required|email|unique:user,email'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $date=User::where('id','=',$request->input('user_id'))->first();
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
            'thumbnail' => 'required|image',
            'user_id'=>'required|min:1'
        );
        $validator =Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return response()->json($validator->errors()->all())->setStatusCode(400, 'Bad Request');
        }
        else
        {
             // Buscamos el usuario que posea el id ingresado
            $user = User::select()->where('id',$request->input('user_id'))->first();
            
            try{
                // Se definen las credenciales del cliente s3 de amazon
                $s3 = new S3Client([
                    'version'     => env('S3_VERSION'),
                    'region'      => env('S3_REGION'),
                    'credentials' => [
                        'key'    => env('S3_KEY'),
                        'secret' => env('S3_SECRET')
                    ]
                ]);

                $avatar_link = 'https://s3.'.env('S3_REGION').'.amazonaws.com/'.env('S3_BUCKET').'/files/avatars/';
                $avatar_original_link = 'https://s3.'.env('S3_REGION').'.amazonaws.com/'.env('S3_BUCKET').'/files/avatars_originals/';

                // Obtenemos el campo file definido en el formulario
                $file = $request->file('thumbnail');            
                // Creamos un nombre para nuestro thumnail
                $name = date('dmYHis').'_thumbnail_user_'.$user->id.'.'.$file->getClientOriginalExtension();            
                // Movemos el archivo a una carpeta temporal
                $file->move('files/avatars_originals/',$name);

                $old_thumbnail = str_replace($avatar_link,'',$user->avatar);

                $s3->deleteObject([
                    'Bucket' => env('S3_BUCKET'),
                    'Key'    => 'files/avatars/'.$old_thumbnail
                ]);  

                $s3->deleteObject([
                    'Bucket' => env('S3_BUCKET'),
                    'Key'    => 'files/avatars_originals/'.$old_thumbnail
                ]);  

                $s3->putObject([
                    'Bucket' => env('S3_BUCKET'),
                    'Key'    => 'files/avatars_originals/'.$name,
                    'Body'   => fopen('files/avatars_originals/'.$name,'r'),
                    'ACL'    => 'public-read'
                ]);                 

                // Borramos el arrchivo de la carpeta temporal
                $this->createThumbnail($name);
                unlink('files/avatars_originals/'.$name);

                DB::table('user')
                    ->where('id', $user->id )
                    ->update(['avatar' => $avatar_link.$name,'avatar_original' => $avatar_original_link.$name]);
                
                return response()->json(['message' => 'Update completed!', 'avatar' => $avatar_link.$name, 'avatar_original' => $avatar_original_link.$name ])->setStatusCode(200, 'Ok');
            }
            catch (S3Exception $e) {
                return response()->json($e->getMessage())->setStatusCode(400, 'Bad Request');
            } catch (AwsException $e) {
                return response()->json($e->getMessage())->setStatusCode(400, 'Bad Request');
            }catch (Exception $e){
                return response()->json($e->getMessage())->setStatusCode(400, 'Bad Request');
            }
        }
    } 

   private function createThumbnail($image){
        $thumbnail = $image;
        
        // Ponemos el .antes del nombre del archivo porque estamos considerando que la ruta está a partir del archivo thumb.php
        $file_info = getimagesize("files/avatars_originals/".$thumbnail);
        $width = $file_info[0];
        $height = $file_info[1];
        $type = $file_info[2];
        
        // Obtenemos la relación de aspecto
        $ratio = $width / $height;
        // Calculamos las nuevas dimensiones
        $newwidth = 400;
        $newheight = round($newwidth / $ratio);

        // Dependiendo del tipo de imagen llamamos a distintas funciones
        if($type==1)    $img=imagecreatefromgif("files/avatars_originals/".$thumbnail); 
        if($type==2)    $img=imagecreatefromjpeg("files/avatars_originals/".$thumbnail); 
        if($type==3)    $img=imagecreatefrompng("files/avatars_originals/".$thumbnail); 
        
        // Creamos la miniatura
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        // La redimensionamos
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // Reescribir imagen original por la nueva y liberar memoria
        imagejpeg($thumb,'files/avatars_originals/'.$thumbnail);
        imagedestroy($thumb);

        $s3 = new S3Client([
            'version'     => env('S3_VERSION'),
            'region'      => env('S3_REGION'),
            'credentials' => [
                'key'    => env('S3_KEY'),
                'secret' => env('S3_SECRET')
            ]
        ]);

        $s3->putObject([
            'Bucket' => env('S3_BUCKET'),
            'Key'    => 'files/avatars/'.$thumbnail,
            'Body'   => fopen('files/avatars_originals/'.$thumbnail,'r'),
            'ACL'    => 'public-read'
        ]); 
    }

   //Actualiza Address
    public function UpdateAddress(Request $request){
      $rule=[
           'id' => 'required|numeric|min:1'
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
           'id' => 'required|numeric|min:1',
           'city_id'=>'numeric|min:1'
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
                          return response()->json($user);
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
            'id'=>'required|numeric|min:1',
            'number'=>'required|numeric|min:1',
            'type_id'=>'required|numeric|min:1|max:2'
            ];
        $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{//Busca si el usuario se encuentra registrado
             $date=User::where('id','=',$request->input('id'))->first();
             if($date!=null){
                  $addphone=new Phone();
                  $addphone->number=$request->input('number');
                  $val=Phone::where('user_id','=',$request->input('id'))->where('number','=',$request->input("number"))->first();
                  if(count($val)==0){
                        $addphone->type_id=$request->input('type_id');
                        $addphone->user_id=$date->id;
                        if($addphone->save()){
                              return response()->json('Add Phone');
                        }
                   }else{
                       return response()->json('This number is already registered');
                   }
              }else{
                   return response()->json('User not found ');
             }
        }        
   }

//Modificar Phone
public function UpdatePhone(Request $request){
      $rule=[
           'phone_id' => 'required|numeric|min:1',
           'number'=>'required|numeric|min:1',
           'type_id'=>'required|numeric|min:1|max:2'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
               $phone = Phone::select()->where('id',$request->input("phone_id"))->first();
                 if (count($phone)>0){
                      DB::table('phone_number')->where('id',$phone->id)->update(
                            ['number'=>$request->input("number"),
                              'type_id'=>$request->input("type_id")
                            ]);
                       return response()->json('Update Phone ');     
                  }else{
                  return response()->json('Phone not found ');
                  }
              }
 }
  
   //Muestra  los Telefonos de un Usuario    
    public function GetPhone(Request $request)
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
                  //$phone=Phone::where('user_id',$user->id)->first();
                  $phone=Phone::where('user_id','=',$user->id)->get();
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
            'id' => 'required|numeric|min:1'
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
            $user=User::where('id','=',$request->input("user_id"))->first();
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
                if(count($user->city_id)>0){
                     $city=City::where('id','=',$user->city_id)->get();
                 return response()->json($city);
                 }else{
                    return response()->json("Your city is not registered");
                 }
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
               if(count($city)>0){
                     $state=State::where('id','=',$city->state_id)->first(); 
                     return response()->json($state);
               }else{
                      return response()->json("Your state is not registered");
               }
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
               if(count($city)>0){
                     $state=State::where('id','=',$city->state_id)->first();
                     $country=Country::where('id','=',$state->country_id)->first(); 
               return response()->json($country);
               }else{
                   return response()->json("Your country is not registered");
               }
            }
            else{
                return response()->json("User not found");
            }
        }
    }

public function LoginOauth(Request $request){
   $rule=[
           'email' => 'required|email'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $user=User::where('email','=',$request->input("email"))->first();
           if(count($user)>0){
                return response()->json($user);
           }else{
               return response()->json("User not found");
           }           
        }   
}

public function UserOauth(Request $request){
     $rule=[
           'email' => 'required|email|unique:user,email',
           'name'=>'required',
           'avatar'=>'required',
           'avatar_original'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
                try{
                        $newuser=New User;
                        $newuser->name=ucwords(strtolower($request->input('name')));
                        $newuser->email=strtolower($request->input("email"));
                        $newuser->avatar=$request->input("avatar");
                        $newuser->avatar_original=$request->input("avatar_original");
                        $newuser->remember_token=str_random(100);
                        $newuser->confirm_token=str_random(100);
                        $newuser->save();
                        return response()->json($newuser); 
                }catch(Exception $e){
                    return response()->json($e);
                }    
                       
        }
   }

  //Verification Email
  public function VerificationEmail(Request $request){
        $rule=[
           'email' => 'required|email',
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
                $user=User::where('email','=',$request->input("email"))->first();
           if(count($user)>0){
                return response()->json("True");
           }else{
               return response()->json("False");
           }       
        }
  }     

}