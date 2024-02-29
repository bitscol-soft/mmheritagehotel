<?php

namespace Module\Restaurant\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Module\Restaurant\Services\PurchaseService;

class RstPurchasesRequest extends FormRequest
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

            $this->service->storeRstPurchase();


            $this->service->storeRstPurchaseDetails();



            // $this->service->updateTransaction();



            // $this->service->updateSuplierBalance($this->request);
        });

        return $this->service->purchase;
    }
}
