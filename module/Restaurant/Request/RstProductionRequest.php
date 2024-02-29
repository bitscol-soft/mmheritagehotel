<?php

namespace Module\Restaurant\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Module\Restaurant\Services\ProductionService;

class RstProductionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public $service;

    public function __construct(ProductionService $purchaseService)
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
            'material_id'       => 'required',
            'item_id'           => 'required',
            'material_quantity' => 'required',
            'item_quantity'     => 'required',
        ];
    }



    public function store()
    {
        DB::transaction(function () {

            $this->service->storeProduction();



            $this->service->storeMaterialDetails();


            $this->service->storeProductDetails();



            // $this->service->updateTransaction();



            // $this->service->updateSuplierBalance($this->request);
        });

        return $this->service->production;
    }
}
