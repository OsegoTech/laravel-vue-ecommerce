<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;

class CheckoutController extends Controller

{
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
        
        list($products, $cartItems) = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;
        
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice = $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        // 'images' => [$product->image],
                
                ],
                'unit_amount_decimal'=> $product->price * 100,
               
                
            ],
                'quantity' => $quantity,
            ];

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
            ];
        }
        // dd(route('checkout.success', [], true), route('checkout.failure', [], true));
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),

            // always create a new customer during checkout
            'customer_creation' => 'always',
          ]);

            // create Order
          $orderData = [
             'total_price' => $totalPrice,
             'status' => OrderStatus::Unpaid,
             'created_by' => $user->id,
             'updated_by' => $user->id,
          ];

            $order = Order::create($orderData);
          
            // create  Order Items
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            // create Payment

            $paymentData = [
                'order_id' => $order->id,
                
                'amount' => $totalPrice,
                'status' => PaymentStatus::Pending,
                'type' => 'cc',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'session_id' => $checkout_session->id,
            ];

            Payment::create($paymentData);
           
            
        //   dd($checkout_session->id);
          return redirect($checkout_session->url);
    }
    public function success(Request $request)
    {
         /** @var \App\Models\User $user */
         $user = $request->user();
        $stripe =new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));
         
        try {

            $session_id = $_GET['session_id'];
            $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);
            
            if(!$session){
                return view('checkout.failure', ['message' => 'Invalid session id']);
            }
            
        // fix the custumer retrieval
        // dd($session);
        
        
        $payment = Payment::query()->where(['session_id' => $session->id, 'status' => PaymentStatus::Pending])->first();
            
        if(!$payment ){
            return view('checkout.failure', ['message' => 'Payment not found']);
        }

        $payment->status = PaymentStatus::Paid;
        $payment->update();

        CartItem::where(['user_id' => $user->id])->delete();

        $order = $payment->order;
        
        // echo '<pre>';
        // var_dump($order);
        // echo '</pre>';

        $order->status = OrderStatus::Paid;
        $order->update();

        $customer = $stripe->customers->retrieve($session->customer);


        return view('checkout.success', compact('customer'));

        return view('checkout.success');
        } catch (\Exception $e) {
            throw $e;
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
        

        
    }

    public function failure(Request $request)
    {
        return view('checkout.failure', ['message' => '']);

    }

    public function checkoutOrder(Order $order, Request $request)
    {
         /** @var \App\Models\User $user */
         $user = $request->user();
        $stripe =new \Stripe\StripeClient(getenv('STRIPE_SECRET_KEY'));

        $lineItems = [];

        foreach($order->items as $item){
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                        // 'images' => [$product->image],
                
                ],
                'unit_amount_decimal'=> $item->unit_price * 100,
               
                
            ],
                'quantity' => $item->quantity,
            ];
        }
         
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),

            // always create a new customer during checkout
            'customer_creation' => 'always',
          ]);

          $order->payment->session_id = $checkout_session->id;
            $order->payment->save();

          return redirect($checkout_session->url);
    }
}
