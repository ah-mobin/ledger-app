<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Ledger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        try{
            $title = "Customers";
            $customers = Customer::search(request('search'))->paginate();
            $total = Ledger::where('type','Due Added')->sum('amount');
            $deducts = Ledger::where('type','Due Deducted')->sum('amount');
            $dues = $total - $deducts;
            return view('customers.index',compact('customers','dues','title'));
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            return view('errors.500');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CustomerStoreRequest  $request
     * @return RedirectResponse|View
     */
    public function store(CustomerStoreRequest $request): View|RedirectResponse
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
            return view('errors.500');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CustomerUpdateRequest  $request
     * @param  Customer  $customer
     * @return RedirectResponse|View
     */
    public function update(CustomerUpdateRequest $request, Customer $customer): RedirectResponse|View
    {
        try{
            Customer::whereId($customer->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);
            session()->flash('success','Customer Updated Successful');
            return back();
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return view('errors.500');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Customer  $customer
     * @return RedirectResponse|View
     */
    public function destroy(Customer $customer): RedirectResponse|View
    {
        try{
            Customer::whereId($customer->id)->delete();
            session()->flash('success','Customer Removed Successful');
            return back();
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return view('errors.500');
        }
    }
}
