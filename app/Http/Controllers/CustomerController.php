<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('name') && !empty($request->get('name'))){
            return response()->json(Customer::where('name', 'like', '%'.strtolower($request->get('name')).'%')->get());
        }else{
            return response()->json(Customer::all());
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
        $customerData = json_decode($request->getContent(), TRUE);
        $customer = Customer::where('name', '=', strtolower($customerData['name']))->first();
        if ($customer === null) {
            $customer = Customer::create($customerData);
        }else{
            $customer = false;
        }

        return response()->json($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = json_decode($request->getContent(), TRUE);
        $customer = Customer::find($id);
        $customer->name = $data["name"];
        $customer->phone = $data["phone"];
        return response()->json($customer->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if($customer->stockOut()->count()){
            return false;
        }
        return response()->json($customer->delete());
    }
}
