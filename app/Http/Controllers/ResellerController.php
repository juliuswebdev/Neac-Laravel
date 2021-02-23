<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\UserReferred;
use App\User;
use App\Post;
use App\FormGroup;
use App\Profile;
use Helper;



class ResellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:reseller');
    }

    public function index()
    {
        //
        $reseller_code = (isset($_GET['code'])) ? $_GET['code'] : '';
        $q = (isset($_GET['q'])) ? trim($_GET['q']) : '';
        $user = false;
        $users = false;
        $check_referred = '';
        $registered_reffered = '';
        $completed_count = 0;
        $paid_count = 0;
        $total_earn = 0;
        $total_deposit = 0;
        $total_balance = 0;
        if($reseller_code) {
            $user = User::where('reseller_code', $reseller_code)->orWhere('email', $reseller_code)->whereNotNull('reseller_code')->first();
            if($user) {
                $check_referred_temp = UserReferred::where('user_id', $user->id)->orderBy('id', 'DESC');
                $registered_reffered_temp = User::where('users.reseller_code_used', $reseller_code)
                                        ->leftJoin('users_referred', 'users.email', '=', 'users_referred.email')
                                        ->leftJoin('carts', 'users.id', '=', 'carts.user_id')
                                        ->where('users_referred.user_id', $user->id)
                                        ->select('users_referred.*','carts.status as cart_status')
                                        ->orderBy('users.id', 'DESC')
                                        ->groupBy('users.id');
                                    
                $check_referred =  $check_referred_temp->get();
                $registered_reffered = $registered_reffered_temp->get();

                $completed_count = $registered_reffered_temp->whereNotNull('carts.status')->get()->count();
           
                $paid_count = UserReferred::where('user_id', $user->id)->where('status',1)->orderBy('id', 'DESC')->get()->count();

                $total_earn = $completed_count * $user->reseller_prize;
                $total_deposit = $paid_count  * $user->reseller_prize;
                $total_balance = $total_earn - $total_deposit;
            }
        } else {
            $query = User::whereNotNull('reseller_code');
            if($q) {
                $query->where('users.email', 'like', '%' . $q . '%')
                ->orWhere('users.reseller_code', 'like', '%' . $q . '%')
                ->orWhere('users.first_name', 'like', '%' . $q . '%')
                ->orWhere('users.middle_name', 'like', '%' . $q . '%')
                ->orWhere('users.last_name', 'like', '%' . $q . '%')
                ->orWhere('users.profession', 'like', '%' . $q . '%');
            }
            $users = $query->orderBy('id','DESC')->paginate(20);
        }
    
        return view('resellers.index')->with([
            'user' => $user,
            'users' => $users,
            'reffered' => $check_referred,
            'registered_referred' => $registered_reffered,
            'completed' => $completed_count,
            'total_earn' => $total_earn,
            'total_deposit' => $total_deposit,
            'total_balance' => $total_balance,
            'completed_count' => $completed_count,
            'paid_count' => $paid_count
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();    

        $rules = array(
            'email'             => 'unique:users',
            'password_confirmation'  => 'min:8|same:password'
        );
        
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors( $validator->messages());
        } else {   
            $user = new User();
            $password = $request->input('password_confirmation');
            $user->first_name = $request->input('first_name');
            $user->middle_name = $request->input('middle_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->password = Hash::make($password);
            $user->profession = $request->input('profession');
            $user->user_type = 'business';
            $user->approval = 1;


         
            $reseller_code = strtoupper(Helper::str_random(10));
            $find_reseller = User::where('reseller_code', $reseller_code)->get();
            if(count($find_reseller)) {
                $user->reseller_code = strtoupper(Helper::str_random(10)).'N';
            } else {
                $user->reseller_code = $reseller_code;
            }
        
            $user->save();

            $post = new Post;
            $post->user_id = $user->id;
            $post->input_id = 483;
            $post->type = 'text';
            $post->post = $request->input('phone_number');
            $post->save();

            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->application_number = $reseller_code;
            $profile->save();
            
            $other['password'] = $password;
            $other['company'] = $request->input('profession');
            $other['reseller_code'] = $reseller_code;
            Helper::mail_formatter(12, $user, $other);
            return redirect()->back()->with('success','New employee registered! Authentication is sent via Email!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        $applicant_profile_form = FormGroup::where('id', 13)->first();

        return view('resellers.edit')->with([
            'user' => $user,
            'applicant_profile_form' => $applicant_profile_form,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if($request->input('mode') == 'prize') {
            $user = User::find($id);
            $user->reseller_prize = $request->input('reseller_prize');
            $user->update(); 
        } else if ($request->input('mode') == 'status') {
            $status_arr = $request->input('status');
            foreach($status_arr as $key => $item) {
                $referred_user = UserReferred::where('id', $key)->first();
                $referred_user->status = $item;
                $referred_user->update();
            }
        }
         return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::find($id);
        $user->status = 0;
        $user->update();

        return redirect()->back();
    }
    public function activate($id)
    {
        //
        $user = User::find($id);
        $user->status = 1;
        $user->update();

        return redirect()->back();
    }

    public function resetpassword(Request $request, $id) {

        $user = User::find($id);
        $password = $request->input('password');

        if( strlen($password) >= 8 ) {
            $user->password = Hash::make($password);
            $user->update();
            $other['company'] = $user->profession;
            $other['password'] = $password;
            Helper::mail_formatter(13, $user, $other);

            $message = array('message' => 'Successfully Changed! New password ['.$password.']', 'alert' => 'success');
        } else {
            $message = array('message' => 'The password must be at least 8 characters.', 'alert' => 'warning');
        }
        return response()->json($message);
    
    }

    public function update_code(Request $request, $id) {
        $reseller_code = $request->input('reseller_code');
        $user = User::where('reseller_code', $reseller_code)->first();
        if($user) {
            $message = [
                'alert' => 'warning',
                'message' => 'Reseller Code already exist!'
            ];
        } else {
            $user_update = User::find($id);
            User::where('reseller_code_used', $user_update->reseller_code)->update(['reseller_code_used' => $reseller_code]);
            UserReferred::where('reseller_code_used', $user_update->reseller_code)->update(['reseller_code_used' => $reseller_code]);
            Profile::where('user_id', $id)->update(['application_number' => $reseller_code]);

            $user_update->reseller_code =  $reseller_code;
            $user_update->update();
            $message = [
                'alert' => 'success',
                'message' => 'Successfully Updated'
            ];
        }
        return response()->json($message);
    }

}
