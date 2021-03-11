<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormInput;

class FormInputController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        //
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
        $type = $request->input('type');
        $settings = $request->input('settings');
        if($type == 'select' || $type == 'radio' || $type == 'checkbox') {
            $settings = str_replace(array("\r\n", "\r", "\n"), ':::', $request->input('settings'));
        } else if($type == 'number') {
            $settings = $request->input('minimum').':::'.$request->input('maximum');
        } else if($type == 'multiple_file' || $type == 'multiple_image' || $type == 'html') {
            $settings = $request->input('settings');
        }
        
        FormInput::create(array_merge($request->all(), ['settings' => $settings]));
        $message = array(
            'success' => 'Successfully Saved!'
        );
        return response()->json($message);
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
        $type = $request->input('type');
        $settings = $request->input('settings');
        if($type == 'select' || $type == 'radio' || $type == 'checkbox') {
            $settings = str_replace(array("\r\n", "\r", "\n"), ':::', $request->input('settings'));
        } else if($type == 'number') {
            $settings = $request->input('minimum').':::'.$request->input('maximum');
        } else if($type == 'multiple_file' || $type == 'multiple_image' || $type == 'html') {
            $settings = $request->input('settings');
        }

        $required = ($request->input('required') == 1) ? 1 : 0;
        $visible_applicant = ($request->input('visible_applicant') == 1) ? 1 : 0;
        $form_input = FormInput::find($id);
        $form_input->update(array_merge($request->all(), [
             'settings' => $settings,
             'required'=> $required,
             'visible_applicant' => $visible_applicant
        ]));
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
        $form_input = FormInput::find($id);
        $form_input->delete();
        return redirect()->back();
    }
}
