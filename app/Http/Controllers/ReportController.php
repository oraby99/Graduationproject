<?php

namespace App\Http\Controllers;

use App\report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index()
    {
      $allreport    = report::all();
      return response()->json([
        'status'  => '200',
        'message' => 'success',
        'data' => $allreport,
    ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:100',
            'code' => 'required|string|max:100',
            'problem' => 'required|string|max:100',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        $city = report::create([
            'email'  => $request->email,
            'code'    => $request->code,
            'problem' => $request->problem
        ]);
        return response()->json([
            'status'  => 200,
            'message' => " Report Created Successfully",
            'data' => $city,

        ]);
    }
}
