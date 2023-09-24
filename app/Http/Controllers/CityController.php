<?php

namespace App\Http\Controllers;

use App\city;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index()
    {
        $city = city::get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $city
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
        $city = city::create([
            'name' => $request->name
        ]);
        return response()->json([
            'status'  => 200,
            'message' => "NEW CITY Created Successfully",
            'data' => $city

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
        $city = city::find($id);
        if ($city) {
        $city->update([
            'name' => $request->name,
        ]);
        return response()->json([
            'status'  => 200,
            'message' => " CITY Updated Successfully",
            'data' => $city

        ]);
      }
      else
      {
        return response()->json([
            'status'  => 404,
            'message' => "THIS ID NOT FOUND",
            'data' => null

        ]);
      }
    }
    public function delete($id)
    {
        $city = city::find($id);
        if ($city) {
        $city->delete();
        return response()->json([
            'status'  => 200,
            'message' => " CITY Deleted Successfully",
            'data' => $city

        ]);
    }
        else
        {
          return response()->json([
              'status'  => 404,
              'message' => "THIS ID NOT FOUND",
              'data' => null

          ]);
        }
    }
}
