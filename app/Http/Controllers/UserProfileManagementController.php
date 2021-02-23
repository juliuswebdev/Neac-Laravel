<?php

namespace App\Http\Controllers;

use App\User;
use App\Profile;
use App\Employee;
use App\SecurityQuestion;
use App\Country;
use App\FormGroup;
use Illuminate\Http\Request;  
use Spatie\Permission\Models\Role;  
use Auth;
use Hash;

class UserProfileManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $security_questions = SecurityQuestion::all();
        $countries = Country::all();
    //   $data =   implode(',',$users->get()->pluck('id')->ToArray());
        // dd($data);
        return view('profiles.create')->with(['security_questions'=>$security_questions,'countries'=>$countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = User::find(auth()->user()->id);
        $roles = Role::all();
        return view('profile.show')
        ->with([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user = User::find(auth()->user()->id);
        $roles = Role::all();
        $security_questions = SecurityQuestion::all();
        $countries = Country::all();
        return view('profile.edit')
        ->with([
            'security_questions' => $security_questions,
            'countries' => $countries,
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $user->update($request->all());
        $employee = Employee::where('user_id', $user_id)->first();
        $image = $employee->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $destinationPath = 'documents/'; // upload path
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $image = $fileName;
        }

        $employee->update( array_merge($request->all(), ['image' => $image] ) );

        return redirect()->back()->with('message', 'Successfully Updated');
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function resetpassword(Request $request, $id)
    {
        $user_id = $id;
        $post = $request->all();
      
        $rules = array(
            'old_password'     => 'required',
            'new_password'     => 'required:different:old_password',
            'confirm_password' => 'required|min:8|same:new_password'
        );
        $validator = \Validator::make($post, $rules);
    
        if ($validator->fails()) {
    
            return redirect()->back()->withErrors( $validator->messages());
     
        } else {
            $user = User::find($user_id);
            if (Hash::check($post['old_password'], $user->password)){
                $user->password = Hash::make($post['confirm_password']);
                $user->update();
                $message = array(
                    'success' => 'Successfully Updated!'
                );

            } else {
                $message = array(
                    'danger' => 'The specified old password does not match the database password!.'
                ); 
            }
            return redirect()->back()->with($message);
        }
    }

    public function update_email(Request $request, $id) {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if($user) {
            $message = array(
                'alert' => 'warning',
                'message' => 'Email is already taken!'
            );
        } else {
            $au = User::find($id);
            $au->email = $email;
            $au->update();
            $message = array(
                'alert' => 'success',
                'message' => 'Email is successfully updated!'
            );
        }
        return response()->json($message);
    }

}
