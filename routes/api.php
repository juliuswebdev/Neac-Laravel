<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;


use App\User;
use App\Country;
use App\FormGroup;
use App\Post;
use App\Profile;
use App\Testimonial;
use App\Mails;
use App\Notification;
use App\Cart;
use App\UserReferred;
use App\ServiceCategory;
use App\Service;
use App\Currency;

use Illuminate\Support\Facades\Crypt;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login',  ['middleware'=>['cors','apitoken'], function (Request $request) {
    $post = $request->all();
  
    $rules = array(
        'email'    => 'required|email',
        'password' => 'required'
    );
    
    $validator = \Validator::make($post, $rules);
    
    if ($validator->fails()) {

        return response()->json($validator->messages());

    } else {
        $id = Helper::secure_enc($request->input('in') , 'd');
        $email = Helper::secure_enc($request->input('mn') , 'd');
        $au = User::where('id', $id)->where('email', $email)->first();

        $approval = 1;
        if(isset($post['in'])) {
            if($au) {
                $approval = (!$au->approval) ? 0 : 1;
            }
        }

        $userdata = array(
            'email'         => $request->input('email'),
            'password'      => $request->input('password'),
            'status'        => 1,
            'approval'      => $approval,
            'user_type'     => ['applicant', 'business']
        );

        if (Auth::attempt($userdata)) {
            $user = Auth::user();
            
            if( isset($post['in']) ) {
 
                

                if($au->approval != 1) {
                    $au->approval = 1;
                    $other['password'] = $request->input('password');
                    Helper::mail_formatter(1, $user, $other);
                    $au->update();
                }
            }

            $message = array(
                'alert' => 'success',
                'message' => 'Successfully Login!',
                'user' => $user,
                'token' =>  Crypt::encryptString($user->id)
            );
            return response()->json($message);
    
        } else {   

            $message = array(
                'alert' => 'danger',
                'message' => 'Invalid Login'
            );
            return response()->json($message);
    
        }
    }
}]);

Route::post('register',  ['middleware'=>['cors','apitoken'], function (Request $request) {
    $post = $request->all();

    $rules = array(
        'email'             => 'required|email|unique:users',
        'first_name'        => 'required',
        'last_name'         => 'required',
        'confirm_password'  => 'required|min:8|same:password'
    );
    $validator = \Validator::make($post, $rules);

    if ($validator->fails()) {
        return response()->json($validator->messages());
    } else {

        $login_route = 'login';
        $mail_id = 7;
        $user_type = ($request->input('u_t') == 'b') ? 'business' : 'applicant';

        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('confirm_password'));
        $user->user_type = $user_type;
        $user->profession = $request->input('profession');

        if($user_type == 'business') {
            $reseller_code = strtoupper(Helper::str_random(10));
            $find_reseller = User::where('reseller_code', $reseller_code)->get();

            if(count($find_reseller)) {
                $user->reseller_code = strtoupper(Helper::str_random(10)).'N';
            } else {
                $user->reseller_code = $reseller_code;
            }
        }
      
        $user->save();

        if($request->input('u_t') == 'b') {
            $mail_id = 15;
            $login_route = 'login-reseller';
            $post = new Post;
            $post->user_id = $user->id;
            $post->input_id = 483;
            $post->type = 'text';
            $post->post = $request->input('phone_number');
            $post->save();
        }
        
        $user->assignRole('applicant');
              
        $profile = new Profile;
        $profile->user_id = $user->id;
        $applicant_id = sprintf('%08d',$user->id);
        $profile->application_number = $applicant_id;
        $profile->save();

        $notification = new Notification;
        $notification->from_user_id = $user->id;
        $notification->module = 'register';
        $notification->messages = 'New registered applicant! ['. $request->input('email').']';
        $notification->save();

        $link = 'https://portal.medicalexamscenter.com/'.$login_route.'?in='.Helper::secure_enc($user->id).'&mn='.Helper::secure_enc($user->email);


        $other['email_register_validation_portal_link'] = $link;
        Helper::mail_formatter($mail_id, $user, $other);

        $message = array(
            'success' => 'Please check your email for account approval!'
        );
        return response()->json($message);
    }
}]);

Route::post('register-socialmedia', ['middleware'=>['cors','apitoken'], function (Request $request) {
    $email = $request->input('email');
    $user_check = User::where('email', $email)->first();
    if($user_check) {
        $message = array(
            'alert' => 'success',
            'token' =>  Crypt::encryptString($user_check->id)
        );
    } else {
        
        if($email) {
            
            $filename = $request->input('id') .'_nurse_'. time() . '.jpg';
            $newFilePath = public_path("documents") . "\'". $filename;
            $filePath = str_replace("'", "", $newFilePath);
            copy($request->input('picture'), str_replace("'", "", $filePath));

            $password = Helper::str_random(10);
            $user = new User;
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->password = Hash::make($password);
            $user->user_type = 'applicant';
            $user->save();
            $user->assignRole('applicant');
                
            $profile = new Profile;
            $profile->user_id = $user->id;
            $applicant_id = sprintf('%08d',$user->id);
            $profile->application_number = $applicant_id;
            $profile->image = $filename;
            $profile->save();

            $notification = new Notification;
            $notification->from_user_id = $user->id;
            $notification->module = 'register';
            $notification->messages = 'New registered applicant! ['. $request->input('email').']';
            $notification->save();



            Helper::mail_formatter(7, $user, $other);

            $message = array(
                'alert' => 'success',
                'token' =>  Crypt::encryptString($user->id)
            );
        }
    }
    return response()->json($message);
}]);

Route::post('forgotpassword',  ['middleware'=>['cors','apitoken'], function (Request $request) {
    $email = $request->input('email');
    $user = User::where('email', $email)->whereIn('user_type', ['applicant', 'business'])->where('status', 1)->select('id', 'email', 'status', 'first_name', 'last_name')->first();
    if($user) {
        $link = 'https://portal.medicalexamscenter.com/reset-password?in='.Helper::secure_enc($user->id).'&mn='.Helper::secure_enc($user->email).'&sn='.Helper::secure_enc($user->status);
        // $body = '
        //     <p>Dear '.$user->last_name.',</p>
        //     <p>Here is your Password Reset Link:<br>
        //         Email: <a href="mailto:'.$email.'" target="_blank">'.$email.'</a><br>
        //         Applicant No.: '.$user->profile->application_number.'
        //     </p>
        //     <p><a href="'.$link.'">'.$link.'</a></p>
        // ';
        // $data = [
        //     'body'          => $body,
        //     'full_name'     => $user->last_name .' '. $user->first_name,
        //     'to'            => $email
        // ];
        
        // Mail::send('mails.mail', $data, function($message) use ($data) {
        //     $message->to($data['to'], $data['full_name'])->subject('Account Registration');
        //     $message->from('no-reply@neac.com','NEAC Medical Exams Center');
        // });
        $other['email_reset_password_portal_link'] = $link;
        Helper::mail_formatter(8, $user, $other);

        $return = array(
            'alert' => 'success',
            'message' => 'Please check your email for password reset!',
        );
    } else {
        $return = array(
            'alert' => 'danger',
            'message' => 'Email not found!',
        );
    }
    return response()->json($return);
}]);

Route::post('resetpassword', ['middleware' => ['cors', 'apitoken'], function(Request $request) {
    $post = $request->all();
    $rules = array(
        'new_password'     => 'required|min:8',
        'confirm_password' => 'required|min:8|same:new_password'
    );
    $validator = \Validator::make($post, $rules);

    if ($validator->fails()) {
        return response()->json($validator->messages());
    } else {
        $id = Helper::secure_enc($request->input('in') , 'd');
        $email = Helper::secure_enc($request->input('mn') , 'd');
        $status = Helper::secure_enc($request->input('sn') , 'd');
        $user = User::where('id', $id)->where('email', $email)->where('status', $status)->first();
        $user->password = Hash::make($post['confirm_password']);
        $user->approval = 1;
        $user->update();
        $message = array(
            'success' => 'Successfully Updated!'
        );
        return response()->json($message);
    }
}]);

Route::post('reseller',  ['middleware'=>['cors','apitoken'], function (Request $request) {
    $post = $request->all();

    $rules = array(
        'email'    => 'required|email',
        'password' => 'required'
    );
    
    $validator = \Validator::make($post, $rules);
    
    if ($validator->fails()) {

        return response()->json($validator->messages());

    } else {
    
        $userdata = array(
            'email'         => $request->input('email'),
            'password'      => $request->input('password'),
            'status'        => 1,
            'approval'      => 1,
            'user_type'     => ['applicant', 'business']
        );
    
        if (Auth::attempt($userdata)) {
            $user = Auth::user();
            $update_user = User::find($user->id);
            if(!$update_user->reseller_code) {
                $reseller_code = strtoupper(Helper::str_random(10));
                $find_reseller = User::where('reseller_code', $reseller_code)->get();
                if(count($find_reseller)) {
                    $update_user->reseller_code = strtoupper(Helper::str_random(10)).'N';
                } else {
                    $update_user->reseller_code = $reseller_code;
                }
                $update_user->update();
            }
            $message = array(
                'alert' => 'success',
                'message' => 'Successfully Login!',
                'user' => $user,
                'token' =>  Crypt::encryptString($user->id)
            );
            return response()->json($message);
        } else {   
            $message = array(
                'alert' => 'danger',
                'message' => 'Invalid Login'
            );
            return response()->json($message);
    
        }
    }
}]);

Route::post('apply', ['middleware' => ['cors', 'apitoken'], function (Request $request) {

    $posts = $request->all();
    $rules = array(
        'email'             => 'required|email|unique:users',
        'confirm_password'  => 'required|min:8|same:password'
    );
    $validator = \Validator::make($posts, $rules);

    if ($validator->fails()) {
        return response()->json( $validator->messages()  );
    } else {

        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('confirm_password'));
        $user->referred_by = $request->input('referred_by');
        $user->reseller_code_used = $request->input('reseller_code');
        $user->user_type = 'applicant';
        $user->approval = 1;
        $user->save();
        $user->assignRole('applicant');
              
        $profile = new Profile;
        $profile->user_id = $user->id;
        $applicant_id = sprintf('%08d',$user->id);
        $profile->application_number = $applicant_id;

        Helper::api_save_posts($posts, $user->id);
        
        $profile->save();

        $notification = new Notification;
        $notification->from_user_id = $user->id;
        $notification->module = 'register';
        $notification->messages = 'New registered applicant! ['. $request->input('email').']';
        $notification->save();

        // Insert Refer Friend
        // $request->input('reseller_code');
        if($request->input('reseller_code')) {
            $refer = new UserReferred;
            $refer->email = $request->input('email');

            $refer_user = User::where('reseller_code', $request->input('reseller_code'))->first();
            
            if($refer_user) {
                $refered_user_id = $refer_user->id;
            } else {
                $refer_user = $request->input('referred_by');
            }

            $refer->user_id = ($refer_user) ? $refer_user->id : '0';
            $refer->first_name = $request->input('first_name');
            $refer->middle_name = $request->input('middle_name');
            $refer->last_name = $request->input('last_name');
            $refer->mobile_number = $request->input('text_fi_483');
            $refer->reseller_code_used = $request->input('reseller_code');
            $refer->status = 0;
            $refer->save();
        }

        $message = array(
            'success' => 'Account registered!',
            'alert' => 'success'
        );
        return response()->json($message);
    }

}]);

Route::post('user/{id}/changepassword', ['middleware' => ['cors', 'apitoken'], function(Request $request, $id) {
    $user_id = Crypt::decryptString($id);
    $post = $request->all();
  
    $rules = array(
        'old_password'     => 'required',
        'new_password'     => 'required:different:old_password',
        'confirm_password' => 'required|min:8|same:new_password'
    );
    $validator = \Validator::make($post, $rules);

    if ($validator->fails()) {

        return response()->json($validator->messages());
 
    } else {
        $user = User::find($user_id);
        if (Hash::check($request->input('old_password'), $user->password)){
            $user->password = Hash::make($request->input('confirm_password'));
            $user->update();
            $message = array(
                'success' => 'Successfully Updated!'
            );
            return response()->json($message);
        } else {
            return response()->json(['old_password' => 'The specified old password does not match the database password!.']);
        }
    }

}]);

Route::get('user/{id}', ['middleware'=>['cors','apitoken'], function (Request $request, $id) {
    try {
        $user_id = Crypt::decryptString($id);
        $user = User::find($user_id);
        $details = array(
            'alert' => 'success',
            'user' => $user,
            'profile' => $user->profile,
        ); 
    } catch (Exception $e) {
        $details = array(
            'alert' => 'error',
            'user' => [],
            'profile' => [],
        ); 
    }
   return response()->json($details);
}]);

Route::get('user/{id}/forms', ['middleware'=>['cors','apitoken'], function (Request $request, $id) {
    $user_id = Crypt::decryptString($id);
    $user = User::find($user_id);
    
    $form_groups = [];
    if($user->profile->forms) {
        $user_form_groups = explode(',', $user->profile->forms);
        foreach($user_form_groups as $item) {
            $form_groups_model = FormGroup::find($item);
            $form_groups[] = array(
                'form_group' => $form_groups_model,
                'form_inputs' => $form_groups_model->inputs_w_value($user_id)
            );
        }
    }
    $details = array(
        'form_groups' => $form_groups,
    );
   return response()->json($details);
}]);

Route::get('user/{id}/applicationstatus', ['middleware'=>['cors','apitoken'], function (Request $request, $id) {
    $user_id = Crypt::decryptString($id);
    $user = User::find($user_id);
    $form_groups = [];
    if($user->profile->application_status) {
        $user_application_status = explode(',', $user->profile->application_status);
        foreach($user_application_status as $item) {
            $form_groups_model = FormGroup::find($item);
            $form_groups[] = array(
                'application_status_group' => $form_groups_model,
                'application_status_inputs' => $form_groups_model->inputs_w_value($user_id),
                'application_status_message' => $form_groups_model->application_status_message($user_id)
            );
        }
    }
    $details = array(
        'application_status' => $form_groups,
    );
   return response()->json($details);
}]);

Route::get('user/{id}/applicant-profile-form', ['middleware'=>['cors','apitoken'], function (Request $request, $id) {
    $user_id = Crypt::decryptString($id);
    $user = User::find($user_id);
    
    $form_groups_model = FormGroup::find(13);
    $form_groups = array(
        'applicant_form' => $form_groups_model,
        'applicant_form_input' => $form_groups_model->inputs_w_value($user_id),
    );
    return response()->json($form_groups);
}]);

Route::post('user/{id}/saveform', ['middleware' => ['cors', 'apitoken'], function (Request $request, $id) {
    $user_id = Crypt::decryptString($id);
    $posts = $request->all();
    unset($posts['submit']);
    unset($posts['files']);


    Helper::api_save_posts($posts, $user_id);
    $user = User::find($user_id);
    $profile = Profile::where('user_id', $user_id)->first();
    $profile->forms_lock = $profile->forms_lock.','.$posts['form_group_id'];
    $profile->update();

    $other['form'] = $request->input('form_group_name');
    Helper::mail_formatter(9, $user, $other);
    
    if($posts) {
        $notification = new Notification;
        $notification->from_user_id = $user_id;
        $notification->module = 'forms';
        $notification->url = '/applicants/'.$user_id.'/edit';
        $notification->messages = 'Update form!';
        $notification->save();
    }
    $message = array('message' => 'Successfully Updated');
    return response()->json($message);
}]);

Route::post('user/{id}/updateprofile', ['middleware' => ['cors', 'apitoken'], function (Request $request, $id) {
    $user_id = Crypt::decryptString($id);
    $posts = $request->all();
    unset($posts['submit']);
    unset($posts['files']);
    $user = Profile::where('user_id', $user_id)->first();
    $image = $user->image;

    $u = User::find($user_id);
    $u->update($posts);
        
    if(isset($posts['image'])) {
        $tmpFilePath = $posts['image']['tmp_name'];
        if ($tmpFilePath != ""){
            $info = pathinfo($posts['image']['name']);
            $base_name =  basename($posts['image']['name'],'.'.$info['extension']);
            $ext = pathinfo($posts['image']['name'], PATHINFO_EXTENSION);
            $fileName = $base_name .'_nurse_'. time() . '.' .$ext;
            $newFilePath = public_path("documents") . "\'". $fileName;
            copy($tmpFilePath, str_replace("'", "", $newFilePath));
            $image = $fileName;
        }
    }
    
    Helper::api_save_posts($posts, $user_id);

    if($posts) {
        
        $notification = new Notification;
        $notification->from_user_id = $user_id;
        $notification->module = 'profile';
        $notification->url = '/applicants/'.$user_id.'/edit';
        $notification->messages = 'update profile.';
        $notification->save();
    }

    $user->update( array_merge($request->all(), ['image' => $image ] ) );
    $message = array('message' => 'Successfully Updated');
    return response()->json($message);
}]);

Route::get('user/{id}/{email}/getmails', ['middleware' => ['cors', 'apitoken'], function (Request $request, $id, $email) {
    $user_id = Crypt::decryptString($id);
    $user = User::find($user_id);
    $return_mails = array(
        'mails' => $user->mails
    );
    return response()->json($return_mails);
}]);

Route::get('country', ['middleware' => ['cors', 'apitoken'], function () {
    $country = Country::all();
    return response()->json($country);
}]);

Route::post('user/{id}/savetestimonial', ['middleware' => ['cors', 'apitoken'], function (Request $request, $id) {
    $user_id = Crypt::decryptString($id);
    $posts = $request->all();
    unset($posts['submit']);
    unset($posts['files']);
    $testimonials = new Testimonial;
    $attachments = '';
    if(isset($posts['attachments'])) {
        $files = $posts['attachments']['name'];
        $files_arr = [];
        for( $i=0 ; $i < count($files) ; $i++ ) {
            $tmpFilePath = $posts['attachments']['tmp_name'][$i];
            if ($tmpFilePath != ""){
            $info = pathinfo($posts['attachments']['name'][$i]);
            $base_name =  basename($posts['attachments']['name'][$i],'.'.$info['extension']);
            $ext = pathinfo($posts['attachments']['name'][$i], PATHINFO_EXTENSION);
            $fileName = $base_name .'_nurse_'. time() . '.' .$ext;
            $newFilePath = public_path("documents") . "\'". $fileName;
            copy($tmpFilePath, str_replace("'", "", $newFilePath));
            $files_arr[] = $fileName;
            }
        } 
        $attachments = implode(',', $files_arr); 
    }

    $testimonials->create( array_merge($request->all(), [
        'attachments' => $attachments,
        'user_id' => $user_id,
        'status' => 1
    ] ) );
    $message = array('message' => 'Successfully Submitted!');
    return response()->json($message);
}]);
 
Route::get('getpaymenthistory/{email}', ['middleware' => ['cors', 'apitoken'], function ($email) {

    $user = User::where('email', $email)->first();
    $data = [];
    if($user) {
        $offline = Cart::where('user_id', $user->id)->where('payment_mode', '<>', 'shopify')->orderBy('id', 'DESC')->get();
        $cart = Cart::where('user_id', $user->id)->select('order_no','status')->get();
        $config = array(
            'ShopUrl' => 'neacmed.myshopify.com',
            'ApiKey' => 'be5742c03c8b43d8fc7c493fc0be31b9',
            'Password' => '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',
        );
        $shopify = new \PHPShopify\ShopifySDK($config);
        $params = array(
            'email' => $email,
            'fields' => 'id,line_items,name,total_price,email,order_status_url,currency,financial_status,gateway,note,order_status_url,customer,created_at,presentment_currency,subtotal_price_set,total_tax_set,total_price_set'
        );
        $shopify_orders = $shopify->Order->get($params);

        $cart_arr = array();
        foreach($cart as $item) {
            $cart_arr[$item->order_no] = $item->status;
        }

        $data['offline'] = $offline;
        $data['shopify'] = $shopify_orders;
        $data['cart'] = $cart_arr;
    }
    return response()->json($data);
}]);

Route::get('getcurrency', ['middleware' => ['cors', 'apitoken'], function () {
    $currency = Currency::find(1);
    return response()->json($currency);
}]);

Route::get('getreseller', ['middleware' => ['cors', 'apitoken'], function () {
    $reseller = User::whereNotNull('reseller_code')->select('id', 'first_name', 'last_name', 'email')->orderBy('first_name', 'ASC')->get();
    return response()->json($reseller);
}]);

Route::post('refer/{id}', ['middleware' => ['cors', 'apitoken'], function (Request $request, $id) {

    $user_id = Crypt::decryptString($id);
    $check_referred = UserReferred::where('user_id', $user_id)->where('email', $request->input('email'))->get();
    if(count($check_referred)) {
        $message = array(
            'message' => 'Email already exist!',
            'alert' => 'warning'
        );
    } else {
        $refer = new UserReferred;
        $refer->create(
            array_merge($request->all(),[
                'user_id' => $user_id,
                'reseller_code_used' => User::find($user_id)->reseller_code
            ])
        );
        $message = array(
            'message' => 'Successfully Referred!',
            'alert' => 'success',
            'code' => User::find($user_id)->reseller_code,
        );
    }
    return response()->json($message);

}]);

Route::get('getrefer/{id}', ['middleware' => ['cors', 'apitoken'], function (Request $request, $id) {
    $user_id = Crypt::decryptString($id);
    $user = User::find($user_id);
    
    $completed_count = 0;
    $total_earn = 0;
    $total_deposit = 0;
    $total_balance = 0;
    $paid_count = 0;

    $check_referred_temp = UserReferred::where('user_id', $user->id)->orderBy('id', 'DESC');
    $registered_reffered_temp = User::where('users.reseller_code_used', $user->reseller_code)
                                ->leftJoin('users_referred', 'users.email', '=', 'users_referred.email')
                                ->leftJoin('carts', 'users.id', '=', 'carts.user_id')
                                ->leftJoin('posts', function($join) {
                                    $join->on('posts.user_id', '=', 'users.id')
                                    ->where('posts.input_id', '=', 483);
                                })
                                ->where('users_referred.user_id', $user->id)
                                ->select('users_referred.*','carts.status as cart_status', 'posts.post as mobile_number')
                                ->orderBy('users.id', 'DESC')
                                ->groupBy('users.id');
                            
    $check_referred =  $check_referred_temp->get();
    $registered_reffered = $registered_reffered_temp->get();

    $completed_count = $registered_reffered_temp->whereNotNull('carts.status')->get()->count();
    //$paid_count = $registered_reffered_temp->where('users_referred.status', 1)->get()->count();
    $paid_count = UserReferred::where('user_id', $user->id)->where('status',1)->orderBy('id', 'DESC')->get()->count();

    $total_earn = $completed_count * $user->reseller_prize;
    $total_deposit = $paid_count  * $user->reseller_prize;
    $total_balance = $total_earn - $total_deposit;

    $return = array(
        'reffered' => $check_referred,
        'registered_referred' => $registered_reffered, 

        'completed' => $completed_count,
        'total_earn' => $total_earn,
        'total_deposit' => $total_deposit,
        'total_balance' => $total_balance            
    );
    return response()->json($return);
}]);



Route::get('/servicecategories', ['middleware' => ['cors', 'apitoken'], function(){
    $service_category = ServiceCategory::all();
    $return = [];
    foreach($service_category as $service_cat) {
        $services = Service::where('category_id', $service_cat->category_id)->where('status', 1);
        if(!$services->limit(1)->get()->isEmpty()) {
            $return[] = array(  
                'service_category' => $service_cat,
                'services' => $services->limit(5)->get()
            );
        }
    }
    return response()->json($return);
}]);

Route::get('servicecategories', ['middleware' => ['cors', 'apitoken'], function(){
    $service_category = ServiceCategory::all();
    $return = [];
    foreach($service_category as $service_cat) {
        $services = Service::where('category_id', $service_cat->category_id)->where('status', 1);
        if(!$services->limit(1)->get()->isEmpty()) {
            $return[] = array(  
                'service_category' => $service_cat,
                'services' => $services->limit(5)->get()
            );
        }
    }
    return response()->json($return);
}]);

Route::get('servicecategory/{id}', ['middleware' => ['cors', 'apitoken'], function($id){
    $new_id = Helper::secure_enc($id, 'd');
    $service_category = ServiceCategory::find($new_id);
    $services = Service::where('category_id', $service_category->category_id)->where('status', 1)->get();

    $states_arr = [];
    $states = [];
    $type_arr = [];
    foreach($services as $service) {
        if($service->state) {
            $states_arr[] = $service->state;
        }
        if(!in_array($service->type, $type_arr)) {
            $type_arr[] = $service->type;
        }
    }
    if(count($states_arr) > 0) {
        $states_temp = implode("\r\n", $states_arr);
        $states = explode("\r\n", $states_temp);
    }

    $return = array(  
        'service_category' => $service_category,
        'services' => $services,
        'states' => $states,
        'type' => $type_arr
    );
    return response()->json($return);
}]);

Route::post('cartitemsession', ['middleware' => ['cors', 'apitoken'], function(Request $request){
    // return response()->json($request->all());
    $cart_item = $request->all();
    $services = Service::whereIn('id', $cart_item)->get();
    return response()->json($services);
}]);



Route::post('processtransaction/{id}', ['middleware' => ['cors', 'apitoken'], function (Request $request, $id) {
    $all = $request->all();
    $post = $all['info'];
    $cart_item = $all['cart_item'];
    $test = [];

    
    $currency = Currency::find(1);
    $services = [];
    if($id == '0') {
        $services = Service::whereIn('id', $cart_item)->get();
    } else {
        $user_id = Crypt::decryptString($id);
        $userx = User::find($user_id);
        if($userx->cart) {
            foreach($userx->cart->services as $service) {
                $services[] = Service::find($service->service_id);
            }
        }
    }

    $total_temp = 0;
    $total = 0;
    $code = 'USD';

    foreach($services as $item) {
        $total_temp = $total_temp + $item->price;
    }

    if($post['payment_mode'] == 'dragonpay' || $post['payment_mode'] == 'paymaya') {
        $total_x = $total_temp * ($currency->value + $currency->additional);
        $total = round( (($total_x * $currency->vat) + $total_x)  ,2);
        $code = 'PHP';
    } else {
        $total = round( (($total_temp * $currency->vat ) + $total_temp), 2);
    }

    $receipt = [];
    if($post['payment_mode'] == 'paypal') {
        $receipt['paymentId'] = $post['paymentId']; 
        $receipt['PayerID'] = $post['PayerID']; 
    } else if ($post['payment_mode'] == 'paymaya') {
        $receipt['paymentId'] = $post['refno']; 
    }

    
    if($id == '0') { // No user Insert Data
        $user = User::where('email', urldecode($post['email']))->first();
        if($user) {
            $cart = new Cart;
            $max_id = Cart::max('id'); 
            $cart = new Cart;
            $order_no = sprintf('%05d',$max_id + 1);
            $cart->user_id = $user->id;
            $cart->order_no = $order_no;
            $cart->status = 1;
            $cart->payment_mode = $post['payment_mode'];
            $cart->currency = $code;
            $cart->total = $total;
            $cart->receipt = json_encode($receipt);
            $cart->current_currency = $currency->value .'::' . $currency->additional;
            $cart->payed_at = date('Y-m-d H:i:s');
            $cart->save();
            

        
            $notification = new Notification;
            $notification->from_user_id = $user->id;
            $notification->module = 'purchase';
            $notification->messages = 'Purchased!' ;
            $notification->save();

            $message = array(
                'message' => 'Transaction is completed!',
                'alert'  => 'success',
                'status' => 1,
                'account' => 'old',
                'user_id' => Crypt::encryptString($user->id),
                'order_no' => $order_no,
                'type' => 'no_session_but_existing_accnt'
            );

        } else {

            $password = Helper::str_random(8);
            $user = new User;
            $user->first_name = urldecode($post['first_name']);
            $user->middle_name = urldecode($post['middle_name']);
            $user->last_name = urldecode($post['last_name']);
            $user->email = urldecode($post['email']);
            $user->password = Hash::make($password);
            $user->approval = 1;
            $user->user_type = 'applicant';
            $user->save();
            $user->assignRole('applicant');
                
            $profile = new Profile;
            $profile->user_id = $user->id;
            $applicant_id = sprintf('%08d',$user->id);
            $profile->application_number = $applicant_id;
            $profile->mobile_number = urldecode($post['mobile_number']);
            $profile->home_address = urldecode($post['home_address']);
            $profile->save();

    
            $max_id = Cart::max('id'); 
            $cart = new Cart;
            $order_no = sprintf('%05d',$max_id + 1);
            $cart->user_id = $user->id;
            $cart->order_no = $order_no;
            $cart->status = 1;
            $cart->payment_mode = $post['payment_mode'];
            $cart->currency = $code;
            $cart->total = $total;
            $cart->receipt = json_encode($receipt);
            $cart->current_currency = $currency->value .'::' . $currency->additional;
            $cart->payed_at = date('Y-m-d H:i:s');
            $cart->save();
            

        
            $notification = new Notification;
            $notification->from_user_id = $user->id;
            $notification->module = 'register-checkout';
            $notification->messages = 'New registered applicant with purchase! ['. urldecode($post['email']).']';
            $notification->token = $password;
            $notification->save();

            $message = array(
                'message' => 'Transaction is completed!',
                'alert'  => 'success',
                'status' => 1,
                'account' => 'new',
                'user_id' => Crypt::encryptString($user->id),
                'order_no' => $order_no,
                'type' => 'totally_new_user'
            );
        }

    } else { // Has user Update existing

        $user_id = Crypt::decryptString($id);
    
        $cart = Cart::where('user_id', $user_id)->where('status', 0)->first();
        if($cart) {
            $cart->status = 1;
            $cart->payment_mode = $post['payment_mode'];
            $cart->currency = $code;
            $cart->total = $total;
            $cart->receipt = json_encode($receipt);
            $cart->current_currency = $currency->value .'::' . $currency->additional;
            $cart->payed_at = date('Y-m-d H:i:s');
            $cart->update();

            $notification = new Notification;
            $notification->from_user_id = $user_id;
            $notification->module = 'purchase';
            $notification->messages =  'Purchased!' ;
            $notification->save();

            $message = array(
                'message' => 'Transaction is completed!',
                'alert'  => 'success',
                'status' => 1,
                'account' => 'old',
                'user_id' => $id,
                'order_no' => $cart->order_no,
                'type' => 'login_user'
            );
        } else {
            $cart = Cart::where('user_id', $user_id)->where('status', 1)->orderBy('id', 'DESC')->first();
            $message = array(
                'message' => 'Transaction is already completed!',
                'alert'  => 'success',
                'status' => 1,
                'account' => 'old',
                'user_id' => $id,
                'order_no' => $cart->order_no,
                'type' => 'login_user'
            );
        }

    }
    return response()->json($message);
}]);