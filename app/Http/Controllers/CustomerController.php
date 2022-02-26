<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Ledger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
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
            $total = Ledger::where('type','Due Added')->sum('amount');
            $deducts = Ledger::where('type','Due Deducted')->sum('amount');
            $dues = $total - $deducts;
            return view('customers.index',compact('customers','dues'));
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
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
