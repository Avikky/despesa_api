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

    public function getLastOpeningBal(){
        $openingBal = OpeningBalance::latest()->first();
        if($openingBal){
            return $openingBal;
        }else {
            return response()->json('No opening balance found', 404);
        }

    }

    public function getCurrentOpeningBal(){
       $currentDate =  date("Y-m-d");
       $openingBal = OpeningBalance::where('date_created', $currentDate)->first();
       return response()->json($openingBal);
    }
    public function getGeneralOpeningBal(){
        return $openingBal = OpeningBalance::all();
    }
    public function addOpeninBalance(Request $request){
        //$checkBal = DB::table('opening_balances')->count();
        $openingBal = new OpeningBalance;
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
            'date_created' => 'required|date|unique:opening_balances,date_created',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()]);
        }

        $openingBal->amount = $request->input('amount');
        $openingBal->date_created = $request->input('date_created');

        if($openingBal->save()){
            return response()->json(['success'=>'Opening Balance Added Successfully', 'status'=> 'success']);
        }else{
            return response()->json(['message'=>'Opps something went wrong', 'status'=> 'error'],500);
        }
    }

    public function reusingOpeningBalance(Request $request, $id){
        $openingBal = OpeningBalance::find($id);

        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
        ]);
        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        if($openingBal->update($request->all())){
            return response()->json(['data'=>$openingBal->amount, 'message'=>'Opening Balance Edited Successfully', 'status'=> 'success'], 200);
        }else{
            return response()->json(['message'=>'Opps something went wrong', 'status'=> 'error'],500);
        }
    }

    public function editOpeningBalance(Request $request, $id){
        $openingBal = OpeningBalance::find($id);

        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
            'date_created' => 'sometimes|required|date|unique:opening_balances,date_created,'.$id
        ]);
        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        if($openingBal->update($request->all())){
            return response()->json(['data'=>$openingBal->amount, 'message'=>'Opening Balance Edited Successfully', 'status'=> 'success'], 200);
        }else{
            return response()->json(['message'=>'Opps something went wrong', 'status'=> 'error'],500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $openingBal = OpeningBalance::find($id);
           if($openingBal){
            if($openingBal->delete()){
                return response()->json(['success'=> 'Data deleted successfully'], 200);
            }else{
                return response()->json(['error' => 'cannot delete data']);
            }
        }else{
            return response()->json(['error'=>'Data is no longer available'], 410);
        }
    }
}
