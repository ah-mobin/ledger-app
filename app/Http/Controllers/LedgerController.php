<?php

namespace App\Http\Controllers;

use App\Http\Requests\LedgerRequest;
use App\Models\Customer;
use App\Models\Ledger;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function index($id): View|string
    {
        try{
            $ledger = Ledger::whereCustomerId($id)->paginate();
            return view('ledger.index',compact('ledger'));
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return back();
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\LedgerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LedgerRequest $request): View|string
    {
        try{
            Ledger::create([
                'customer_id' => $request->customer_id,
                'type' => $request->type,
                'date' => $request->date ?? Carbon::now(),
                'amount' => $request->amount,
            ]);
            session()->flash('success','Customer Created Successful');
            return back();
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return back();
        }
    }
}
