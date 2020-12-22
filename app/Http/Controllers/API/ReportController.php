<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Income;
use App\Expense;
use App\OpeningBalance;
use App\Customer;


class ReportController extends Controller
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
        $totalExpenses = Expense::where('deleted_at', Null)->pluck('amount')->sum();
        $totalIncome = Income::where('deleted_at', Null)->pluck('amount')->sum();
        $grosProfit = $totalIncome - $totalExpenses;

        if($grosProfit <= 0){
            $grosProfit =  0;
            return response()->json(['totalExpenses'=>number_format($totalExpenses, 2), 'totalIncome'=> number_format($totalIncome, 2), 'grossProfit'=> number_format($grosProfit, 2)], 200);
        }
    }

    public function generateReport(Request $request){
        if($request->expenseCateID || $request->customerName){
            $expenseData = Expense::where('deleted_at', Null)->where('category_id',$request->expenseCateID)->whereBetween('date_of_expense', [$request->sortFrom, $request->sortTo])->get();

            $incomeData = Income::where('deleted_at', Null)->where('source', $request->customerName)
            ->whereBetween('date_received', [$request->sortFrom, $request->sortTo])
            ->get();

             $openingBal = OpeningBalance::whereBetween('date_created', [$request->sortFrom, $request->sortTo])
            ->get();

              if($expenseData){
                return response()->json(
                    [
                        'expenseData'=>$expenseData,
                        'totalSearchExp' => $expenseData->sum('amount'),
                        'totalSearchIncome' => $incomeData->sum('amount'),
                        'incomeData'=>$incomeData,
                        'openingBal' => $openingBal,
                    ], 200);
                }else{
                    return response()->json(['message'=>'problem getting data', 'status'=>500]);
                }
        }

    }

    public function reportWithBalance(Request $request){
        $params = Expense::where('deleted_at', Null)->whereBetween('date_created', [$request->sortFrom, $request->sortFrom])->join('Opening_balances', 'id', '=', 'expenses.opening_bal_id')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
