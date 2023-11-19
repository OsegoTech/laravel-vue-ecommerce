<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller

{
    public function checkout(Request $request)
    {
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
        
        list($products, $cartItems) = Cart::getProductsAndCartItems();
        $lineItems = [];
        foreach ($products as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        'images' => [$product->image],
                
                ],
                'unit_amount_decimal'=> $product->price * 100,
               
                
            ],
                'quantity' => $cartItems[$product->id]['quantity'],
            ];
        }
        // dd(route('checkout.success', [], true), route('checkout.failure', [], true));
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
            'customer_creation' => 'always',
          ]);
        //   dd($checkout_session->id);
          return redirect($checkout_session->url);
    }
    public function success(Request $request)
    {
        $stripe =new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
         
        try {
            $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);

            if(!$session){
                return view('checkout.failure');
            }
            
        // fix the custumer retrieval
        // dd($session);
        $customer = $stripe->customers->retrieve($session->customer);
        // dd($customer);

        return view('checkout.success', compact('customer'));

        return view('checkout.success');
        } catch (\Exception $e) {
            return view('checkout.failure');
        }
        

        
    }

    public function failure(Request $request)
    {
        dd($request->all());
    }
}
