<?php

namespace Module\GeneralStore\Services\Export;

use App\Models\Company;
use Module\HRM\Models\Department;
use Module\GeneralStore\Models\GoodsRequisition;
use Module\GeneralStore\Models\Item;
use Module\GeneralStore\Models\Purchase;
use Module\GeneralStore\Models\PurchaseReceive;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// class ExportDataAsCSV implements FromView, ShouldAutoSize, WithEvents
class GSExportData
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public $request = [];
    function __construct($request)
    {
        $this->request = $request;
    }

    // Set company name for list
    public function getCompanyName()
    {
        if ($this->request->filled('company_id')) {
            return Company::where('id', $this->request->company_id)->first()->name;
        } else {
            return "All Companies";
        }
    }

    public function getHeadingName()
    {
        return $this->request->model;
    }




    public function getExportableData()
    {
        $request = $this->request;
        if ($request->model == "Item List") {
            return $this->itemList($request);
        } else if ($request->model == "Purchase List") {
            return $this->purchaseList($request);
        } else if ($request->model == "GRN List") {
            return $this->GRNList($request);
        } else if ($request->model == "Goods Requisition List") {
            return $this->goodsRequisitionList($request);
        } else if ($request->model == "GIN List") {
            return $this->GINList($request);
        } else if ($request->model == "Item Ledger") {
            return $this->itemLedger($request);
        }
    }


    // #########################  get header name
    public function getHeadersName()
    {
        $request = $this->request;
        if ($request->model == "Item List") {
            return ["Company", "Item Name", "Unit", "Opening Quantity", "Current Stock", "Created By", "Updated By"];
        } else if ($request->model == "Purchase List") {
            return ["Date", "Purchase Number", "Reference", "Company", "Required Quantity", "Received Quantity", "Created By", "Updated By"];
        } else if ($request->model == "GRN List") {
            return ["Date", "GRN No.", "Date", "Purchase Number", "Company", "Required Quantity", "Received Quantity", "Challan Number", "Received By"];
        } else if ($request->model == "Goods Requisition List") {
            return ["Date", "Requisition No", "Company", "Department", "Issue Date", "GIN Number", "Reference", "Total Qty", "Created By", "Updated By"];
        } else if ($request->model == "GIN List") {
            return ["Issue Date", "GIN Number", "Date", "Requisition No", "Company", "Department", "Reference", "Total Qty", "Received By"];
        } else if ($request->model == "Item Ledger") {
            return ["Date", "Item", "Unit", "Company", "Stock In Hand"];
        }
    }



    //#########################################         methods for individual models        ###########################

    public function itemList()
    {
        $mydata = [];
        $request = $this->request;

        $items     = Item::with('company', 'item_unit', 'created_user', 'updated_user')->items();
        if ($request->filled('company_id')) {
            $items->where('company_id', $request->company_id);
        }

        if ($request->filled('name')) {
            $items->where('name', $request->name);
        }

        if ($request->filled('from_date')) {
            $items->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $items->whereDate('created_at', '<=', $request->to_date);
        }

        $items = $items->orderByDesc('id')->get();

        foreach ($items as $key => $item) {
            $mydata['data_1'][] = $item->company->name;
            $mydata['data_2'][] = $item->name;
            $mydata['data_3'][] = $item->item_unit->name;
            $mydata['data_4'][] = $item->opening_balance;
            $mydata['data_5'][] = $item->current_stock;
            $mydata['data_6'][] = '<p>' . $item->created_user->name . '</p><p style="margin-top:-10px !important; font-size: 10px !important;">' . Carbon::parse($item->created_at)->format('Y-m-d') . '</p>';
            $mydata['data_7'][] = '<p>' . $item->updated_user->name . '</p><p style="margin-top:-10px !important; font-size: 10px !important;">' . Carbon::parse($item->updated_at)->format('Y-m-d') . '</p>';
        }

        return $mydata;
    }

    public function purchaseList()
    {
        $mydata = [];
        $request = $this->request;

        $purchases = Purchase::with(['company', 'created_user', 'updated_user'])
            ->with(['purchase_details' => function ($q) {
                $q->select(DB::raw('sum(quantity) as totalQty, purchase_id'))->groupBy('purchase_id');
            }])
            ->with(['purchase_receives' => function ($q) {
                $q->select(DB::raw('sum(quantity) as totalQty, purchase_id'))->groupBy('purchase_id');
            }]);
        if ($request->filled('company_id')) {
            $purchases->where('company_id', $request->company_id);
        } else {
            $purchases->whereIn('company_id', Company::userCompanies()->keys());
        }
        if ($request->filled('from_date')) {
            $purchases->whereDate('purchase_date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $purchases->whereDate('purchase_date', '<=', Carbon::parse($request->to_date));
        }
        $purchases = $purchases->orderByDesc('id')->get();
        $remaining_receive = 0;

        foreach ($purchases as $key => $purchase) {

            $mydata['data_1'][] = $purchase->purchase_date;
            $mydata['data_2'][] = $purchase->form_number;
            $mydata['data_3'][] = $purchase->purchase_reference;
            $mydata['data_4'][] = $purchase->company->name;
            $mydata['data_5'][] = optional($purchase->purchase_details->first())->totalQty;
            $mydata['data_6'][] = optional($purchase->purchase_receives->first())->totalQty;
            $mydata['data_7'][] = '<p>' . $purchase->created_user->name . '</p><p style="margin-top:-10px !important; font-size: 10px !important;">' . Carbon::parse($purchase->created_at)->format('Y-m-d') . '</p>';
            $mydata['data_8'][] = '<p>' . $purchase->updated_user->name . '</p><p style="margin-top:-10px !important; font-size: 10px !important;">' . Carbon::parse($purchase->updated_at)->format('Y-m-d') . '</p>';
        }

        return $mydata;
    }

    public function GRNList()
    {
        $mydata = [];
        $request = $this->request;

        $purchase_receives = PurchaseReceive::with('purchase_receive_details.supplier', 'purchase', 'company')->orderByDesc('id');
        if ($request->filled('company_id')) {
            $purchase_receives->where('company_id', $request->company_id);
        } else {
            $purchase_receives->whereIn('company_id', Company::userCompanies()->keys());
        }
        if ($request->filled('from_date')) {
            $purchase_receives->whereDate('purchase_receive_date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $purchase_receives->whereDate('purchase_receive_date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->filled('grn_no')) {
            $purchase_receives->where('form_number', $request->grn_no);
        }

        if ($request->filled('purchase_number')) {
            $purchase_receives->whereHas('purchase', function ($q) use ($request) {
                $q->where('form_number', $request->purchase_number);
            });
        }

        foreach ($purchase_receives->get() as $key => $purchase_receive) {
            $total_received_quantity = 0;
            $total_required_quantity = 0;
            foreach ($purchase_receive->purchase_receive_details as $i => $purchase) {
                $total_received_quantity += $purchase->quantity;
                $total_required_quantity += $purchase_receive->purchase->purchase_details[$i]->quantity;
            }

            $mydata['data_1'][] = $purchase_receive->purchase_receive_date;
            $mydata['data_2'][] = $purchase_receive->form_number;
            $mydata['data_3'][] = $purchase_receive->purchase->purchase_date;
            $mydata['data_4'][] = $purchase_receive->purchase->form_number;
            $mydata['data_5'][] = $purchase_receive->company->name;
            $mydata['data_6'][] = $total_required_quantity;
            $mydata['data_7'][] = $total_received_quantity;
            $mydata['data_8'][] = $purchase_receive->purchase_challan_number;
            $mydata['data_9'][] = '<p>' . $purchase_receive->updated_user->name . '</p><p style="margin-top:-10px !important; font-size: 10px !important;">' . Carbon::parse($purchase_receive->updated_at)->format('Y-m-d') . '</p>';
        }

        return $mydata;
    }

    public function goodsRequisitionList()
    {
        $mydata = [];
        $request = $this->request;

        $relation = ['company', 'department', 'goods_requisition_details.item.item_unit', 'goods_requisition_details.item', 'created_user', 'updated_user'];
        $goods_requisitions = GoodsRequisition::with($relation)
            ->orderByDesc('id')
            ->whereIn('department_id', Department::userDepartments()->keys());

        if ($request->filled('company_id')) {
            $goods_requisitions->where('company_id', $request->company_id);
        } else {
            $goods_requisitions->whereIn('company_id', Company::userCompanies()->keys());
        }
        if ($request->filled('from_date')) {
            $goods_requisitions->whereDate('goods_requisition_date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $goods_requisitions->whereDate('goods_requisition_date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->filled('requisition_number')) {
            $goods_requisitions->where('form_number', $request->requisition_number);
        }
        if ($request->filled('gin_number')) {
            $goods_requisitions->where('issue_number', $request->gin_number);
        }

        foreach ($goods_requisitions->get() as $key => $goods_requisition) {
            $mydata['data_1'][] = $goods_requisition->goods_requisition_date;
            $mydata['data_2'][] = $goods_requisition->form_number;
            $mydata['data_3'][] = $goods_requisition->company->name;
            $mydata['data_4'][] = $goods_requisition->department->name;
            $mydata['data_5'][] = Carbon::parse($goods_requisition->issue_date)->format('Y-m-d');
            $mydata['data_6'][] = $goods_requisition->issue_number;
            $mydata['data_7'][] = $goods_requisition->goods_requisition_reference;
            $mydata['data_8'][] = $goods_requisition->goods_requisition_details->sum('quantity');
            $mydata['data_9'][] = '<p>' . $goods_requisition->created_user->name . '</p><p style="margin-top:-10px !important; font-size: 10px !important;">' . Carbon::parse($goods_requisition->created_at)->format('Y-m-d') . '</p>';
            $mydata['data_10'][] = '<p>' . $goods_requisition->updated_user->name . '</p><p style="margin-top:-10px !important; font-size: 10px !important;">' . Carbon::parse($goods_requisition->updated_at)->format('Y-m-d') . '</p>';
        }
        return $mydata;
    }


    public function GINList()
    {
        $mydata = [];
        $request = $this->request;

        $goods_requisitions = GoodsRequisition::with('company', 'department', 'goods_requisition_details.item.item_unit', 'goods_requisition_details.item')
            ->orderByDesc('id')
            ->whereIn('department_id', Department::userDepartments()->keys());

        if ($request->filled('company_id')) {
            $goods_requisitions->where('company_id', $request->company_id);
        } else {
            $goods_requisitions->whereIn('company_id', Company::userCompanies()->keys());
        }
        if ($request->filled('from_date')) {
            $goods_requisitions->whereDate('goods_requisition_date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $goods_requisitions->whereDate('goods_requisition_date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->filled('requisition_number')) {
            $goods_requisitions->where('form_number', $request->requisition_number);
        }
        if ($request->filled('gin_number')) {
            $goods_requisitions->where('issue_number', $request->gin_number);
        }
        $goods_requisitions->where('is_approved', 1)->orderByDesc('id');

        foreach ($goods_requisitions->get() as $key => $goods_requisition) {
            $mydata['data_1'][] = Carbon::parse($goods_requisition->issue_date)->format('Y-m-d');
            $mydata['data_2'][] = $goods_requisition->issue_number;
            $mydata['data_3'][] = $goods_requisition->goods_requisition_date;
            $mydata['data_4'][] = $goods_requisition->form_number;
            $mydata['data_5'][] = $goods_requisition->company->name;
            $mydata['data_6'][] = $goods_requisition->department->name;
            $mydata['data_7'][] = $goods_requisition->goods_requisition_reference;
            $mydata['data_8'][] = $goods_requisition->goods_requisition_details->sum('quantity');
            $mydata['data_9'][] = '<p>' . $goods_requisition->updated_user->name . '</p><p style="margin-top:-10px !important; font-size: 10px !important;">' . Carbon::parse($goods_requisition->updated_at)->format('Y-m-d') . '</p>';
        }
        return $mydata;
    }



    public function itemLedger()
    {
        $mydata = [];
        $request = $this->request;

        $data['item_stocks'] = Item::with('item_unit');

        if ($request->filled('item_id')) {
            $data['item_stocks']->where('id', $request->item_id);
        }
        if ($request->filled('from_date')) {
            $data['item_stocks']->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $data['item_stocks']->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }

        if ($request->filled('company_id')) {
            $data['item_stocks']->where('company_id', $request->company_id);
        }

        foreach ($data['item_stocks']->get() as $key => $item) {
            $mydata['data_1'][] = Carbon::parse($item->created_at)->format('Y-m-d');
            $mydata['data_2'][] = $item->name;
            $mydata['data_3'][] = $item->item_unit->name;
            $mydata['data_4'][] = $item->company->name;
            $mydata['data_5'][] = $item->current_stock;
        }
        return $mydata;
    }
}
