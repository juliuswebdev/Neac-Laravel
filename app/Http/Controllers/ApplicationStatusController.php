<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Helper;
use App\FormGroup;
use App\Profile;
use App\User;
use Spatie\Permission\Models\Role;

class ApplicationStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        // $this->middleware('permission:employee-list');
        // $this->middleware('permission:employee-add',  ['only' => ['create','store']]);
        // $this->middleware('permission:employee-show', ['only' => ['show']]);
        // $this->middleware('permission:employee-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:employee-delete', ['only' => ['destroy', 'activate']]);
    }


    public function index()
    {
        //
        $application_status = FormGroup::where('status', 1)->where('type', 1)->get();
        return view('settings.applicationstatus.index')
        ->with('application_status', $application_status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::where('name', '<>', 'applicant')->get();
        return view('settings.applicationstatus.create')->with('roles', $roles);
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

        $application_status = new FormGroup;
        $application_status->name = $request->input('name');
        $application_status->description = $request->input('description');
        $application_status->type = 1;
        $application_status->save();
        return redirect()->route('applications.index');
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
        $application_status = FormGroup::find($id);
        $roles = Role::where('name', '<>', 'applicant')->get();
        return view('settings.applicationstatus.show')
        ->with([
            'application_status' => $application_status,
            'roles' => $roles
        ]);
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
        $application_status = FormGroup::find($id);
        $roles = Role::where('name', '<>', 'applicant')->get();
        return view('settings.applicationstatus.edit')
        ->with([
            'application_status' => $application_status,
            'roles' => $roles
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
        $application_status = FormGroup::find($id);
        $application_status->name = $request->input('name');
        $application_status->description = $request->input('description');
        $application_status->update();
        return redirect()->back()->with('message', 'Successfully Updated!');

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
        $application_status = FormGroup::find($id);
        $application_status->status = 0;
        $application_status->update();
        return redirect()->back();
    }

    public function save_user_application_status(Request $request) {
        
        $user = User::find($request->input('user_id'));
        $as = [];
        if($request->input('form_group')) {
            $user->profile->application_status = implode(',', $request->input('form_group'));
            $application_status = FormGroup::whereIn('id', $request->input('form_group'))->get();
            foreach($application_status as $item) {
                $as[] = $item->name;
            }
        } else {
            $user->profile->application_status = null;
        }
        $user->profile->update();

        $others['application_status'] = implode(', ', $as);
        Helper::mail_formatter(11, $user, $others);
        return redirect()->back();

    }

}
