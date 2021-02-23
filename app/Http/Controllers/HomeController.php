<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormGroup;
use DB;
use App\User;
use App\Testimonial;

use App\Cart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $application_status = FormGroup::where('status', 1)->where('type', 1)->get();

        $query = User::where('users.user_type','applicant')
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->leftJoin('posts', 'users.id', '=', 'posts.user_id');

        $application_status_report  = User::where('users.user_type','applicant')
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
        ->leftJoin('form_input', 'posts.input_id', '=', 'form_input.id')
        ->leftJoin('form_group', function($join)  {
            $join->on('form_input.form_group_id', '=', 'form_group.id')
            ->where('form_input.id', '=', 
                DB::raw('
                (
                    SELECT p.input_id FROM posts AS p
                    LEFT JOIN form_input fi ON p.input_id = fi.id
                    WHERE p.user_id = users.id 
                    AND p.post IS NOT NULL
                    AND fi.application_status_message IS NOT NULL
                    ORDER BY p.id DESC LIMIT 1)
                '));
        })
        ->where('form_group.type', 1)

        ->whereNotNull('posts.post')
        ->select('users.*', 'form_input.application_status_message', 'form_input.id as input_id', 'form_input.label', 'posts.post')
        ->get();

        $new = [];
        foreach($application_status_report as $item){
            $new[] = $item->id;
        }
      

        $inquiry = $query->whereNotIn('users.id', $new)
        ->select('users.*', 'posts.post')
        ->orderBy('users.id','DESC')
        ->groupBy('users.id')->get();
        
        $date = \Carbon\Carbon::today()->subDays(14);
        $new_applicant =  User::where('created_at', '>=', $date)
                            ->where('user_type', 'applicant')
                            ->orderBy('created_at', 'DESC')
                            ->get();

        $employees = User::whereNotIn('user_type',  ['applicant', 'business'])->inRandomOrder()->get();
        $resellers = User::whereNotNull('reseller_code')->inRandomOrder()->get();
        $testimonials = Testimonial::where('status', 1)->orderBy('id', 'DESC')->limit(7)->get();
 
        $active_carts = Cart::where('status', 1)->count();
        $successful_transaction = Cart::where('status',2)->count();


        $config = array(
            'ShopUrl' => 'neacmed.myshopify.com',
            'ApiKey' => 'be5742c03c8b43d8fc7c493fc0be31b9',
            'Password' => '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',
        );
        
        $shopify = new \PHPShopify\ShopifySDK($config);

        $params = array(
            'limit' => '5',
            'fields' => 'title,handle,image,tags,id'
        );

        $shopify_products = $shopify->Product->get($params);
        $shopify_orders_count = $shopify->Order->count(array('fulfillment_status' => 'any'));

        return view('home')->with([
            'application_status'=> $application_status,
            'application_status_report'=> $application_status_report,
            'shopify_products' => json_decode(json_encode($shopify_products), FALSE),
            'inquiry' => $inquiry,
            'new_applicant' => $new_applicant,
            'employees' => $employees,
            'resellers' => $resellers,
            'testimonials' => $testimonials,
            'active_carts' => ($active_carts + $shopify_orders_count) - Cart::where('payment_mode', 'shopify')->count(),
            'successful_transaction' => $successful_transaction
        ]);
    }

    public function user_data_register($year) {
        $month = [];
        $current_month = intval(date('m'));
        if(date('Y') > $year) {
            $current_month = 12;
        }
        for($x = 1; $x <= $current_month; $x++) {
            $mktime = date("M", mktime(0, 0, 0, $x, 10));
            $yr_mo = $year.'-'.sprintf('%02d', $x);
             $month[$mktime] = User::where('created_at', 'LIKE', $yr_mo.'%')->where('user_type', 'applicant')->count();
        }
        return json_encode($month);
    }
}