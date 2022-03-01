<?php

namespace App\Http\Controllers;

use App\Http\Requests\LedgerRequest;
use App\Models\Customer;
use App\Models\Ledger;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Customer $id
     * @return RedirectResponse|View
     */
    public function index(Customer $id): RedirectResponse|View
    {
        try{
            $title = "Customer Ledger";

            $customer = Ledger::whereCustomerId($id)->latest()->first();

            $ledger = Ledger::query()
                        ->when(request('date'),function($query){
                            $query->where('date',request('date'));
                        })
                        ->when(request('type'),function($query){
                            if(request('type') != 'all'){
                                $query->where('type',request('type'));
                            }
                        })
                        ->where('customer_id',$id)
                        ->paginate();

            return view('ledger.index',compact('ledger','customer','title'));
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return view('errors.500');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  LedgerRequest  $request
     * @return RedirectResponse|View
     */
    public function store(LedgerRequest $request): RedirectResponse|View
    {
        try{
            Ledger::create([
                'customer_id' => $request->customer_id,
                'type' => $request->type,
                'date' => $request->date ?? Carbon::now(),
                'amount' => $request->amount,
            ]);
            session()->flash('success','Ledger Update Successful');
            return back();
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return view('errors.500');
        }
    }
}
