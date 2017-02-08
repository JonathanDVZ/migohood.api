<?php
namespace App\Http\Controllers;
use App\Models\Message;
use App\Models\Inbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DB;

class ControllerMessageInbox extends Controller
{
       public function ReadMessage(){
           return Message::all();
       }
        
        public function ReadInbox(){
           return Inbox::all();
       }

       public function CreateMesage(){
            $rule=[
            'name'=>'required|regex:/^[a-zA-Z_áéíóúàèìòùñ\s]*$/|max:45',
            'email'=>'email|required',
            'password'=>'required',
            'thumbnail'=>'required|regex:/^[a-zA-Z_áéíóúàèìòùñ\s]*$/|max:45',
            'secondname'=>'required|regex:/^[a-zA-Z_áéíóúàèìòùñ\s]*$/|max:45',
            'lastname'=>'required|regex:/^[a-zA-Z_áéíóúàèìòùñ\s]*$/|max:45',
            'address'=>'required',
            'city_id'=>'numeric|required'
        ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        else{
       }
}
}