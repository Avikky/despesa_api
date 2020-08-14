<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ExpenseCategory;
use App\Http\Resources\ExpenseCategoryResources;
use Illuminate\Support\Facades\Validator;


class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exCategory = ExpenseCategory::all()->latest();
        return ExpenseCategoryResources::collection($exCategory);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }
        $exCategory = new ExpenseCategory;
        $exCategory->name = $request->input('name');
        if($exCategory->save()){
            return new ExpenseCategoryResources($exCategory);
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
        $exCategory = ExpenseCategory::find($id)->first();
        return new ExpenseCategoryResources($exCategory);
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
            'name' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }
        $exCategory = ExpenseCategory::find($id)->first();

        $exCategory->name = $request->name;
        if($exCategory->save()){
            return new ExpenseCategoryResources($exCategory);
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
        $expense = ExpenseCategory::find($id)->first();
        $expense->delete();
    }
}
