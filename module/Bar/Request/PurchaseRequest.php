<?php

namespace Module\Bar\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Module\Bar\Services\PurchaseService;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public $service;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->service = $purchaseService;
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
            'date'              => 'required',
            'subtotal'          => 'required',
            'grand_total'       => 'required',
            'discount'          => 'required',
            'paid_amount'       => 'required',
        ];
    }



    public function store()
    {
        DB::transaction(function () {

            $this->service->storePurchase();



            $this->service->storePurchaseDetails();



            // $this->service->updateTransaction();



            // $this->service->updateSuplierBalance($this->request);
        });

        return $this->service->purchase;
    }
}
