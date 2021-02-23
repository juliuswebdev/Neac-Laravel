<?php

namespace App\Http\Controllers;

use App\Testimonial;
use App\User;
use Illuminate\Http\Request;


class TestimonialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:testimonial-list');
    }

    public function index()
    {
        //
        $users = User::select('first_name', 'last_name', 'id', 'email')->where('user_type', 'applicant')->where('status', 1)->where('approval', 1)->get();
        $testimonials = Testimonial::orderBy('id', 'DESC')->get();
        return view('testimonials.index')->with([
            'testimonials'=> $testimonials,
            'users' => $users
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
 
        $posts = $request->all();
        unset($posts['submit']);
        unset($posts['files']);
        
        $testimonials = new Testimonial;

        $image = '';
        if ($request->hasFile('image')) {
            $file1 = $request->file('image');
            $destinationPath1 = 'documents/'; // upload path
            $name1 = pathinfo($file1->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName1 = $name1 ."_nurse_". time() . "." . $file1->getClientOriginalExtension();
            $file1->move($destinationPath1, $fileName1);
            $image = $fileName1;
        }

        $applicant_image = '';
        if ($request->hasFile('applicant_image')) {
            $file2 = $request->file('applicant_image');
            $destinationPath2 = 'documents/'; // upload path
            $name2 = pathinfo($file2->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName2 = $name2 ."_nurse_". time() . "." . $file2->getClientOriginalExtension();
            $file2->move($destinationPath2, $fileName2);
            $applicant_image = $fileName2;
        }
    
        $testimonials->create( array_merge($request->all(), [
            'image' => $image,
            'applicant_image' => $applicant_image,
            'status' => 1
        ]));
       
        return redirect()->back();

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
        $testimonial = Testimonial::find($id);
        return view('testimonials.show')->with('testimonial', $testimonial);
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
        //
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
        $testimonial = Testimonial::find($id);
        $testimonial->delete();
        return redirect()->back();
    }

    public function deactivate($id)
    {
        //
        $testimonial = Testimonial::find($id);
        $testimonial->status = 0;
        $testimonial->update();
        return redirect()->back();
    }
    public function activate($id)
    {
        //
        $testimonial = Testimonial::find($id);
        $testimonial->status = 1;
        $testimonial->update();
        return redirect()->back();
    }
}
