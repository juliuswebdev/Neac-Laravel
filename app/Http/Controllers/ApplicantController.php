<?php

namespace App\Http\Controllers;

use App\User;
use App\Profile;
use App\SecurityQuestion;
use App\Country;
use App\FormGroup;
use App\Post;
use App\FormInput;
use App\Cart;
use App\Mails;
use App\UserReferred;
use Helper;
use DB;
use Auth;
use Session;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:applicant-list');
        $this->middleware('permission:applicant-add',  ['only' => ['create','store']]);
        $this->middleware('permission:applicant-show', ['only' => ['show']]);
        $this->middleware('permission:applicant-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:applicant-delete', ['only' => ['destroy', 'activate']]);
        $this->middleware('permission:applicant-list-locked', ['only' => ['lockedlist', 'lockedlistunlock']]);
    }
    
    public function index()
    {
        $form_group = FormGroup::all();
        
        $q = (isset($_GET['q'])) ? trim($_GET['q']) : '';
        $ref = (isset($_GET['ref'])) ? trim($_GET['ref']) : '';
        $ref_val = (isset($_GET['ref_val'])) ? trim($_GET['ref_val']) : '';
        $status = (isset($_GET['application_status'])) ? trim($_GET['application_status']) : '';
        
        $post_dropdown = FormInput::where('form_group_id', 13)->select('id','label')->get();

        $application_status = FormGroup::where('status', 1)->where('type', 1)->get();

        $query  = User::where('users.user_type','applicant')
                        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
                        ->leftJoin('posts', 'users.id', '=', 'posts.user_id');

                        if($q) {
                            $query->where('users.email', 'like', '%' . $q . '%')
                                ->orWhere('users.first_name', 'like', '%' . $q . '%')
                                ->orWhere('users.middle_name', 'like', '%' . $q . '%')
                                ->orWhere('users.last_name', 'like', '%' . $q . '%')
                                ->orWhere('profiles.application_number', 'like', '%' . $q . '%')
                                ->orWhere('posts.post', 'like', '%' . $q . '%');
                        }
                        
                        if($ref) {
                            if(is_numeric($ref)) {
                                $query->where('posts.input_id', '=', $ref)
                                      ->where('posts.post', 'like', '%' . $ref_val . '%');
                            } else {
                                $query->where($ref, 'like', '%' . $ref_val . '%');
                            }
                        }

                        if($status) {

                            if($status == '0-0') {
                   
                                $get  = User::where('users.user_type','applicant')
                                ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
                                ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
                                ->leftJoin('form_input', 'posts.input_id', '=', 'form_input.id')
                                ->where('form_input.id', '=', 
                                        DB::raw('
                                        (
                                            SELECT p.input_id FROM posts AS p
                                            LEFT JOIN form_input fi ON p.input_id = fi.id
                                            WHERE p.user_id = users.id 
                                            OR p.post IS NULL
                                            OR fi.application_status_message IS NOT NULL
                                            ORDER BY p.id DESC LIMIT 1)
                                        ')
                                )->select('users.id')->get();
                                $new = [];
                                foreach($get as $id){
                                    $new[] = $id->id;
                                }
                                $query->whereNotIn('users.id', $new);

                            } else {
                                $status_arr = explode('-', $status);
                                $query->leftJoin('form_input', 'posts.input_id', '=', 'form_input.id')
                                ->leftJoin('form_group', function($join) use($status_arr) {
                                    $join->on('form_input.form_group_id', '=', 'form_group.id')
                                    ->where('form_input.id', '=', 
                                        DB::raw('
                                        (
                                            SELECT p.input_id FROM posts AS p
                                            LEFT JOIN form_input fi ON p.input_id = fi.id
                                            WHERE p.user_id = users.id 
                                            AND p.post IS NOT NULL
                                            AND fi.application_status_message IS NOT NULL
                                            AND fi.form_group_id = '.$status_arr[0].'
                                            ORDER BY p.id DESC LIMIT 1)
                                        '));
                                })
                                ->where('form_group.type', 1)
                                ->where('form_input.id', $status_arr[1])
                                ->whereNotNull('posts.post');
                            }
                        }



        $nurses = $query->orderBy('users.id','DESC')
            ->select('users.*', 'profiles.application_number')
            ->groupBy('users.id')
            ->paginate(100);


        return view('applicants.index')->with([
            'application_status_dropdown' => $application_status,
            'post_dropdown' => $post_dropdown,
            'nurses' => $nurses,
            'form_group' => $form_group
        ]);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $resellers = User::whereNotNull('reseller_code')->select('id', 'first_name', 'last_name', 'email')->orderBy('first_name', 'ASC')->get();
        $countries = Country::all();
        $applicant_profile_form = FormGroup::find(13);
    
     //
        return view('auth.applicant-register')->with([
            'countries' => $countries,
            'resellers' => $resellers,
            'applicant_profile_form' => $applicant_profile_form
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $data = $request->all();    

        $rules = array(
            'email'             => 'unique:users',
            'confirm_password'  => 'min:8|same:password'
        );
        
        $validator = \Validator::make($data, $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors( $validator->messages());
        } else {

            $user = new User();

            $user->reseller_code_used = $request->input('reseller_code');
            $user->referred_by = $request->input('referred_by');
            
            $user->first_name = $request->input('first_name');
            $user->middle_name = $request->input('middle_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('confirm_password'));
            $user->user_type = 'applicant';
            $user->approval = 1;
            $user->save();
           
            $user->assignRole('applicant');
            $applicant_id = sprintf('%08d',$user->id);

            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->application_number = $applicant_id;
            $profile->alternate_email = $request->input('alternate_email');

            $image = '';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $destinationPath = 'documents/'; // upload path
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $image = $fileName;
            }

            $profile->image = $image;
            $profile->save();

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

            // Save Post Fire Action
            foreach($data as $key => $post) {
                $temp =  array_pad(explode('_fi_', $key),2,null);
                $type = $temp[0];
                $input_id = $temp[1];
                $form_input = FormInput::find($input_id);
                if( $form_input && $type != '_method' && $type != '_token' ) {
                    $select_post =  Post::where('type', $type)
                    ->where('input_id', $input_id)
                    ->where('user_id', $user->id)->first();
                
                    if($type == 'multiple_image' || $type == 'multiple_file') {
                        $attachments = [];
                        if ($request->hasFile($key)) {
                            $files = $request->file($key);
                            foreach($files as $file) {
                                $destinationPath = 'documents/'; // upload path
                                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
                                $file->move($destinationPath, $fileName);
                                $attachments[] = $fileName;
                            }
                        }
                        $post = implode(',', $attachments);            
                    } else if($type == 'checkbox') {
                        $post = implode(',', $post);
                    }

                    if(empty($select_post)) {
                    // Insert POST
                        $post_save = new Post;
                        $post_save->user_id = $user->id;
                        $post_save->input_id = $input_id;
                        $post_save->type = $type;
                        $post_save->post = $post;
                        $post_save->save();
                    } else {
                        $post_update = Post::find($select_post->id);
                        $post_update->user_id = $user->id;
                        $post_update->input_id = $input_id;
                        $post_update->type = $type;
                        $post_update->post = $post;
                        $post_update->update();
                    }
                }
            }

            $other['password'] = $request->input('confirm_password');
            Helper::mail_formatter(1, $user, $other);
            
            $check = Profile::all();
            return redirect()->route('applicants.create')->with('success','New applicant registered! Authentication is sent via Email!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = User::find($id);

        $data = [];

        $offline = Cart::where('user_id', $user->id)->where('payment_mode', '<>', 'shopify')->orderBy('id', 'DESC')->get();
        $cart = Cart::where('user_id', $user->id)->select('order_no','status')->get();
        $config = array(
            'ShopUrl' => 'neacmed.myshopify.com',
            'ApiKey' => 'be5742c03c8b43d8fc7c493fc0be31b9',
            'Password' => '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',
        );
        $shopify = new \PHPShopify\ShopifySDK($config);
        $params = array(
            'email' => $user->email,
            'fields' => 'id,line_items,name,total_price,email,order_status_url,currency,financial_status,gateway,note,order_status_url,customer,created_at,presentment_currency,subtotal_price_set,total_tax_set,total_price_set'
        );
        $shopify_orders = $shopify->Order->get($params);

        $cart_arr = array();
        foreach($cart as $item) {
            $cart_arr[$item->order_no] = $item->status;
        }

        
        $user_application_status = explode(',', $user->profile->application_status);
        $application_status = FormGroup::whereIn('id', $user_application_status)->where('status', 1)->get();

        $user_forms = explode(',', $user->profile->forms);
        $forms = FormGroup::whereIn('id', $user_forms)->where('status', 1)->get();

        $forms_settings = FormGroup::where('status', 1)->get();
        $posts = Post::where('user_id', $id)->get();

        $applicant_profile_form = FormGroup::where('id', 13)->first();

        return view('applicants.show')->with([
            'user' => $user,
            'forms' => $forms,
            'application_status' => $application_status,
            'forms_settings' => $forms_settings,
            'applicant_profile_form' => $applicant_profile_form,
            'posts' => $posts,
            'shopify_arr' => json_encode($shopify_orders),
            'offline_arr' => json_encode($offline),
            'cart_arr' => $cart_arr,
            'fg' => '',
            'fgt' => ''
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user,$id)
    {
        $user = User::find($id);
        if($user->lock_status == 0) {
            $user->lock_status = 1;
            $user->lock_user_id = auth()->user()->id;
            $user->lock_date = date('Y-m-d');
            $user->update();
        }

        $data = [];

        $offline = Cart::where('user_id', $user->id)->where('payment_mode', '<>', 'shopify')->orderBy('id', 'DESC')->get();
        $cart = Cart::where('user_id', $user->id)->select('order_no','status')->get();
        $config = array(
            'ShopUrl' => 'neacmed.myshopify.com',
            'ApiKey' => 'be5742c03c8b43d8fc7c493fc0be31b9',
            'Password' => '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',
        );
        $shopify = new \PHPShopify\ShopifySDK($config);
        $params = array(
            'email' => $user->email,
            'fields' => 'id,line_items,name,total_price,email,order_status_url,currency,financial_status,gateway,note,order_status_url,customer,created_at,presentment_currency,subtotal_price_set,total_tax_set,total_price_set'
        );
        $shopify_orders = $shopify->Order->get($params);

        $cart_arr = array();
        foreach($cart as $item) {
            $cart_arr[$item->order_no] = $item->status;
        }

        
        $user_application_status = explode(',', $user->profile->application_status);
        $application_status = FormGroup::whereIn('id', $user_application_status)->where('status', 1)->get();

        $user_forms = explode(',', $user->profile->forms);
        $forms = FormGroup::whereIn('id', $user_forms)->where('status', 1)->get();

        $forms_settings = FormGroup::where('status', 1)->get();
        $posts = Post::where('user_id', $id)->get();

        $applicant_profile_form = FormGroup::where('id', 13)->first();

        return view('applicants.edit')->with([

            'user' => $user,
            'forms' => $forms,
            'application_status' => $application_status,
            'forms_settings' => $forms_settings,
            'applicant_profile_form' => $applicant_profile_form,
            'posts' => $posts,
            'shopify_arr' => json_encode($shopify_orders),
            'offline_arr' => json_encode($offline),
            'cart_arr' => $cart_arr,
            'fg' => '',
            'fgt' => ''
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data = $request->all();
        $reseller_code = $request->input('reseller_code_used');

        $user = Profile::where('user_id', $id)->first();

        $u = User::find($id);
        $u->update($data);

        $u_r = User::where('reseller_code', $reseller_code)->first();
        $user_referred = UserReferred::where('email', $u->email)->first();
        if($user_referred) {
            $user_referred->user_id = $u_r->id;
            $user_referred->reseller_code_used = $reseller_code;
            $user_referred->update();
        } else {
            $user_referred_u = new UserReferred;
            $user_referred_u->user_id = $u_r->id;
            $user_referred_u->reseller_code_used = $reseller_code;
            $user_referred_u->first_name = $u->first_name;
            $user_referred_u->middle_name = $u->middle_name;
            $user_referred_u->last_name = $u->last_name;
            $user_referred_u->email = $u->email;
            $user_referred_u->mobile_number = $request->input('text_fi_483');
            $user_referred_u->status = 0;
            $user_referred_u->save();
        }
      
        

        $image = $user->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $destinationPath = 'documents/'; // upload path
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $image = $fileName;
        }
        $user->update( array_merge($data, ['image' => $image] ) );

        foreach($data as $key => $post) {
            $temp =  array_pad(explode('_fi_', $key),2,null);
            $type = $temp[0];
            $input_id = $temp[1];
            $form_input = FormInput::find($input_id);
            if( $form_input && $type != '_method' && $type != '_token' ) {
                $select_post =  Post::where('type', $type)
                ->where('input_id', $input_id)
                ->where('user_id', $id)->first();
            
                if($type == 'multiple_image' || $type == 'multiple_file') {
                    $attachments = [];
                    if ($request->hasFile($key)) {
                        $files = $request->file($key);
                        foreach($files as $file) {
                            $destinationPath = 'documents/'; // upload path
                            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
                            $file->move($destinationPath, $fileName);
                            $attachments[] = $fileName;
                        }
                    }
                    $post = implode(',', $attachments);            
                } else if($type == 'checkbox') {
                    $post = implode(',', $post);
                }

                if(empty($select_post)) {
                // Insert POST
                    $post_save = new Post;
                    $post_save->user_id = $id;
                    $post_save->input_id = $input_id;
                    $post_save->type = $type;
                    $post_save->post = $post;
                    $post_save->save();
                } else {
                    $post_update = Post::find($select_post->id);
                    $post_update->user_id = $id;
                    $post_update->input_id = $input_id;
                    $post_update->type = $type;
                    $post_update->post = $post;
                    $post_update->update();
                }
            }
        }

       return redirect()->back()->with('message', 'Successfully Updated');
       
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::find($id);
        $user->delete();
        
        Profile::where('user_id', $id)->delete();
        Post::where('user_id', $id)->delete();
        Cart::where('user_id', $id)->delete();
        Mails::where('user_id', $id)->delete();
        DB::table('model_has_roles')->where('model_id', $id)->where('model_type','App\User')->delete();

        return redirect()->back();
    }



    public function post_update(Request $request, $id) {
        $posts = $request->all();
        $err = false;
        $consultant = '';
        $consultant_name = '';
        $consultant_html = '';
        foreach($posts as $key => $post) {
            $temp =  array_pad(explode('_fi_', $key),2,null);
            $type = $temp[0];
            $input_id = $temp[1];
            $form_input = FormInput::find($input_id);

            if($form_input['label'] == 'Consultant') {
                $consultant = $post;
                $consultant_name_arr = explode(':::', $form_input['settings']);
                foreach($consultant_name_arr as $arr) {
                    $select_item = array_pad(explode(' : ',$arr),2,null);
                    $value = $select_item[0];
                    $text = $select_item[1];
                    if($value == $post) {
                        $consultant_name = $text;
                    }
                }
            }

            if(auth()->user()->role()->name == $form_input['restriction'] || $form_input['restriction'] == '' || auth()->user()->user_type == 'admin') {
                if( $type != '_method' && $type != '_token' && $key != 'form_group_type' && $key != 'form_group_id' ) {
                    
                    if($form_input['label'] == 'Consultant') {
                        $consultant = $post;
                    }

                    $select_post =  Post::where('type', $type)
                    ->where('input_id', $input_id)
                    ->where('user_id', $id)->first();
                
                    if($type == 'multiple_image' || $type == 'multiple_file') {
                        $attachments = [];
                        if ($request->hasFile($key)) {
                            $files = $request->file($key);
                            foreach($files as $file) {
                                $destinationPath = 'documents/'; // upload path
                                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
                                $file->move($destinationPath, $fileName);
                                $attachments[] = $fileName;
                            }
                        }
                        $post = implode(',', $attachments);            
                    } else if($type == 'checkbox') {
                        $post = implode(',', $post);
                    }

                    if(empty($select_post)) {
                    // Insert POST
                        $post_save = new Post;
                        $post_save->user_id = $id;
                        $post_save->input_id = $input_id;
                        $post_save->type = $type;
                        $post_save->post = $post;
                        $post_save->save();
                    } else {
                        $post_update = Post::find($select_post->id);
                        $post_update->user_id = $id;
                        $post_update->input_id = $input_id;
                        $post_update->type = $type;
                        $post_update->post = $post;
                        $post_update->update();
                    }

                }
            } else {
                $err = true;
            }
        }

        $user = User::find($id);
        $application_status = FormGroup::find($posts['form_group_id']);
        if($application_status->type == 1) {

            $status = '';
            $other = [];
            if($application_status->application_status_message($id)) {
                $status = $application_status->application_status_message($id)->application_status_message;
            } else {
                $status = 'Inquiry';
            }
            if($consultant) {
                $consultant_html = '<span><strong>Consultant</strong>: '.$consultant_name.' ['.$consultant.']</span>';
            }
            $other['application_status'] = $application_status->name;
            $other['application_status_status_message'] = ' <span><strong>'.$application_status->name.'</strong>: '.$status.'</span><br>'.$consultant_html.'';
            $other['consultant'] = $consultant;
            Helper::mail_formatter(4, $user, $other);
        } elseif($application_status->type == 0) {
            $other['form'] = $application_status->name;
            Helper::mail_formatter(3, $user, $other);
        }

        if($err) {
            return redirect()->back()->with([
                'message' => "Successfully Update!<br>Some of posts not update due to restriction!",
                'fg' => $request->input('form_group_id'),
                'fgt' => $request->input('form_group_type'),
            ]);
        } else {
            return redirect()->back()->with([
                'message' => 'All posts successfully updated!',
                'fg' => $request->input('form_group_id'),
                'fgt' => $request->input('form_group_type'),
            ]);
        }
    }

    public function resetpassword(Request $request, $id) {

        $user = User::find($id);
        $password = $request->input('password');

        if( strlen($password) >= 8 ) {
            $user->password = Hash::make($password);
            $user->update();
            $other['password'] = $password;

           Helper::mail_formatter(2, $user, $other);

            $message = array('message' => 'Successfully Changed! New password ['.$password.']', 'alert' => 'success');
        } else {
            $message = array('message' => 'The password must be at least 8 characters.', 'alert' => 'warning');
        }
        return response()->json($message);
        
    }

    public function lockedlist() {
        $users = User::where('user_type','applicant')->where('lock_status', 1)->orderBy('id','DESC')->paginate(100);
        return view('applicants.lockedlist')->with(['nurses' => $users]);
    }

    public function lockedlistunlock(Request $request, $id) {
        $user = User::find($id);
        $user->lock_status = null;
        $user->lock_user_id = null;
        $user->lock_date = null;
        $user->update();
        return redirect()->back();
    }

    public function lock(Request $request, $id) {
        $user = User::find($id);
        if($user->lock_status) {
            $user->lock_status = null;
            $user->lock_user_id = null;
            $return = array('status' => 0, 'user'=> '');
        } else {
            $user->lock_status = 1;
            $user->lock_user_id = auth()->user()->id;
            $user->lock_date = date('Y-m-d');
            $return = array('status' => 1, 'user' => auth()->user()->email);
        }
        $user->update();
        return response()->json($return);
    }

    public function approve(Request $request, $id) {
        $user = User::find($id);
        $user->approval = 1;
        $user->update();
        return response()->json($user);
    }

    public function approvereactivation(Request $request, $id) {
        $user = User::find($id);
        $f_a = explode(',',$user->profile->forms_reactivate);
        $f_l = explode(',',$user->profile->forms_lock);
      
        if (($key = array_search($request->input('form_group_id'), $f_a)) !== false) {
            unset($f_a[$key]);
        }

        if (($key = array_search($request->input('form_group_id'), $f_l)) !== false) {
            unset($f_l[$key]);
        }
        $user->profile->forms_reactivate = implode(',', $f_a);
        $user->profile->forms_lock = implode(',', $f_l);
        $user->profile->update();
        return redirect()->back();
    }

}
