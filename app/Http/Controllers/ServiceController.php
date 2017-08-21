<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Crypt;
	use Illuminate\Contracts\Encryption\DecryptException;
	use	Illuminate\Encryption\Encrypter;
	use validator;
	use Aws\S3\S3Client;
	use Aws\Exception\AwsException;
	use Aws\S3\Exception\S3Exception;
	use DateTime;
	use DB;
	use App\Models\Service;
	use App\Models\User;
	use App\Models\Service_Type_Category;
	use App\Models\Service_Category;
	use App\Models\Type_Category;
	use App\Models\Category;
	use App\Models\Languaje;
	use App\Models\Service_Languaje;

	class ServiceController extends Controller {
		//Muestra todo los service
	    public function ReadService(){
	    //Se obtiene todos los servicios que se crean
	    return Service::all();
    	}

    	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    //Agrega un servicio(web)
	    public function AddNewServiceStep(Request $request){
	         $rule=[
	            'user_id' => 'required|numeric|min:1',
	            'category_code'=>'required|numeric|min:1',
	        ];
	        $use = 1;
	        $code = 4;
	        $validator=Validator::make($request->all(),$rule);
	        if ($validator->fails()) {
	            return response()->json($validator->errors()->all());
	        }else{
	              $user=User::select()->where('id',$use)->first();
	              $category=Category::select('id')->where('code',$code)->get();
	              if(count($user)>0){
	                 if(count($category)>0){
	                     $newspace=new Service;
	                     $dt = new DateTime();
	                     $newspace->date=$dt->format('Y-m-d (H:m:s)');
	                     $newspace->user_id=$user->id;
	                     $newspace->save();
	                     foreach ($category as $categorys){
	                            $newservicateg=new Service_Category;
	                            $newservicateg->service_id=$newspace->id;
	                            $newservicateg->category_id=$categorys->id;
	                            $newservicateg->save();
	                            
	                           }
	                     return  response()->json($newspace);
	                }else{
	                     return  response()->json("Category Not Found");
	                 }
	              }else{
	                 return  response()->json("User Not Found");
	              }
	        }

	    }

		    //Agregar Service(service-step1)-Web
	    public function AddNewServiceStep1(Request $request){
	        $rule=[
	            // Comente esto, ya que aun no poseo ningun id service
	            'service_id' => 'required|numeric|min:1',
	            'recreational' =>'boolean'
	        ];
	        $validator=Validator::make($request->all(),$rule);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
            	 $service=Service::where('id',$request->input("service_id"))->first();
            	 if(count($service)>0){
            	 	$valtrules=Service_Type_Category::where('service_id',$service->id)->get();
            	 if(count($valtrules)==0){
                try{
                	 $newrules=new Service_Type_Category;
	                 $newrules->service_id=$service->id;
	                 $newrules->type_categories_id=1;
	                 $newrules->check=$request->input("recreational");
	                 $newrules->save();
	                 return response()->json('Add Step-1');
                }catch(Exception $e){
                       return response()->json($e);
                }
            }else{
                DB::table('service_type_category')->where('service_id',$service->id)->delete();
                 $newrules=new Service_Type_Category;
                 $newrules->service_id=$service->id;
                 $newrules->type_categories_id=1;
                 $newrules->check=$request->input("recreational");
                 $newrules->save();
                 return response()->json('Update Step-1');
             }else{
                 return response()->json('Service not found');
            	}
	    	}
		}	


	     public function ReturnStep(Request $request){
		           $rule=[
		           'service_id' => 'required|numeric'
		      ];
		      $validator=Validator::make($request->all(),$rule);
		      if ($validator->fails()) {
		            return response()->json($validator->errors()->all());
		      }else{
		             $getreturn = DB::table('service')->join('service_category','service.id','=','service_category.service_id')
		              ->join('category','category.code','=','service_category.category_id')
		              ->where('service_id','=',$request->input("service_id"))
		              ->where('category.languaje','=',$request->input("languaje"))
		              ->select('service.id','service.date','category.name')
		             ->get();
		             if(count($getreturn)>0){
		                    return response()->json($getreturn);
		             }else{
		                     return response()->json('Not Found');
		             }

		      }
		    }

		    public function ReturnStep1(Request $request){
		            $rule=[
		           'service_id' => 'required|numeric'
		      ];
		      $validator=Validator::make($request->all(),$rule);
		      if ($validator->fails()) {
		            return response()->json($validator->errors()->all());
		      }else{
		      	$getstep1 = DB::table('service')->where('service.id','=',$request->input("service_id"))
		      	->select('service.id')
              	->first() ;
              if(count($getstep1)>0){
                    return response()->json($getstep1);
              }else{
                    return response()->json('Not Found');
              }

		      }
		      }

    }

?>