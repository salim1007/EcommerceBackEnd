<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {


        $credlist = User::all();
        return response()->json([
            'status' => 200,
            'credlist' => $credlist,

        ]);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'midname' => 'required',
            'surname' => 'required',
            'email' => 'required|email|max:191|unique:users,email',
            'regno' => 'required',
            'year' => 'required|integer',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        } else {
            $user = User::create([
                'firstname' => $request->firstname,
                'midname' => $request->midname,
                'surname' => $request->surname,
                'regno' => $request->regno,
                'year' => $request->year,
                'email' => $request->email,
                'password' => Hash::make($request->password),

            ]);

            $token = $user->createToken($user->email . '_Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'username' => $user->firstname,
                'token' => $token,
                'message' => 'Registered Successfully',
            ]);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ]);
        } else {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => "Invalid Credentials",
                ]);
            } else {
                if ($user->role_as == 1) //1 = admin
                {
                    $role = 'admin';
                    $token = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
                } else {
                    $role = '';
                    $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;
                }
                return response()->json([
                    'status' => 200,
                    'username' => $user->firstname,
                    'token' => $token,
                    'message' => 'Logged In Successfully',
                    'role' => $role,
                ]);
            }
        }
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Logged Out Successfully',
        ]);
    }
}
