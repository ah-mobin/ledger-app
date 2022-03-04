<?php

namespace App\Observers;

use App\Constants\PaymentTypeConstants;
use App\Models\Balance;
use App\Models\Customer;
use App\Models\Ledger;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function created(Customer $customer)
    {
        Ledger::create([
            'customer_id' => $customer->id,
            'payment_type_id' => PaymentTypeConstants::LEDGER_OPEN,
            'amount' => 0,
            'date' => Carbon::now(),
            'remarks' => 'Ledger Open'
        ]);

        Balance::create([
            'customer_id' => $customer->id,
            'due_amount' => 0,
            'customer_balance' => 0,
            'bonus_amount' => 0,
        ]);
    }

    /**
     * Handle the Customer "updated" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        //
    }


    /**
     * Handle the Customer "deleting" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function deleting(Customer $customer)
    {
        //
    }
    /**
     * Handle the Customer "deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function restored(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function forceDeleted(Customer $customer)
    {
        //
    }
}
