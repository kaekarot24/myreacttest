<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $status_code    =        200;

    public function userSignUp(Request $request) {
        $validator              =        Validator::make($request->all(), [
            "name"              =>          "required|alpha",
            "email"             =>          "required|email|unique:users,email",
            "password"          =>          "required|string|min:6",
            "mobile"            =>          "required|numeric|digits:10"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }

        $userDataArray          =       array(
            "name"               =>          $request->name,  
            "email"              =>          $request->email,
            "password"           =>          md5($request->password),
            "mobile"             =>          $request->mobile,
            "gender"             =>          $request->gender,
            "address"            =>          $request->address,
            "state"              =>          $request->state,
            "city"               =>          $request->city,
            "country"            =>          $request->country
        );

        //print_r($userDataArray); die;

        $user_status            =           User::where("email", $request->email)->first();

        if(!is_null($user_status)) {
           return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! email already registered"]);
        }

        $user   =   User::create($userDataArray);

        if(!is_null($user)) {
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Registration completed successfully", "data" => $user]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to register"]);
        }
    }


    // ------------ [ User Login ] -------------------
    public function userLogin(Request $request) {

        $validator          =       Validator::make($request->all(),
            [
                "email"             =>          "required|email",
                "password"          =>          "required"
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }


        // check if entered email exists in db
        $email_status       =       User::where("email", $request->email)->first();


        // if email exists then we will check password for the same email

        if(!is_null($email_status)) {
            $password_status    =   User::where("email", $request->email)->where("password", md5($request->password))->first();

            // if password is correct
            if(!is_null($password_status)) {
                $user           =       $this->userDetail($request->email);

                return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $user]);
            }

            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Incorrect password."]);
            }
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Email doesn't exist."]);
        }
    }

    //----------------- [User Update] ----------------------------------
    public function userUpdate(Request $request)
    {
        # code...
        $validator              =        Validator::make($request->all(), [
            "name"              =>          "required|alpha",
            "mobile"            =>          "required|numeric|digits:10"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }

        $user = User::find($request->id);

        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;

        if($user->save()){
            $user  =  $this->userDetail($request->email);
              return response()->json(["status" => $this->status_code, "success" => true, "message" => "Data Updated successfully", "data" => $user]);
        }

        return response()->json(["status" => "failed", "success" => false, "message" => "failed to register"]);
    }

    // ------------------ [ User Detail ] ---------------------
    public function userDetail($email) {
        $user               =       array();
        if($email != "") {
            $user           =       User::where("email", $email)->first();
            return $user;
        }
    }
}
