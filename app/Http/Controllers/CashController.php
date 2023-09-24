<?php

namespace App\Http\Controllers;

use App\Cash;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashController extends Controller
{

    public function cashOnDeleviry(Request $request)
    {
        $code = $request->code;
        if (Cash::where('code', $code )->exists()) {
            return response()->json([
                'status'  => '404',
                'message' => 'THIS CODE PAID BEFORE',
                'data'   => NULL

            ]);
        }
        elseif(Category::where('code', $code )->exists()) {
            $validator = Validator::make($request->all(), [
                'name'         => 'required|string',
                'address'      => 'required|string',
                'phone'        => 'required|string',
                'country'      => 'required|string',
                'relationship' => 'required|string',
                'quantity'     => 'required|string',
                'email'        => 'required|string',
                'code'        => 'required|string',
                'type'        => 'required|string',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors);
            }
                $cash = Cash::create([
                'name'         => $request->name,
                'address'      => $request->address,
                'phone'        => $request->phone,
                'country'      => $request->country,
                'relationship' => $request->relationship,
                'quantity'     => $request->quantity,
                'email'        => $request->email,
                'code'         => $request->code,
                'type'         => $request->type,
            ]);
            return response()->json([
                'status'  => '200',
                'message' => 'payment done successfully',
                'data'    =>   $cash

            ]);
        }
        else {
            return response()->json([
                'status'  => '404',
                'message' => 'THIS CODE NOT FOUND ',
                'data'    =>  null

            ]);
        }
    }
    public function allCashPayment()
    {
      $cashPayment = Cash::all();
      return response()->json([
        'status'  => '200',
        'message' => 'success',
        'data' => $cashPayment,
    ]);
    }
}
