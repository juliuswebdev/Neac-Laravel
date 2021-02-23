<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceCategory;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        // $this->middleware('permission:service-category-list');
        // $this->middleware('permission:service-category-add',  ['only' => ['create','store']]);
        // $this->middleware('permission:service-category-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:service-category-destroy', ['only' => ['destroy']]);
    }

    public function index()
    {
        //
        
        $service_category = ServiceCategory::orderby('id', 'asc')->get();
        return view('settings.servicecategory.index')->with('service_category',$service_category);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('settings.servicecategory.create');
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
        $max_id = ServiceCategory::max('id');
        $service_category = new ServiceCategory;
        $service_category->category_id = $max_id + 11;

        $image = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $destinationPath = 'documents/'; // upload path
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $image = $fileName;
        }
    
        $logo = '';
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $destinationPath = 'documents/'; // upload path
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $logo = $fileName;
        }

        $service_category->image = $image;
        $service_category->logo = $logo;
        $service_category->name = $request->input('name');
        $service_category->description = $request->input('description');
        $service_category->save();
        return redirect()->back()->with(['message' => 'Successfully Saved!']);

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
        $service_category = ServiceCategory::find($id);
        return view('settings.servicecategory.edit')->with('service_category',$service_category);
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
        $service_category = ServiceCategory::find($id);
        // $service_category->name = $request->input('name');
        // $service_category->description = $request->input('description');
        // $service_category->update();
        $image = $service_category->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $destinationPath = 'documents/'; // upload path
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $image = $fileName;
        }
        $logo = $service_category->logo;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $destinationPath = 'documents/'; // upload path
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $name ."_nurse_". time() . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $logo = $fileName;
        }
        $service_category->update( array_merge($request->all(), ['image' => $image, 'logo' => $logo] ) );
        return redirect()->back()->with(['message' => 'Successfully updated!']);
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
        $service_category = ServiceCategory::find($id);
        $service_category->delete();
        return redirect()->route('service-category.index');
    }
}
