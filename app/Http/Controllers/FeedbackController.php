<?php

namespace App\Http\Controllers;

use App\feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{

 public function index()
 {
   $allfeedback    = feedback::all();
   return response()->json([
    'status'  => '200',
    'message' => 'success',
    'data' => $allfeedback,
 ]);
 }
 public function store(Request $request)
 {
     $validator = Validator::make($request->all(), [
         'name'       => 'required|string|max:100',
         'pros'       => 'required|string|max:100',
         'cons'       => 'required|string|max:100',
         'suggest'    => 'required|string|max:100',
         'rate'       => 'required|string|max:100',
     ]);

     if ($validator->fails()) {
         $errors = $validator->errors();
         return response()->json($errors);
     }
     $city = feedback::create([
         'name'          => $request->name,
         'pros'          => $request->pros,
         'cons'          => $request->cons,
         'suggest'       => $request->suggest,
         'rate'          => $request->rate
     ]);
     return response()->json([
        'status'  => 200,
        'message' => " Feedback Created Successfully",
        'data' => $city,

     ]);
 }
}
