<?php

namespace App\Observers;

use App\Constants\PaymentTypeConstants;
use App\Models\Balance;
use App\Models\Ledger;
use Illuminate\Support\Facades\Log;

class LedgerObserver
{
    /**
     * Handle the Ledger "created" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function created(Ledger $ledger)
    {
        Log::alert("LedgerObserver -> created() called");
        if($ledger->payment_type_id != PaymentTypeConstants::LEDGER_OPEN){
            $getBalance = Balance::findOrFail($ledger->customer_id);
            Log::alert($getBalance);
            if($ledger->payment_type_id == PaymentTypeConstants::DUE_ADD){
                if($getBalance->customer_balance > 0){
                    $dueBalanceCalc = $getBalance->customer_balance - $ledger->amount;
                    $getBalance->customer_balance = $dueBalanceCalc < 0 ? 0 : $dueBalanceCalc;
                    $getBalance->due_amount = $dueBalanceCalc < 0 ? abs($dueBalanceCalc) : $getBalance->due_amount;
                }else{
                    $getBalance->due_amount = $getBalance->due_amount + $ledger->amount;
                }
            }

            if($ledger->payment_type_id == PaymentTypeConstants::PAYMENT_FROM_CUSTOMER){
                if($getBalance->due_amount == 0){
                    $getBalance->customer_balance += $ledger->amount;
                }
                else{
                    $dueCalculate = $getBalance->due_amount - $ledger->amount;
                    $getBalance->due_amount = $dueCalculate < 0 ? 0 : $dueCalculate;
                    $getBalance->customer_balance = $dueCalculate < 0 ? abs($dueCalculate) : $getBalance->customer_balance;
                }

            }

            if($ledger->payment_type_id == PaymentTypeConstants::BONUS_ADD){
                $getBalance->bonus_amount += $ledger->amount;
            }

            if($ledger->payment_type_id == PaymentTypeConstants::PAYMENT_BY_BONUS){
                $getBalance->bonus_amount = $getBalance->bonus_amount - $ledger->amount;
            }

            $getBalance->save();
        }
    }

    /**
     * Handle the Ledger "updated" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function updated(Ledger $ledger)
    {
        //
    }

    /**
     * Handle the Ledger "deleted" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function deleted(Ledger $ledger)
    {
        //
    }

    /**
     * Handle the Ledger "restored" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function restored(Ledger $ledger)
    {
        //
    }

    /**
     * Handle the Ledger "force deleted" event.
     *
     * @param  \App\Models\Ledger  $ledger
     * @return void
     */
    public function forceDeleted(Ledger $ledger)
    {
        //
    }
}
