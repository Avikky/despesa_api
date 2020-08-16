<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CustomerResources;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
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
        $customer = Customer::all();

        if(count($customer) ==  0){
            return response()->json(['message'=>'No Data Found'], 404);
        }

        return CustomerResources::collection($customer)->additional(['status' => ['success' => 200]]);
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
            'name' => 'required|string|unique:customers,name',
            'about' => 'nullable|string',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        $customer = new Customer;
        $customer->name = $request->input('name');
        $customer->about = $request->input('about');

        if($customer->save()){
            return (new CustomerResources($customer))->additional(['status' => ['success' => 200]]);
        }else{
            return response()->json(['error' => 'Opps Something went wrong'], 500);
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
        $customer = Customer::find($id);
        if($customer){
            return (new CustomerResources($customer))->additional(['status' => ['success' => 200]]);
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
        $customer = Customer::find($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required','string',Rule::unique('customers')->ignore($id)],
            'about' => 'nullable|string',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        if($customer->update($request->all())){
            return (new CustomerResources($customer))->additional(['status' => ['success' => 200]]);
        }else{
            return response()->json(['error' => 'Opps Something went wrong'], 500);
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
        $customer = Customer::find($id);
        if($customer){
            if($customer->delete()){
                return response()->json(['success'=> 'Data deleted successfully'], 200);
            }
        }else{
            return response()->json(['error'=>'This Data is no longer available'], 410);
        }
    }
}
