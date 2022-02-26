<?php

namespace App\Http\Requests;

use App\Http\Rules\CheckLedgerAmount;
use Illuminate\Foundation\Http\FormRequest;

class LedgerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required','exists:customers,id',(new CheckLedgerAmount(
                request('type'),
                request('amount')
            ))],
            'type' => 'required',
            'date' => 'nullable',
            'amount' => 'required|numeric|gt:0',
        ];
    }
}
