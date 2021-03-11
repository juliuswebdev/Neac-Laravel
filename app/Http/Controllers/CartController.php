<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Profile;
use App\User;

use App\CartItem;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:transactions');
    }

    public function index(Request $request)
    {
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $q = (isset($_GET['q'])) ? trim( $_GET['q']) : '';
        $s_e = (isset($_GET['s_e'])) ? trim( $_GET['s_e']) : '';
        
        
        $query = Cart::orderBy('carts.id', 'desc')->leftJoin('users', 'users.id', '=', 'carts.user_id');

     

        if( $status == '1' || $status == '0' ) {
            $query->where('carts.status', $status);
        }

        if($q) {
            $query->where('users.email', 'like', '%' . $q . '%')
            ->orWhere('users.first_name', 'like', '%' . $q . '%')
            ->orWhere('users.middle_name', 'like', '%' . $q . '%')
            ->orWhere('users.last_name', 'like', '%' . $q . '%')
            ->orWhere('carts.order_no', 'like', '%' . $q . '%');
        }
        if($s_e) {
            $s_e = explode('::', $s_e);
            $query->whereBetween('carts.payed_at', [trim($s_e[0]), trim($s_e[1])]);
        }
        
        $carts = $query->where('carts.payment_mode', '<>', 'shopify')->paginate(30);
  
        $config = array(
            'ShopUrl' => 'neacmed.myshopify.com',
            'ApiKey' => 'be5742c03c8b43d8fc7c493fc0be31b9',
            'Password' => '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',
        );
        
        $shopify = new \PHPShopify\ShopifySDK($config);

        $params = array(
            'last_id' => isset($_GET['next']) ? $_GET['next'] : null,
            'order' => 'id',
            'direction' => 'next',
            'limit' => '250',
            'fulfillment_status' => 'any',
            'fields' => 'id,line_items,name,total_price,email,order_status_url,currency,financial_status,gateway,note,order_status_url,customer,created_at,presentment_currency,subtotal_price_set,total_tax_set,total_price_set'
        );

        if (strpos($q, '#') !== false) {
           $params['name'] = $q;
        }
        
        if (strpos($q, '@') !== false) {
            $params['email'] = $q;
        }
       
        if ($s_e) {
            $params['created_at_min'] = $s_e[0];
            $params['created_at_max'] = $s_e[1];
        }


        $shopify_orders = $shopify->Order->get($params);
        $next = last($shopify_orders)['id'];
        $prev = current($shopify_orders)['id'];

        $first_temp = $shopify->Order->get(array('limit' => 1, 'fields' => 'id'));
        $last_temp =  $shopify->Order->get(array('since_id' => 0, 'limit' => 1, 'fields' => 'id'));
        $first = 0;
        $last = 0;
        if(isset($first_temp[0])) {
            $first = $first_temp[0]['id'];
        }
        if(isset($last_temp[0])) {
            $last = $last_temp[0]['id'];
        }

        $shopify_orders_count = $shopify->Order->count();


        return view('transactions.index')->with([
            'carts' => $carts,
            'shopify_orders' => json_decode(json_encode($shopify_orders), FALSE),
            'shopify_orders_count' => $shopify_orders_count,
            'next' => $next,
            'prev' => $prev,
            'first' => $first,
            'last' => $last
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
        if($id == 'sh_update') {

            $user = User::where('email', $request->input('email'))->first();

            $cart = new Cart;
            $cart->status = 2;
            $cart->user_id = ($user) ? $user->id : 0;
            $cart->order_no = $request->input('order_no');
            $cart->total = $request->input('total');
            $cart->currency = $request->input('currency');
            $cart->payed_at = $request->input('payed_at');
            $cart->payment_mode = 'shopify';
            $cart->save();
        } else {
            $cart = Cart::find($id);
            $cart->status = 2;
            $cart->save();
        }
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
        $cart = Cart::find($id);
        $cart->delete();
        return redirect()->back();
    }

    public function find_user(Request $request){

        $profile = Profile::where('application_number', $request->input('application_number'))->first();
        if($profile) {
            $user = User::find($profile->user_id);
            $return = array(
                'message' => 'User exists!',
                'alert' => 'success',
                'user' =>  $user,
                'profile' => $profile
            );
        } else {
            $return = array(
                'message' => 'User not exist!',
                'alert' => 'warning',
                'user' =>  '',
                'profile' => ''
            );
        }
        return response()->json($return);
    }

    public function place_order(Request $request) {

        $url = $request->all('data')['data'];
        $parts = parse_url($url);
        parse_str($parts['path'], $query);
        $data = $query;
        $checkout = $request->all('checkout')['checkout'];
        $max_id = Cart::max('id');
        $cart = new Cart;
        $order_no = sprintf('#OFL%05d',$max_id + 1);
        $cart->order_no = $order_no;
        $cart->user_id = $data['user_id'];
        $cart->status = 2;
        $cart->payment_mode = $data['type'];
        $cart->currency = $data['currency'];
        $cart->total = $data['total'];
        $cart->notes = str_replace(PHP_EOL,"<br>",$data['notes']);

        if(isset($checkout['lineItems'])) {
            $new_checkout_arr = array(
                'id' => $checkout['id'],
                'lineItems' => $checkout['lineItems'],
                'subtotalPrice' => $checkout['subtotalPrice'],
                'totalTax' => $checkout['totalTax'],
                'totalPrice' => $checkout['totalPrice']
            );
        } else {
            $new_checkout_arr = array(
                'id' => $checkout['id'],
                'subtotalPrice' => $checkout['subtotalPrice'],
                'totalTax' => $checkout['totalTax'],
                'totalPrice' => $checkout['totalPrice']
            );
        }


        $cart->items = json_encode($new_checkout_arr);
        $cart->payed_at = $data['payed_at'];
        $cart->save();
    }

}
