<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'phone' => 'required|numeric|digits:11',
            'gender' => 'required',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return response($validator->errors()->first(), 201);
        }

        $checkPhone = User::where('phone',$request->phone)->first();
        if($checkPhone){
            return response("Mobile number already exists in database", 201);
        }

        $edu = new User();
        $edu->name = $request->name;
        $edu->phone = $request->phone;
        $edu->gender = $request->gender;
        $edu->password = Hash::make($request->password);
        $edu->save();
        $code = rand(100000,999999);
        $combine = $code."FlixShop";
        $hash = Hash::make($combine);
        $data = ['result' => $edu,'code' => $code,'hash' => $hash];
        return response($data , 200);
    }

    // public function store(Request $request)
    // {
    //     $edu = Hash::make("3456Awertyu");
    //     return response($edu, 200);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function loginuser(Request $request){
        $phone = $request->phone;
        $password = $request->password;

        $validator = Validator::make($request->all(),[
            'phone' => 'required|numeric|digits:11',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response($validator->errors()->first(), 201);
        }
        
        $user = User::where('phone',$phone)->first();
        if ($user) {
            if (Hash::check($password, $user->password)) {
                $token = $user->createToken('Laravel grant Client')->accessToken;
                return response([
                    'user' => $user,
                    'token' => $token
                ], 200);
            } else {
                return response("Password not foumnd", 201);
            }
            
        }else{
            return response("user not found", 201);
        }
    }

    public function logout(){
        $token = Auth::user()->token();
        return $token->revoke();
    }

    public function login_userdata(){
        $user = Auth::user();
        if($user){
            return response($user, 200);
        }else{
            return response("user not found", 201);
        }
    }

    

}
