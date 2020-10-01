<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Expense;
use App\OpeningBalance;
use DB;
use App\Http\Resources\ExpenseResources;
use Illuminate\Support\Facades\Validator;

class ExpensesController extends Controller
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
    public function index()
    {
        $expenses = Expense::where('deleted_at', NULL)->latest()->paginate(15);

        if(count($expenses) ==  0){
            return response()->json(['message'=>'No Data Found'], 404);
        }

        return ExpenseResources::collection($expenses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'made_by' => 'required|string',
            'amount' => 'required|integer',
            'opening_bal_id' => 'required|integer',
            'date_of_expense' => 'required|date',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $expenses = new Expense;
        $expenses->category_id = $request->input('category_id');
        $expenses->title = $request->input('title');
        $expenses->description = $request->input('description');
        $expenses->made_by = $request->input('made_by');
        $expenses->amount = $request->input('amount');
        $expenses->opening_bal_id = $request->input('opening_bal_id');
        $expenses->date_of_expense = $request->input('date_of_expense');

        if($expenses->save()){
            return new ExpenseResources($expenses);
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
        $expense = Expense::find($id)->first();
        return new ExpenseResources($expense);
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
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'made_by' => 'required|string',
            'amount' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $expense = Expense::find($id)->first();
        $expense->category_id = $request->input('category_id');
        $expense->title = $request->input('title');
        $expense->description = $request->input('description');
        $expense->made_by = $request->input('made_by');
        $expense->amount = $request->input('amount');

        if($expense->save()){
            return new ExpenseResources($expense);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $expense = Expense::find($id);
        $openingBal  =  OpeningBalance::find($request->balId);
        $newBal;
        if($openingBal){
            $newBal = $openingBal->amount + $request->amount;
        }
        $openingBal->amount = $newBal;
        $openingBal->save();
        if($expense){
            if($expense->delete()){
                return response()->json(['success'=> 'Data deleted successfully', 'newBal'=>$openingBal->amount], 200);
            }
        }else{
            return response()->json(['error'=>'Data is no longer available'], 410);
        }

    }
}
