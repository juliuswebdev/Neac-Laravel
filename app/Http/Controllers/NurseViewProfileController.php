<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Profile;
use App\SecurityQuestion;
use App\Country;
use App\FormGroup;
use App\Post;
use DB;
use Auth;
use Session;

class NurseViewProfileController extends Controller
{
    // Profile View for Nurse Profile
    public function view()
    {
        $form_group = FormGroup::all();
        $nurses  = User::where('user_type','nurse')
                        ->orderBy('id','DESC')
                        ->get();
        return view('admin.data')->with([
            'nurses' => $nurses,
            'form_group' => $form_group
        ]);
    }


    public function show(User $user,$id)
    {
        $form_group = FormGroup::all();
        $nurses  = User::where('user_type','nurse')
                        ->orderBy('id','DESC')
                        ->get();
        return view('admin.data')->with([
            'nurses' => $nurses,
            'form_group' => $form_group
        ]);
    }
}
