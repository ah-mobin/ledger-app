<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View|string
    {
        try{
            $customers = Customer::search(request('search'))->paginate();
            return view('customers.index',compact('customers'));
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CustomerStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerStoreRequest $request): View|string
    {
        try{
            Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);
            session()->flash('success','Customer Created Successful');
            return back();
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\CustomerUpdateRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpdateRequest $request, Customer $customer): View|string
    {
        try{
            Customer::whereId($customer->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);
            session()->flash('success','Customer Updated Successful');
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer): View|string
    {
        try{
            Customer::whereId($customer->id)->delete();
            session()->flash('success','Customer Removed Successful');
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
        }

        return back();
    }
}
