<?php

namespace App\Http\Controllers;

use Exception;
use App\Payment;
use App\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function retrievePaymentRecord(Request $request)
    {   //Just in case you need to query stripe to confirm from your server
        //if a payment intent has made a successful payment or not
        $code = $request->code;
        \Stripe\Stripe::setApiKey(config('app.stripekey'));
        $event = \Stripe\PaymentIntent::retrieve($code);
    }
    //Create payment intent
    public function CreatePayIntent(Request $request)
    {
        \Log::info($request->all());
        try {
            $code = $request->code;
             $itemPrice = $request->price;
            $itemCurrency = strtolower($request->currency);
            $email = $request->email;
            \Stripe\Stripe::setApiKey(config('app.stripekey'));
            $intent = \Stripe\PaymentIntent::create([
                 'amount' => round($itemPrice * 100),
                 'currency' => $itemCurrency,
            ]);
            return response(['intent' => $intent]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => '404',
                'message' => $e->getMessage(),
                'data'    => null
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    public function storeStripePayment(Request $request)
    {
        $code = $request->code;
        if (Payment::where('code', $code )->exists()) {
            return response()->json([
                'status'  => '404',
                'message' => 'THIS CODE PAID BEFORE',
                'data'    =>  $code

            ]);
        }
        elseif(Category::where('code', $code )->exists()) {
            try {
                $code = $request->code;
                //$itemId = $request->itemId;
                $paymentOption = 'stripe';
                //$currency = $request->currency;
                //$itemPrice = $request->itemPrice;
                $email = $request->email;
               // $itemDescription = $request->itemDescription;
                $payment = Payment::create(
                    [
                    'code' => $code,
                    //'item_id' => $itemId,
                    'payment_option' => $paymentOption,
                    //'currency' => $currency,
                    //'item_price' => $itemPrice,
                    'email' => $email,
                    //'item_description' => $itemDescription,
                    'payment_completed' => true
                    ]
                );
                return response([
                    'status' => '200',
                    'message' => 'NEW payment Done Successfully',
                    'data' => $payment
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'errors' => $e->getMessage()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        else {
            return response()->json([
                'status'  => '404',
                'message' => 'THIS CODE NOT FOUND ',
                'data'  =>  null
            ]);
        }

    }
    public function allPayment()
    {
      $payment = Payment::all();
      return response()->json([
        'status'  => '200',
        'message' => 'success',
        'data' => $payment,
    ]);
    }

}
