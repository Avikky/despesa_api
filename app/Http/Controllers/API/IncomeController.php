<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Income;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\IncomeResources;

class IncomeController extends Controller
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
        $income = Income::where('deleted_at', NULL)->latest()->paginate(10);
        
        if($income){
            return IncomeResources::collection($income);
        }else {
            return response()->json('No Data Found', 404);
        }
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $income = new Income;
        $validator = Validator::make($request->all(), [
            'source' => 'required|string',
            'description' => 'required|string',
            'mop' => 'required|string',
            'amount' => 'required|integer',
            'vat_percentage' => 'required|string',
            'date_received' => 'required|date',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        if($income->create($request->all())){
            return response()->json('Income Created successfully', 200);
        }else{
            return response()->json('Opps Something went wrong', 500);
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
        $income = Income::find($id);
        if($income){
            return (new IncomeResources($income))->additional(['status' => ['success' => 200]]);
        }else {
            return response()->json(['error' => 'No data found'], 404);
        }
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
        $income = Income::find($id);
       $validator = Validator::make($request->all(), [
            'source' => 'required|string',
            'description' => 'required|string',
            'mop' => 'required|string',
            'amount' => 'required|integer',
            'vat_percentage' => 'required|string',
            'date_received' => 'required|date',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        if($income->update($request->all())){
            return (new IncomeResources($income))->additional(['status' => ['success' => 200]]);
        }else{
            return response()->json(['error' => 'Opps Something went wrong'], 404);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = Income::find($id);
        if($income){
            if($income->delete()){
                return response()->json(['success'=> 'Data deleted successfully'], 200);
            }
        }else{
            return response()->json(['error'=>'This Data is no longer available'], 410);
        }
    }
}
