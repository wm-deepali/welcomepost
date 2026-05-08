<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App;  

use Illuminate\Support\Facades\Auth; 
use Validator,Redirect,Response,File;
use DB;
use App\Http\Controllers\Controller as Controller;

class UserController extends Controller
{
    
    public function getCountryProducts()
    {
        $category  = DB::table('categories')->get(['id','name']);
        // return $category;
        foreach($category as $cat) {
            
            
            $data = DB::table('products')->where('cateory_id', $cat->id)->get();
            foreach($data as $dt) {
                $country = DB::table('contries_product')->where('product_id', $dt->id)->pluck('country');
                $dt->country = $country;
            }
           
            $catCountry = DB::table('contries_product')->where('cateory_id', $cat->id)->pluck('country');		   
            $cat->products = $data;
            $cat->catCountry = $catCountry;
            // return $cat;
        }
        
        return response()->json([
            "response_code" => 200,
            "response_msg" =>"ok",
            "data" => $category
            ]);
        
    }
    
    
    public function getCategoryProducts()
    {
        $category  = DB::table('categories')->get(['id','name']);
        // return $category;
        foreach($category as $cat) {
            $data = DB::table('products')->where('cateory_id', $cat->id)->get();
            $cat->products = $data;
            // return $cat;
        }
        
        return response()->json([
            "response_code" => 200,
            "response_msg" =>"ok",
            "data" => $category
            ]);
        
    }

		public function create_user_profile(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          'email'=>'required|unique:users',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			
			$length = 50;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			$randomString;
			
			$id = DB::table('users')-> insertGetId(
			array(
					'name'=>$request->name,
					'email'=>$request->email,
					'password'=>$request->password,
					'remember_token'=>$randomString,
					'role_id'=>'2'
				));
				
				
			
			
            if($id){
				
			$userinfo = DB::table('users')->where('id',$id)->get();
			$usr_id =  $userinfo[0]->id;
			$string_user_id = (string)$usr_id;
			$success['id'] =  $string_user_id;
			$success['name'] =  $userinfo[0]->name;
			$success['email'] =  $userinfo[0]->email;
			$success['password'] =  $userinfo[0]->password;
			$success['remember_token'] =  $userinfo[0]->remember_token;

			return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
			
			}
			
		}
		
		}
		
		
		public function login(Request $request){
			 
			 
			 $validator = Validator::make($request->all(), [ 
            'password' => 'required', 
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			 
			 $mobile = $request->mobile_no;
			 $email = $request->email;
			 $password = $request->password;
 
			 $result = DB::select(DB::raw("select * from users where email = '$mobile' OR mobile_no = '$mobile'"));
					
			  if($result){
				
				
				//$mob_info = DB::table('users')->where('mobile_no',$request->mobile_no)->where('password',$request->password)->get();
				$mob_info = DB::select(DB::raw("select * from users where  mobile_no = '$mobile' AND password = '$password' "));
				$email_info = DB::select(DB::raw("select * from users where  email = '$mobile' AND password = '$password' "));

				if($mob_info){
				$success['mobile_no'] =  $mob_info[0]->mobile_no;
				$success['user_id'] =  $mob_info[0]->id;
				$success['remember_token'] =  $result[0]->remember_token;
				return response()->json(['success'=>$success,'response_code' => 200]); 
				}elseif($email_info){
				$success['mobile_no'] =  $email_info[0]->mobile_no;
				$success['user_id'] =  $email_info[0]->id;
				$success['remember_token'] =  $result[0]->remember_token;
				return response()->json(['success'=>$success,'response_code' => 200]); 
				}else{
					 return response()->json(['response_result'=>'Invalid Password','response_code' => 401]); 

				}
				
			  }else{
				 return response()->json(['response_result'=>'Invalid Details','response_code' => 401]); 
				}	
			  
		
		 
		 }
		 
		 
		 }
		 
		 public function getUserInformation(Request $req){
    
    $validator=Validator::make($req->all(),[
     'user_id'=>'required|exists:users,id',
    ]); 
            if($validator->fails()){
 
               return response()->json(
                [
                    'response_code' => 401,
                    'response_message' => $validator->errors()->first()
                ],
                200
            );
        }
     $userinfo['result'] = DB::table('users')->where('id',$req->user_id)->get();
   // $userInfo['result']=User::where('id',$req->user_id)->first()->toArray();
     return response()->json(['success' => $userinfo,'response_code'=>200]);
     }
	 
	 public function create_employs(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          'email'=>'required|unique:employs',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			
			$length = 50;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			$randomString;
			
			$id = DB::table('employs')-> insertGetId(
			array(
					'name'=>$request->name,
					'email'=>$request->email,
					'password'=>$request->password,
					'remember_token'=>$randomString,
					'role_id'=>'2'
				));
				
				
            if($id){
				
			$userinfo = DB::table('employs')->where('id',$id)->get();
			$usr_id =  $userinfo[0]->id;
			$string_user_id = (string)$usr_id;
			$success['id'] =  $string_user_id;
			$success['name'] =  $userinfo[0]->name;
			$success['email'] =  $userinfo[0]->email;
			$success['password'] =  $userinfo[0]->password;
			$success['remember_token'] =  $userinfo[0]->remember_token;

			return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
			
			}
			
		}
		
		}
		
		public function create_category(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

         // 'email'=>'required|unique:employs',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			if($request->file('image')){
			$imageName = time().'.'.$request->image->extension();
			$request->image->move(public_path('uploads/categories'),$imageName);
			$product_image=url('public/uploads/categories').'/'.$imageName;
			}else{
			$product_image=url('public/uploads/categories/dummy.jpeg'); 
			} 
			
			$id = DB::table('categories')-> insertGetId(
			array(
					'name'=>$request->name,
					'icon'=>$product_image
				));
				
				
            if($id){
				
			$userinfo = DB::table('categories')->where('id',$id)->get();
			$usr_id =  $userinfo[0]->id;
			$string_user_id = (string)$usr_id;
			$success['id'] =  $string_user_id;
			$success['name'] =  $userinfo[0]->name;
			$success['icon'] =  $userinfo[0]->icon;
			
			return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
			
			}
			
		}
		
		}
		
		
		public function get_category(){
		 
		    $userinfo = DB::table('categories')->get();
            return response()->json(['success' => $userinfo,'response_code'=>200]);
			
		}
		
		public function employ_login(Request $request){
			 
			 
			 $validator = Validator::make($request->all(), [ 
            'password' => 'required', 
            'email' => 'required', 
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			 
				$email = $request->email;
				$password = $request->password;
				
				
				$result = DB::table('employs')->where('email',$email)->where('password',$password)->exists();
				if($result){
					
				$userinfo = DB::table('employs')->where('email',$email)->get();			 
				$success['employ_id'] =  $userinfo[0]->id;
				$success['name'] =  $userinfo[0]->name;
				$success['email'] =  $userinfo[0]->email;
				$success['remember_token'] =  $userinfo[0]->remember_token;
				return response()->json(['success'=>$success,'response_code' => 200]); 
				
				
			     }else{
				   return response()->json(['response_result'=>'Invalid Details','response_code' => 401]); 
				 }	
			  
		
		 
		 }
		 
		 
		 }
	 
	 public function get_employs(){
		 
		 $userinfo = DB::table('employs')->get();
     return response()->json(['success' => $userinfo,'response_code'=>200]);
		 
	 }
	 
	 public function update_employs(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          'employ_id'=>'required|exists:employs,id',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			$update = DB::table('employs')->where('id',$request->employ_id)->update(
		array(
			'name'=>$request->name,
			'password'=>$request->password,
		));
		
				
            if($update){
				
			$userinfo = DB::table('employs')->where('id',$request->employ_id)->get();
			$usr_id =  $userinfo[0]->id;
			$string_user_id = (string)$usr_id;
			$success['id'] =  $string_user_id;
			$success['name'] =  $userinfo[0]->name;
			$success['email'] =  $userinfo[0]->email;
			$success['password'] =  $userinfo[0]->password;
			$success['remember_token'] =  $userinfo[0]->remember_token;

			return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
			
			}
			
		}
		
		}
		
		
		public function delete_employs(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          'employ_id'=>'required|exists:employs,id',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			DB::table('employs')->where('id',$request->employ_id)->delete();
		    return response()->json(['success'=>'Data Deleted Successfully','response_code' => 200]); 
			exit();
				
            
		}
		
		}
		
		
		public function create_products(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          //'email'=>'required|unique:employs',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			if($request->file('image')){
			$imageName = time().'.'.$request->image->extension();
			$request->image->move(public_path('uploads/products'),$imageName);
			$product_image=url('public/uploads/products').'/'.$imageName;
			}else{
			$product_image=url('public/uploads/products/dummy.jpeg'); 
			} 
			
		
			$id = DB::table('products')-> insertGetId(
			array(
					'cateory_id'=>$request->cateory_id,
					'name'=>$request->name,
					'image'=>$product_image,
					'price'=>$request->price,
					'description'=>$request->description
					));
				
				
            if($id){
				
			$userinfo = DB::table('products')->where('id',$id)->get();
			$usr_id =  $userinfo[0]->id;
			$string_user_id = (string)$usr_id;
			$success['id'] =  $string_user_id;
			$success['name'] =  $userinfo[0]->name;
			$success['image'] =  $userinfo[0]->image;
			$success['price'] =  $userinfo[0]->price;

			return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
			
			}
			
		}
		
		}
		
		public function get_products(){
		 
		    $userinfo = DB::table('products')->get();
            return response()->json(['success' => $userinfo,'response_code'=>200]);
			
		}
	  
		
		public function add_to_cart(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          //'email'=>'required|unique:employs',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			$product_id = $request->product_id;
			
			$result = DB::table('shopping_cart')->where('product_id',$product_id)->where('status','0')->where('user_id',$request->user_id)->exists();
			if($result){
				
				$exist_product_id = DB::table('shopping_cart')->where('product_id',$product_id)->where('status','0')->where('user_id',$request->user_id)->get();
				$quantity = $exist_product_id[0]->quantity;
				$unique_id = $exist_product_id[0]->id;
				$product_price = $exist_product_id[0]->price;
				
				$updated_quantity = $quantity + 1;
				$total_amount = $updated_quantity * $product_price;
				
				DB::table('shopping_cart')->where('product_id',$product_id)->update(
				array(
				'quantity'=>$updated_quantity,
				'total'=>$total_amount
				));
				
				$count_total = DB::table('shopping_cart')->where('user_id',$request->user_id)->where('status','0')->sum('total');
				$sum_total = DB::table('shopping_cart')->where('user_id',$request->user_id)->where('status','0')->sum('quantity');
				
				$userinfo = DB::table('shopping_cart')->where('id',$unique_id)->get();
			$success['product_id'] =  $userinfo[0]->product_id;
			$success['user_id'] =  $userinfo[0]->user_id;
			$success['quantity'] =  $userinfo[0]->quantity;
			$success['price'] =  $userinfo[0]->price;
			$success['status'] =  $userinfo[0]->status;
			$success['total_amount'] =  $count_total;
			$success['total_products'] =  $sum_total;

			return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
			
		}else{
			
			$id = DB::table('shopping_cart')-> insertGetId(
			array(
					'product_id'=>$request->product_id,
					'user_id'=>$request->user_id,
					'quantity'=>1,
					'price'=>$request->price,
					'total'=>$request->price,
					'status'=>"0"
					));
					
			if($id){
				
			$count_total = DB::table('shopping_cart')->where('user_id',$request->user_id)->where('status','0')->sum('total');
			$sum_total = DB::table('shopping_cart')->where('user_id',$request->user_id)->where('status','0')->sum('quantity');
				
			$userinfo = DB::table('shopping_cart')->where('id',$id)->get();
			$success['product_id'] =  $userinfo[0]->product_id;
			$success['user_id'] =  $userinfo[0]->user_id;
			$success['quantity'] =  $userinfo[0]->quantity;
			$success['price'] =  $userinfo[0]->price;
			$success['status'] =  $userinfo[0]->status;
			$success['total_amount'] =  $count_total;
			$success['total_products'] =  $sum_total;

			return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
			
			}
			
		}
			
			
		}
		
		}
		
		public function user_cart_count(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          //'employ_id'=>'required|exists:employs,id',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
						
			$sum_total = DB::table('shopping_cart')->where('user_id',$request->user_id)->where('status','0')->sum('quantity');
			$count_total = DB::table('shopping_cart')->where('user_id',$request->user_id)->where('status','0')->sum('total');


			$success['count'] =  $sum_total;
			$success['totalAmount'] =  $count_total;
			
			
		    return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
				
            
		}
		
		}
		
		public function user_shopping_cart($user_id){
			
			//$userinfo = DB::table('shopping_cart')->where('user_id',$user_id)->where('status','0')->get();
			$userinfo = DB::table('shopping_cart')
			->where('user_id',$user_id)
			->where('status','0')
			->join('products', 'products.id','=','shopping_cart.product_id')
			->get(['shopping_cart.*','products.name','products.image']);
			
			
			return response()->json(['success'=>$userinfo,'response_code' => 200]); 
			exit();

		}
		
		
		public function user_product_history(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          //'email'=>'required|unique:employs',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			$userinfo = DB::table('shopping_cart')->where('user_id',$request->user_id)->get();
			return response()->json(['success'=>$userinfo,'response_code' => 200]); 
			exit();
			
		}

		}
		
		
		public function user_billing(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          //'email'=>'required|unique:employs',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			$id = DB::table('payment')-> insertGetId(
			array(
					'user_id'=>$request->user_id,
					'total'=>$request->total
					));
					
            if($id){
				
			DB::table('shopping_cart')->where('user_id',$request->user_id)->update(
			array('status'=>'1'));
				
			$userinfo = DB::table('payment')->where('id',$id)->get();
			$usr_id =  $userinfo[0]->user_id;
			$string_user_id = (string)$usr_id;
			$success['id'] =  $string_user_id;
			$success['total'] =  $userinfo[0]->total;

			return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
			
			}
			
		}

		}
		
		
		public function get_answer(){
		 
		    $userinfo = DB::table('answer')->get();
            return response()->json(['success' => $userinfo,'response_code'=>200]);
			
		}
		
		
		public function post_answer(Request $request){
			
			$validator = Validator::make($request->all(), [ 

		  //'mobile_no' => 'required|string|unique:users|min:10|max:15',

          //'email'=>'required|unique:employs',
        ]);

         if ($validator->fails()){
            return response()->json(
                [
                    'response_code' => 401,
                    'response_result' => $validator->errors()->first()
                ],
                200
            );
        }else{
			
			$id = DB::table('answer')-> insertGetId(
			array(
					'answer_text'=>$request->answer,
					'qid'=>'1'
					));
					
					
				
				
            if($id){
				
			
			$userinfo = DB::table('answer')->where('id',$id)->get();
			$success['answer_text'] =  $userinfo[0]->answer_text;

			return response()->json(['success'=>$success,'response_code' => 200]); 
			exit();
			
			}
			
		}

		}
		
		
		
		
}
