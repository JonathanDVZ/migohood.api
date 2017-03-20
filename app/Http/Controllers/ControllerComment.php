<?php
namespace App\Http\Controllers;
use App\Models\Notification;
use App\Models\User;
use App\Models\Comment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use	Illuminate\Encryption\Encrypter;
use validator;
use DB;
use DateTime;

class ControllerComment extends Controller
{
    public function AddComment(Request $request){
            $rule=[
           'user_id' => 'required|numeric|min:1',
           'service_id'=>'required|numeric|min:1',
           'content'=>'required'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
         $user=User::where('id','=',$request->input('user_id'))->first();
          if(count($user)>0){
               $service=Service::where('id','=',$request->input('service_id'))->first();
               if(count($service)>0){
                     $dt = new DateTime();
                     $newcomment=new Comment();
                     $newcomment->date=$dt->format('Y-m-d H:i:s');
                     $newcomment->content=$request->input("content");
                     $newcomment->cod_service=$service->id;
                     $newcomment->user_id=$user->id;
                     if($newcomment->save()){
                         $newnotification=new Notification();
                         $newnotification->comment_id=$newcomment->id;
                         $newnotification->user_id=$service->user_id;
                         $newnotification->save();
                         return response()->json("Comment Send");
                     }
               }else{
                   return response()->json("Service not found");
               }
          }else{
              return response()->json("User not found");
          }
        }
    }

    public function DeleteComment(Request $request){
          $rule=[
            'comment_id' => 'required|numeric|min:1'
            ];
        $validator=Validator::make($request->all(),$rule);
         if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
             $comment=Comment::where('id','=',$request->input('comment_id'))->first();
             if($comment!=null){
                  $comment->delete();
                  return response()->json('Comment Delete');
              }else{
                 return response()->json('Comment not found');
               }
            }     
    }

    public function ReadCommentUser(Request $request){
         $rule=[
           'user_id' => 'required|numeric|min:1'
      ];
      $validator=Validator::make($request->all(),$rule);
      if ($validator->fails()) {
        return response()->json($validator->errors()->all());
        }else{
            $user=User::where('id','=',$request->input('user_id'))->first();
            if(count($user)>0){
                 $comment = DB::select('select * from comment where user_id=?', [$user->id]);
               if(count($comment)>0){
                    return response()->json($comment);
               }else{
                    return response()->json("The user does not comment");
               }
            }
            else{
                return response()->json("User not found");
            }
        }
    }
}