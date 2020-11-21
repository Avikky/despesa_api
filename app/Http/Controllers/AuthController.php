<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

     /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
      public function register(Request $request){

       $validateData = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|integer',
            'gender' => 'required|string|',
            'phone' => 'required|string',

        ]);

        if ($validateData->fails()) {
            return response()->json(['errors'=>$validateData->errors()], 422);
        }
        $roleData = $request->has('role') ? $request->role : 3;
        $createUser =  User::create([
        'name' => $request['name'],
        'email' => $request['email'],
        'password' => Hash::make($request['password']),
        'role' => $roleData,
        'gender' => $request['gender'],
        'phone' => $request['phone']
        ]);

        return response()->json(['user'=> $createUser, 'message' => 'User created successfully'], 200);
    }


    public function login(Request $request)
    {
          $validateData = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        if ($validateData->fails()) {
            return response()->json(['errors'=>$validateData->errors(), 'status'=>422]);
        }

        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {

            return $this->respondWithToken($token);
        }else {
            return response()->json(['error' => 'Unauthorized', 'status' => 401]);
        }


    }

    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout()
    {
        auth()->invalidate();
        $this->guard()->logout();
        return response()->json(['message' => 'Successfully logged out', 'status'=>200]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

     public function refresh()
    {
        $newToken = auth()->refresh(true, true);
        return $this->respondWithToken($newToken);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = $this->guard()->user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user_role' => $user->role,
            'username' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'id' => $user->id,
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

}
