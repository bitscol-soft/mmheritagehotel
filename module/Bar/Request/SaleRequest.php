<?php

namespace Module\Bar\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Module\Bar\Services\SaleService;

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




    public function store()
    {
        DB::transaction(function () {

            $this->service->store();

            $this->service->storeItem();

            $this->service->calculateSaleAmount($this->service->sale->id);

        });

        return $this->service->sale;

    }
}
