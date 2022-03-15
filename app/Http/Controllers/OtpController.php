<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    public function checkOtp(Request $request){
        $validator = Validator::make($request->all(),[
            'code' => 'required|numeric|digits:6'
        ]);

        if($validator->fails()){
            return response($validator->errors()->first(), 201);
        }

        $combine = $request->code."FlixShop";

        if (Hash::check($combine, $request->hash)) {
            return response('number verified', 200);
        }else{
            return response('number verified Failed', 201);
        }
    }

    public function sendOtp(Request $request){
        $username = $request->username;
        $password = $request->password;
        $sender = $request->phone;
        $msg = $request->message;
        $combine = "username=".$username."&password=".$password."&number=".$sender."&message=".$msg;
        $response = Http::accept('text/plain')->get("http://66.45.237.70/api.php?".$combine);
        
        return response($response,200);
    }
}
