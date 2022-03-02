<?php

namespace App\Http\Rules;

use App\Constants\PaymentTypeConstants;
use App\Models\Balance;
use App\Models\Ledger;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CheckLedgerAmount implements Rule
{
    private $customerId;
    private $amount;

    public function __construct($customerId, $amount)
    {
        $this->customerId = $customerId;
        $this->amount = $amount;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try{
            $balance = Balance::whereCustomerId($this->customerId)->first()->bonus_amount;
            if(($value == PaymentTypeConstants::PAYMENT_BY_BONUS) && ($balance - $this->amount < 0)){
                return false;
            }

            return true;
        }catch(\Exception | \Throwable $e){
            Log::critical($e->getMessage());
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string|array
    {
        return [
            'Customer bonus amount is less than your deducted amount'
        ];
    }
}