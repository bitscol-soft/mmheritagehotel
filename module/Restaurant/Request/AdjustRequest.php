<?php

namespace Module\Restaurant\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Module\Restaurant\Services\StockAdjustService;

class AdjustRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public $service;

    public function __construct(StockAdjustService $StockAdjustService)
    {
        $this->service = $StockAdjustService;
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
            'adjustment_id'              => 'required',
            'product_id'                 => 'required',
            'adjustment_detail_id'       => 'required',
        ];
    }



    public function datastore()
    {
        DB::transaction(function () {

            $this->service->UpdateAdjustment();



            // $this->service->storeAdjustmentDetails();



            // $this->service->updateTransaction();



            // $this->service->updateSuplierBalance($this->request);
        });

        return $this->service->adjustment;
    }
}
