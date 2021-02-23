<?php

namespace App\Http\Controllers;

use App\User;
use App\Service; 
use App\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */

    public function __construct()
    {
        // $this->middleware('permission:service-list');
        // $this->middleware('permission:service-add',  ['only' => ['create','store']]);
        // $this->middleware('permission:service-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:service-disable-enable', ['only' => ['deactivate', 'activate']]);
        // $this->middleware('permission:service-destroy', ['only' => ['destroy']]);
    }
    

    public function index()
    {
        $q = (isset($_GET['q'])) ? trim($_GET['q']) : '';

        $query = Service::leftJoin('service_categories', 'services.category_id', '=', 'service_categories.category_id');

        if($q) {
            $query->where('services.name', 'like', '%' . $q . '%')
                ->orWhere('service_categories.name', 'like', '%' . $q . '%')
                ->orWhere('type', 'like', '%' . $q . '%')
                ->orWhere('price', 'like', '%' . $q . '%');
              
        } 

        $services = $query->select('services.*', 'service_categories.name as service_category_name')
        ->paginate(100);

        return view('services.index')->with('services', $services);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service_category = ServiceCategory::all();
        return view('services.create')->with('service_category',$service_category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          
        Service::create($request->all());
        return redirect()->back()->with('message','New service added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $service = Service::find($id);
        return view('services.show')->with('service', $service);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $service = Service::find($id);
        $service_category = ServiceCategory::all();
        return view('services.edit')
        ->with([
            'service' => $service,
            'service_category' => $service_category
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Service::find($id)->update($request->all());
        return redirect()->back()->with('message','Service Udded!');
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
        $service = Service::find($id);
        $service->delete();
        return redirect()->back();
    }

    public function deactivate($id)
    {
        //
        $service = Service::find($id);
        $service->status = 0;
        $service->update();
        return redirect()->back();
    }
    public function activate($id)
    {
        //
        $service = Service::find($id);
        $service->status = 1;
        $service->update();
        return redirect()->back();
    }
}
