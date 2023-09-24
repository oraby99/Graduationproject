<?php

namespace App\Http\Controllers;

use App\Code;
use App\User;
use App\Mail\MailNotify;
use App\Mail\NotifyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CodeController extends Controller
{
    public function index()
    {
        $role = Code::get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $role
            ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:100',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        $relative = Code::create([
            'name' => $request->name,
            'role_id' => $request->role_id
        ]);
        $relative = "role created successfully";
        return response()->json([
            'status'  => ' 200',
            'message' => " New Role Created Successfully",
            'data' => $relative

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
        $Code = Code::find($id);
        if($Code)
        {
            $Code->update([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);
            return response()->json([
                'status'  => ' 200',
                'message' => "Role Updated Successfully",
                'data' => $Code,


            ]);
        }
        else
        {
            return response()->json([
                'status'  => ' 404',
                'message' => "THIS ID NOT FOUND",
                'data' => null

            ]);
        }

    }
    public function delete($id)
    {
        $Code = Code::find($id);
        if($Code){
            $Code->delete();
            return response()->json([
                'status'  => ' 200',
                'message' => "Role Deleted Successfully",
                'data' => $Code

            ]);
        }
        else
        {
            return response()->json([
                'status'  => ' 404',
                'message' => "THIS ID NOT FOUND",
                'data' => null

            ]);
        }
    }
    public function updateUserRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_admin' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        $Code = User::find($id);
        if ($Code) {
            $Code->update([
                'is_admin' => $request->is_admin,
            ]);
            return response()->json([
                'status'  => 200,
                'message' => 'UserRole Updated Successfully',
                'data' => $Code

            ]);
        }
        else
        {
            return response()->json([
                'status'  => ' 404',
                'message' => "THIS ID NOT FOUND",
                'data' => null

            ]);
        }

    }
    public function changePassword(Request $request) {
        $randomId       =   rand(4,5000);
        $useremail      = $request->email;
        $data=[
            'subject' => "SMART ASSISTANT GLASSES",
            'body'    => "SAG_".$randomId ,
        ];
        if (User::where('email', $useremail )->exists()) {
            Mail::to($useremail)->send(new MailNotify($data));
            $newPass = User::where('email' , "=" , $useremail)->first();
            if($newPass)
            {
                $newPass->update([
                    'password' =>Hash::make($data['body']),
                ]);
                return response()->json([
                    'status'  => 200,
                    'message' =>"Password updated successfully",
                    'data' => $newPass

                ]);
            }
        }
       else{
        return response()->json([
            'status'  => 404,
            'message' =>"THIS USER EMAIL DOESN'T EXIST",
            'data' => null

        ]);
       }

    }
    public function EditProfile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'name'  => 'required|string',
            'phone' => 'required|string',
            'city'  => 'required|string',
            'img'   => 'required|image',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors);
        }
        $Code = User::find($id);
       if ($Code) {
        $name = $Code->img;
        if($request->hasFile('img'))
        {
            if($name !== null)
            {
                unlink( public_path('uploads/user/') . $name );
            }
            $img = $request->file('img');
            $ext = $img->getClientOriginalExtension();
            $name = "user-". uniqid() . ".$ext";
            $img->move( public_path('uploads/user/') , $name);
        }
        $Code->update([
            'email' => $request->email,
            'name'  => $request->name,
            'phone' => $request->phone,
            'city'  => $request->city,
            'img'   => $name
        ]);
        return response()->json([
            'status'  => 200,
            "message" => 'Profile Updated Successfully',
            'data' => $Code

        ]);
       }
       else{
        return response()->json([
            'ERROR'  => 404,
            "message" => 'THIS ID NOT FOUND',
            'data' => null
        ]);
       }
    }

}
