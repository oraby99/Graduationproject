<?php

namespace App\Http\Controllers;

use App\Cash;
use App\Category;
use App\Payment;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ApiAuthController extends Controller
{
    public function handleRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|max:100',
            'password'     => 'required|string|max:50|min:5',
            'relationship' => 'required|string|max:100',
            'phone'        => 'required|string|max:100',
            'city'         => 'required|string|max:100',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        $is_user = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if(! $is_user)
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'relationship' => $request->relationship,
                'city' => $request->city,
                'phone' => $request->phone,
                'access_token' => Str::random(64),
            ]);
            return response()->json([
                'status'      => 200,
                'message'     => $request->name . ' ' .'added succesfully',
                'access_token'=> $user->access_token
            ]);
        }
        else{
            return response()->json([
                 'status' => 404,
                 'message' => "Email Allready exsit",
                 'data'   => NULL

            ]);
        }


    }
    public function handleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
            'password' => 'required|string|max:50|min:5',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        $currentuser = $request->email;
        $payerUser   = Payment::where('email' , "=" , $currentuser)->first();
        $cashUser    = Cash::where('email' , "=" , $currentuser)->first();
       if ($payerUser ) {
        $code = $payerUser->code;
        $catCode    = Category::where('code' , "=" , $code )->first();
       }
       elseif ($cashUser ) {
        $code2 = $cashUser->code;
        $catCode    = Category::where('code' , "=" , $code2 )->first();
       }
       else{
        $catCode = "This User Didn't buy Any Glass ";
       }

        $is_user = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if(! $is_user)
        {
            return response()->json([
                'status' => 404,
                'message'  => "credentials are not correct",
                'data'   => NULL
            ]);
        }
        $user = User::where('email', '=', $request->email)->first();
        $new_access_token = Str::random(64);
        $user->update([
            'access_token' => $new_access_token
        ]);
            $userData = User::where('email', '=', $request->email)->first();
            return response()->json([
                'status'            =>200,
                'message'           =>'LOGGED IN SUCCESSFULY',
                'data' => [
                    "UserInfo"          => $userData,
                    'GlassInfo'         =>$catCode,
                    'StripePaymentInfo' =>$payerUser,
                    'CashPaymentInfo'   =>$cashUser,
                ]

        ]);
    }
    public function logout(Request $request)
    {
        $access_token = $request->access_token;
        $user = User::where('access_token', $access_token)->first();
        if($user == null) {
            return response()->json([
                'status'  => '404',
                'message' => 'This Token Are Not Correct',
                'data'   => NULL

            ]);
        }
        $user->update([
            'access_token' => NULL
        ]);
        return response()->json([
            'status'  => '200',
            'message' => 'Logged Out Successfully',
            'data'   => NULL
        ]);
    }

    public function allUser()
    {
      $user = User::all();
      return response()->json([
        'status'  => '200',
        'message' => 'success',
        'data' => $user,
       ]);
    }


}
