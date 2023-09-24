<?php

namespace App\Http\Controllers;

use App\Cash;
use App\Payment;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class CategoryController extends Controller
{
    public function newglassess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string',
            'desc'         => 'required|string',
            'code'         => 'required|string',
            'board_id'     => 'required|string',
            'img'          => 'required|image',
            'price'        => 'required|string',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
            $img = $request->file('img');
            $ext = $img->getClientOriginalExtension();
            $name = asset('public/uploads/glass/' ."book-". uniqid() . ".$ext" );
            $img->move( public_path('uploads/glass') , $name);

 if (Category::where('code', $request->code )->exists() || Category::where('board_id', $request->board_id  )->exists() ) {
            return response()->json([
                'status'  => '404',
                'message' => 'THIS CODE OR BOARD_ID ALREADY EXISTS',
                'data'    =>  null

            ]);
        }
       else
       {
        $cash = Category::create([
            'name'         => $request->name,
            'desc'         => $request->desc,
            'code'         => $request->code,
            'board_id'     => $request->board_id,
            'img'          => $name,
            'price'         => $request->price,
        ]);
        return response()->json([
            'status'  => 200,
            'message' => "THIS GLASS Added Successfully",
            'data'    =>  $cash

        ]);
       }
    }
    public function show($id)
    {
        $Category = Category::find($id);
        if ($Category) {
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $Category
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No product id found',
                'data'    =>  null

            ]);
        }
    }
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'desc' => 'required|string',
            'code' => 'required|string',
            'board_id' => 'required',
            'img' => 'required|image',
            'price' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json($errors);
            }
            $Category = Category::find($id);
            if ($Category) {
                $name = $Category->img;
                if ($Category) {

                if($request->hasFile('img'))
                {
                if($name !== null)
                {
                    unlink( public_path('uploads/glass/') . $name );
                }
                $img = $request->file('img');
                $ext = $img->getClientOriginalExtension();
                $name = asset('uploads/glass/' ."book-". uniqid() . ".$ext" );
                $img->move( public_path('uploads/glass/') , $name);
               }
// if (Category::where('code', $request->code )->exists() || Category::where('board_id', $request->board_id  )->exists()

// ) {

//     return response()->json([
//         'status'  => '404',
//         'message' => 'THIS CODE OR BOARD_ID ALREADY EXISTS',
//         'data'    =>  null

//     ]);
//    }
//         else{
            $Category->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'code' => $request->code,
                'board_id' => $request->board_id,
                'img' => $name,
                'price'=> $request->price
            ]);
            return response()->json([
                'status'  => 200,
                'message' => "THIS GLASS Updated Successfully",
                'data'    =>  $Category
            ]);
           // }
        }

    }
    else{
        return response()->json([
            'status'  => 404,
            'message' => "THIS GLASS Id Not Found ",
            'data'    =>  null
        ]);
        }
    }
    public function delete($id)
    {
        $Category = Category::find($id);
        if($Category)
        {
            if($Category->img !== null)
        {
            unlink( public_path('uploads/glass/') . $Category->img );
        }
        $Category->delete();
        return response()->json([
            'status'  => 200 ,
            'message' => 'Glassess Deleted Successfully',
            'data'    =>  $Category

        ]);
        }
        else{
            return response()->json([
                'status'  => 404 ,
                'message' => 'THIS ID NOT FOUND',
                'data'    =>  null

            ]);
        }
    }
    public function allglassess()
    {
      $allglassess    = Category::all();
      return response()->json([
        'status' => 200,
        'message' => 'success',
        'data' => $allglassess,

    ]);
    }
    public function solidglassess()
    {
        $Cashglassess       = Cash::all();
        $paymentglassess    = Payment::all();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => [
                'CashOnDelivryGlassess'    => $Cashglassess,
                'paymentStripeGlassess' => $paymentglassess,
            ]

    ]);
    }
    // public function availableglassess()
    // {

    //   $Cashglassess         = Cash::select('code')->get();
    //   $paymentglassess      = Payment::select('code')->get();
    //   $allglassess          = Category::all();
    //   $json_array_first = json_decode($Cashglassess, true);
    //   $json_array_second = json_decode($paymentglassess, true);
    //   $merged_json_object = array_merge($json_array_first, $json_array_second);

    //   foreach($merged_json_object as $item) { //foreach element in $arr
    //     $uses = $item['code']; //etc
    //    }
    //   if (Category::where('code', '!=',$uses )->exists()) {
    //         return Category::select('code')->get();
    //     }
    //   return response()->json([
    //     //'AvailableGlassess' => $allglassess,
    //     //'Cashglassess'      => $Cashglassess,
    //     //'paymentglassess'   => $paymentglassess,
    //     //'$encoded_merged_json' =>$merged_json_object,
    //     '$encoded_merged_json' =>$uses
    // ]);
    // }

}


