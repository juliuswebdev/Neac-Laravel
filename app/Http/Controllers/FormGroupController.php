<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormGroup;
use App\Profile;
use App\User;
use Helper;

class FormGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:forms-list');
        $this->middleware('permission:forms-add',  ['only' => ['create','store']]);
        $this->middleware('permission:forms-show', ['only' => ['show']]);
        $this->middleware('permission:forms-delete', ['only' => ['destroy']]);
    }



    public function index()
    {
        //
        $forms = FormGroup::where('status', 1)->where('type', 0)->get();
        return view('settings.formgroup.index')->with('forms', $forms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('settings.formgroup.create');
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
        $form_group = new FormGroup;
        $form_group->name = $request->input('name');
        $form_group->description = $request->input('description');
        $form_group->type = 0;
        $form_group->save();

        return redirect()->route('forms.index');
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
        $forms = FormGroup::where('id',$id)->where('type', 0)->firstOrFail();
        return view('settings.formgroup.show')->with('forms', $forms);
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
        $forms = FormGroup::where('id',$id)->where('type', 0)->firstOrFail();
        return view('settings.formgroup.edit')->with('forms', $forms);
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
        $form_group = FormGroup::find($id);
        $form_group->name = $request->input('name');
        $form_group->description = $request->input('description');
        $form_group->update();
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
        $form_group = FormGroup::find($id);
        $form_group->status = 0;
        $form_group->update();
        return redirect()->back();
    }

    public function save_user_form_group(Request $request) {

        $user = User::find($request->input('user_id'));
        $f = [];
        if($request->input('form_group')) {
            $user->profile->forms = implode(',', $request->input('form_group'));
            $forms = FormGroup::whereIn('id', $request->input('form_group'))->get();
            foreach($forms as $item) {
                $f[] = $item->name;
            }
        } else {
            $user->profile->forms = null;
        }
        $user->profile->update();

        $others['form'] = implode(', ', $f);
        // print_r($others);
        Helper::mail_formatter(10, $user, $others);
        return redirect()->back();

    }

}
