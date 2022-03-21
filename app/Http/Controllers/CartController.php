<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }

    private function itemInCart($pid,$quantity=1){
        $cart = new Cart;
        $cart->user_id = Auth::user()->id;;
        $cart->product_id = $pid;
        $cart->quantity = $quantity;
        $cart->save();
        return $cart;
    }

    public function add_to_cart(Request $request) {
        $product_id = $request->pid;
        $user_id = Auth::user()->id;
        if($this->checkCart($product_id)){
            return response($this->checkCart($product_id), 201);
        }else {
            return response($this->itemInCart($product_id), 200);
        }
    }

    // Check Cart For login user
    public function checkCart($product_id){
        $user_id = Auth::user()->id;
        $check = Cart::where('product_id', $product_id)->where('user_id', $user_id)->first();
        if ($check) {
            return $check;
        }else{
            return false;
        }
    }

    public function get_guest_cart(Request $request) {
        $items = $request->items;
        $carts = [];
        foreach ($items as $item) {
            $product = Product::where('id', $item['id'])->first();
            $product->quantity = $item['quantity'];
            array_push($carts, $product);
        }
        return response($carts, 200);
    }

    public function get_user_cart() {
        $user_id = Auth::user()->id;
        $products = DB::table('products')
            ->join('carts', 'products.id', '=', 'carts.product_id')
            ->select('products.*', 'carts.quantity')
            ->where('carts.user_id', $user_id)
            ->get();

        return response($products, 200);
    }


    public function addItem_user_cart(Request $request) {
        $items = $request->items;
        $carts = [];
        foreach ($items as $item) {
            $checkcart = $this->checkCart($item['id']);
            if(!$checkcart){
                $cart = $this->itemInCart($item['id'],$item['quantity']);
                $product = [ 'id'=> $item['id'] , 'quantity' =>$item['quantity']];
                array_push($carts, $product );
            }else{
                array_push($carts, ['id'=> $checkcart->product_id, 'quantity'=>$checkcart->quantity] );
            };
            
            
        }
        return response($carts, 200);
    }


    public function update_qty(Request $request) {
        $product_id = $request->id;
        $user_id = Auth::user()->id;
        $qty = $request->quantity;
        $cart = Cart::where("product_id", $product_id)->where("user_id", $user_id)->first();
        if ($cart) {
            if ($qty > 0) {
                $cart->quantity = $qty;
                $cart->save();
            }
        }
        return response($product_id, 200);
    }

    public function removeCartItem(Request $request){
        $product_id = $request->product_id;
        $user_id = Auth::user()->id;
        Cart::where('product_id', $product_id)->where('user_id', $user_id)->delete();
        return response("Success", 200);
    }

}
