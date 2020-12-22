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
        $customer = Customer::latest()->paginate(10);

        if(count($customer) ==  0){
            return response()->json('No Data Found', 404);
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
            'category' => 'required|string',
            'service_provided' => 'required|string',
            'payment_interval' => 'required|string',
            'current_billing_date' => 'sometimes|required|date',
            'payment_status' => 'nullable|string',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        $customer = new Customer;
        $customer->name = $request->input('name');
        $customer->category = $request->input('category');
        $customer->service_provided = $request->input('service_provided');
        $customer->payment_interval = $request->input('payment_interval');
        $customer->current_billing_date = $request->input('current_billing_date');
        $customer->payment_status = $request->input('payment_status');

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
            'name' => 'required|string|unique:customers,name,'.$id,
            'category' => 'required|string',
            'service_provided' => 'required|string',
            'payment_interval' => 'required|string',
            'current_billing_date' => 'sometimes|required|date',
            'payment_status' => 'nullable|string',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 422);
        }

        $customer->name = $request->input('name');
        $customer->category = $request->input('category');
        $customer->service_provided = $request->input('service_provided');
        $customer->payment_interval = $request->input('payment_interval');
        $customer->current_billing_date = $request->input('current_billing_date');


        if($customer->save()){
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
