<?php

namespace App\Http\Controllers;

use App\User;
use App\Employee;
use App\SecurityQuestion;
use App\Country;
use App\FormGroup;
use App\Post;
use App\Mails;
use Auth;
use Session;
use DB;
use Mail;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class EmployeeManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:employee-list');
        $this->middleware('permission:employee-add',  ['only' => ['create','store']]);
        $this->middleware('permission:employee-show', ['only' => ['show']]);
        $this->middleware('permission:employee-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:employee-delete', ['only' => ['destroy', 'activate']]);
    }


    public function index()
    {
        $q = (isset($_GET['q'])) ? trim($_GET['q']) : '';

        $query = User::whereNotIn('user_type', ['applicant', 'business'])
                ->leftJoin('employees', 'users.id', '=', 'employees.user_id');

            if($q) {
                $query->where('users.email', 'like', '%' . $q . '%')
                ->orWhere('users.first_name', 'like', '%' . $q . '%')
                ->orWhere('users.middle_name', 'like', '%' . $q . '%')
                ->orWhere('users.last_name', 'like', '%' . $q . '%')
                ->orWhere('employees.employee_number', 'like', '%' . $q . '%');
            }

        $employees = $query->orderBy('users.id','desc')
        ->where('users.id', '<>', auth()->user()->id)
        ->select('users.*', 'employees.employee_number')
        ->paginate(100);

        return view('employees.index')->with('employees', $employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name', '<>', 'applicant')->get();
        return view('auth.employee-register')->with('roles', $roles);  
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
            $user->user_type = $request->input('role');
            $user->approval = 1;
            $user->save();

            $user->assignRole($data['role']);
            $employee_profile = new Employee();

            $applicant_id = date('Y').'-'.sprintf('%04d',$user->id);
            $employee_profile->employee_number = $applicant_id;
            $employee_profile->user_id = $user->id;
            $employee_profile->save();


            $other['password'] = $password;
            Helper::mail_formatter(5, $user, $other);

            return redirect()->route('employees.create')->with('success','New employee registered! Authentication is sent via Email!');
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, $id)
    {
        //
        $user = User::find($id);
        $security_questions = SecurityQuestion::all();
        $countries = Country::all();
        $roles = Role::all();
        $posts = Post::where('user_id', $id)->get();
        return view('employees.show')->with([
            'security_questions' => $security_questions,
            'countries' => $countries,
            'user' => $user,
            'roles' => $roles,
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
        $security_questions = SecurityQuestion::all();
        $countries = Country::all();
        $roles = Role::all();
        return view('employees.edit')->with([
            'security_questions' => $security_questions,
            'countries' => $countries,
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user,$id)
    {

        $user = User::find($id);


        $employee = Employee::where('user_id', $id)->first();
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

        $user->update( array_merge($request->all(), ['user_type' => $request->input('role')] ));

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('role'));

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

        Employee::where('user_id', $id)->delete();
        Post::where('user_id', $id)->delete();
        Mails::where('user_id', $id)->delete();
        DB::table('model_has_roles')->where('model_id', $id)->where('model_type','App\User')->delete();

        return redirect()->back();
    }


    public function resetpassword(Request $request, $id) {

        $user = User::find($id);
        $password = $request->input('password');

        if( strlen($password) >= 8 ) {
            $user->password = Hash::make($password);
            $user->update();

            $other['password'] = $password;
            Helper::mail_formatter(6, $user, $other);

            $message = array('message' => 'Successfully Changed! New password ['.$password.']', 'alert' => 'success');
        } else {
            $message = array('message' => 'The password must be at least 8 characters.', 'alert' => 'warning');
        }
        return response()->json($message);
        
    }


}
