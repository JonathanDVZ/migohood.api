<?php
namespace App\Http\Controllers;
use App\Models\Notification;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DB;

class ControllerNotification extends Controller
{
    public function GetNotification(Request $request){
        $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
             $getrent = DB::table('comment')->join('notification','notification.comment_id','=','comment.id')
             ->join('user','user.id','=','comment.user_id')
             ->join('service','service.id','=','comment.cod_service')
             ->where('notification.user_id','=',$request->input("user_id"))
             ->select('user.name','comment.content as comment','comment.date','service.title','notification.id')
             ->get();
             if(count($getrent)>0){
                  return response()->json($getrent);
             }else{
                    return response()->json("Notification not found");
             }

        }     
        
    }

    public function DeleteNotification(Request $request){
         $rule=[
            'id' => 'required|numeric|min:1'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
              $notification=Notification::where('id',$request->input('id'))->first();
             if($notification!=null){
                   DB::table('notification')->where('id',$notification->id)->delete();
                  return response()->json('Notification Delete');
              }else{
                 return response()->json('Notification not found');
               }
            }     
    }
}