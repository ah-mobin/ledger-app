<?php

namespace App\Http\Rules;

use App\Models\Ledger;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CheckLedgerAmount implements Rule
{
    private $type;
    private $amount;

    public function __construct($type, $amount)
    {
        $this->type = $type;
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
            $balance = Ledger::whereCustomerId($value)->orderBy('id','desc')->first()->balance;
            if(($this->type == 'Due Deducted') && ($balance - $this->amount < 0)){
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
            'Customer due balance is less than your deducted amount'
        ];
    }
}