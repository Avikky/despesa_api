<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResources;
use App\User;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validateData = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => ['sometimes','required','email',Rule::unique('users')->ignore($user->id)],
            'phone' => 'sometimes|required|string',
        ]);

        if($user->update($request->all())){
            return (new UserResources($user))->additional(['status' => ['success' => 200]]);
        }else{

            return response()->json(['error' => 'Opps Something went wrong'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $validateData = Validator::make($request->all(), [
            'oldpassword' => 'required|password:api',
            'newpassword' => 'required|string',
            'newpassword_confirmation' => 'required|string',
        ]);

        $user->password = Hash::make($request->input('newpassword'));

        if($user->save()){
            return response()->json(['success' => 'Password Reset Successful'], 200);
        }else{
            return response()->json(['error' => 'Opps something went wrong'], 500);

        }
    }

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
}
