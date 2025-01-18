<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customerId' => ['required', 'integer'],
            'amount' => ['required', 'numeric'],
            'status' => ['required', Rule::in(['P', 'B', 'p', 'b'])],
            'billedDate' => ['required', 'date_format:Y-m-d H:i:s'],
            'paidDate' => ['date_format:Y-m-d H:i:s', 'nullable']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'billed_date' => $this->billedDate,
            'paid_date' => $this->paidDate
        ]);
    }
}
