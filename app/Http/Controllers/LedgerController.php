<?php

namespace App\Http\Controllers;

use App\Constants\PaymentTypeConstants;
use App\Http\Requests\LedgerRequest;
use App\Models\Balance;
use App\Models\Ledger;
use App\Models\PaymentType;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return RedirectResponse|View
     */
    public function index($id): RedirectResponse|View
    {
        try{
            $title = "Customer Ledger";

            $customer = Ledger::whereCustomerId($id)->first();

            $types = PaymentType::select('id','type')->get();



            $ledger = Ledger::query()
                        ->when(request('from_date'),function($query){
                            $query->whereBetween('date',[request('from_date'),request('to_date') ?? Carbon::today()->format('y-m-d')]);
                        })
                        ->when(request('type'),function($query){
                            if(request('type') != 'all'){
                                $query->where('payment_type_id',request('type'));
                            }
                        })
                        ->when(request('remarks'),function($query){
                            $query->where('remarks','LIKE','%'.request('remarks').'%');
                        })
                        ->where('customer_id',$id)
                        ->orderBy('id','desc')
                        ->paginate();

            return view('ledger.index',compact('ledger','customer','title','types'));
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
        DB::beginTransaction();
        try{
            Ledger::create([
                'customer_id' => $request->customer_id,
                'payment_type_id' => $request->type,
                'date' => $request->date ?? Carbon::now(),
                'amount' => $request->amount,
                'remarks' => $request->remarks,
            ]);
            DB::commit();
            session()->flash('success','Ledger Update Successful');
            return back();
        }catch(\Exception | \Throwable $e){
            DB::rollBack();
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return view('errors.500');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  LedgerRequest  $request
     * @param  Ledger  $ledger
     * @return RedirectResponse|View
     */
    public function update(LedgerRequest  $request,$customerId,$ledgerId): RedirectResponse|View
    {
        DB::beginTransaction();
        try{
            $ledgerInstance = Ledger::findOrFail($ledgerId);
            $ledgerInstance->payment_type_id = $request->type;
            $ledgerInstance->amount = $request->amount;
            $ledgerInstance->date = $request->date ?? $ledgerInstance->date;
            $ledgerInstance->remarks = $request->remarks;
            $ledgerInstance->save();

            $getBalance = Balance::findOrFail($ledgerInstance->customer_id);
            $sumOfDueType = Ledger::whereCustomerId($ledgerInstance->customer_id)->wherePaymentTypeId(PaymentTypeConstants::DUE_ADD)->sum('amount');
            $sumOfDuePaidType = Ledger::whereCustomerId($ledgerInstance->customer_id)->wherePaymentTypeId(PaymentTypeConstants::PAYMENT_FROM_CUSTOMER)->sum('amount');
           

            $sumOfBonusType = Ledger::whereCustomerId($ledgerInstance->customer_id)->wherePaymentTypeId(PaymentTypeConstants::BONUS_ADD)->sum('amount');
            $sumOfBonusPaidType = Ledger::whereCustomerId($ledgerInstance->customer_id)->wherePaymentTypeId(PaymentTypeConstants::PAYMENT_BY_BONUS)->sum('amount');
            $calcOfDuePaidAmount = $sumOfDueType - $sumOfDuePaidType;
            Log::debug($calcOfDuePaidAmount);
            $getBalance->due_amount = $calcOfDuePaidAmount >= 0 ? $calcOfDuePaidAmount : 0;
            $getBalance->customer_balance = $calcOfDuePaidAmount < 0 ? $calcOfDuePaidAmount : 0;
            $getBalance->bonus_amount = $sumOfBonusType;

            $getBalance->save();

            DB::commit();
            session()->flash('success','Ledger Updated Successful');
            return back();
        }catch(\Exception | \Throwable $e){
            DB::rollBack();
            Log::critical($e->getMessage());
            session()->flash('danger','Something Went Wrong');
            return view('errors.500');
        }
    }
}
