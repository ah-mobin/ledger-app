<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:64',
            'email' => 'nullable|email|unique:customers,email,'.$this->customer->id,
            'phone_number' => 'required|max:32||unique:customers,phone_number,'.$this->customer->id,
        ];
    }
}
