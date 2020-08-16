<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\OpeningBalance;
use DB;

class BalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function addOpeninBalance(Request $request){
        //$checkBal = DB::table('opening_balances')->count();
        $openingBal = new OpeningBalance;
        $validateData = Validator::make($request->all(), [
            'amount' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        $openingBal->amount = $request->input('amount');

        if($openingBal->save()){
            return response()->json(['message'=>'Opening Balance Added Successfully', 'status'=> 'success'], 200);
        }else{
            return response()->json(['message'=>'Opps something went wrong', 'status'=> 'error'],500);
        }
    }

    public function editOpeningBalance(Request $request, $id){
        $openingBal = OpeningBalance::find($id);

        $validateData = Validator::make($request->all(), [
            'amount' => 'required|integer',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        if($openingBal->update($request->all())){
            return response()->json(['message'=>'Opening Balance Edited Successfully', 'status'=> 'success'], 200);
        }else{
            return response()->json(['message'=>'Opps something went wrong', 'status'=> 'error'],500);
        }
    }
}
