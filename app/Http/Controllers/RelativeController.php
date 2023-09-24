<?php

namespace App\Http\Controllers;

use App\relative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelativeController extends Controller
{
    public function index()
    {
        $relative = relative::get();

        return response()->json([
            'status'  => '200',
            'message' => 'success',
            'data' => $relative,
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        $relative = relative::create([
            'name' => $request->name
        ]);

        return response()->json([
           'status'   => "200",
           'message' => "New Relative Created Successfully",
           'data' => $relative,

        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        $relative = relative::find($id);
        if ($relative) {
            $relative->update([
                'name' => $request->name,
            ]);
            return response()->json([
                'status'   => "200",
                'message' => " Relative Updated Successfully",
                'data' => $relative,

             ]);
        }
        else{
            return response()->json([
                'status'   => "404",
                'message' => " THIS ID NOT FOUND",
                'data' => null,

             ]);
        }
    }
    public function delete($id)
    {
        $relative = relative::find($id);
        if ($relative)
        {
            $relative->delete();
            return response()->json([
                'status'   => "200",
                'message' => " Relative Deleted Successfully",
                'data' => $relative,

             ]);
        }
        else{
            return response()->json([
                'status'   => "404",
                'message' => " THIS ID NOT FOUND",
                'data' => null,

             ]);
        }

    }
}
