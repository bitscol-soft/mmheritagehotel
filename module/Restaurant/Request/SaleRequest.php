<?php

namespace Module\Restaurant\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Module\Restaurant\Services\SaleService;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */


    private $service;

    public function __construct(SaleService $saleService)
    {
        $this->service = $saleService;
    }




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
            'invoice_no'    => 'required',
            'subtotal'      => 'required',
            'discount'      => 'required',
            'previous_due'  => 'nullable',
            'paid_amount'   => 'required',
        ];
    }



    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {

        throw new HttpResponseException(response()->json([

            'success'   => false,

            'message'   => 'Validation Error',

            'data'      => $validator->errors()

        ]));

    }



    public function store()
    {
        DB::transaction(function () {

            $this->service->store();

            $this->service->storeItem();
        });
        return $this->service->sale;
    }
}
