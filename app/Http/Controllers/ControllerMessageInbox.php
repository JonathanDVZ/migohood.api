<?php
namespace App\Http\Controllers;
use App\Models\Message;
use App\Models\Inbox;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DateTime;
use DB;

class ControllerMessageInbox extends Controller
{
       public function ReadMessage(Request $request){
           $rule=[
              'user_id' => 'required|numeric|min:1'
            ];
          $validator=Validator::make($request->all(),$rule);
          if ($validator->fails()) {
             return response()->json($validator->errors()->all());
           }else{
              $usermessage = DB::select('select content,date from message where user_id=?', [$request->input('user_id')]);
            if(count($usermessage)>0){
                  return response()->json($usermessage);
             }
            else{
                return response()->json("Message not fount");
            }
        }
       }
        
        //Muestra todos
        public function ReadInbox(Request $request){
            $rule=[
              'user_id' => 'required|numeric|min:1'
            ];
          $validator=Validator::make($request->all(),$rule);
          if ($validator->fails()) {
             return response()->json($validator->errors()->all());
           }else{
                 $getrent = DB::table('inbox')->join('user','inbox.receiver_id','=','user.id')
         ->where('inbox.transmiter_id','=',$request->input("user_id"))
         ->select('inbox.transmiter_id','user.name as receiver')
         ->get();
         if(count($getrent)>0){
             return response()->json($getrent); 
         }else{
             return response()->json("Rent not fount"); 
         }
         }
       }

     
       public function CreateMessageInbox(Request $request){
            $rule=[
            'user_id'=>'required|numeric|min:1',
            'receiver_id'=>'required|numeric|min:1',
            'content'=>'required'
          ];
        $validator=Validator::make($request->all(),$rule);
        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }
        else{
             $user = User::select()->where('id',$request->input("user_id"))->first();        
                if (count($user)>0){
                       
                       $receiver = User::select()->where('id',$request->input("receiver_id"))->first();
                       if($receiver==null){
                           return response()->json("Receiver not found");
                       }else{
                           $newinbox=new Inbox();
                           $newmessage=new Message();
                           $newinbox->receiver_id=$request->input("receiver_id");
                           $newinbox->transmiter_id=$user->id;
                           $newinbox->save();
                           $newmessage->content=$request->input("content");
                           $dt = new DateTime();
                           $newmessage->date=$dt->format('Y-m-d H:i:s');
                           $newmessage->user_id=$user->id;
                           $newmessage->inbox_id=$newinbox->id;
                           $newmessage->save();
                           if($newinbox->save() && $newmessage->save()){
                              $newnotification=new Notification();
                                $newnotification->user_id=$request->input("receiver_id");
                                $newnotification->message_id=$newmessage->id;
                                $newnotification->save();
                                return response()->json("Message Sent"); 
                           }
                       }
                    }else{
                         return response()->json("User not found"); 
                }
            }
          }

        public function DeleteMessage(Request $request){
            $rule=[
                'message_id' => 'required|numeric|min:1'
              ];
             $validator=Validator::make($request->all(),$rule);
             if ($validator->fails()) {
                 return response()->json($validator->errors()->all());
             }else{
                 $message=Message::where('id','=',$request->input('message_id'))->first();
                 if($message!=null){
                      $message->delete();
                      return response()->json('Message Delete');
                 }else{
                    return response()->json('Message not found');
                  }
               }      
           }  
     
}

