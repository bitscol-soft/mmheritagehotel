<?php

namespace App\Traits;

use App\Models\Comm\Pi;
use App\Models\Company;
use App\Models\Payment\CashPayment;
use App\Models\Payment\CashPaymentRef;
use Module\GeneralStore\Models\Purchase;
use Module\Restaurant\Models\RstPurchase;
use Module\GeneralStore\Models\GoodsRequisition;
use Module\Garments\Models\Inventory\DyeingOrder;
use Module\Garments\Models\Inventory\ArpWorkOrder;
use Module\Garments\Models\Merchandising\Setup\Buyer;
use Module\Garments\Models\Inventory\KnitYarnTransfer;
use Module\Garments\Models\Inventory\SweaterYarnWorkOrder;
use Module\Garments\Models\Inventory\WovenFabricWorkOrder;

trait FormNumber
{


    // ##########################      GENERAL STORE   #################################
    // purchase number
    public function purchase_number($company_id)
    {
        $count_purchase_number = Purchase::where('company_id', $company_id)->where('form_number', '>', 0)->count();
        $company_code          = Company::where('id', $company_id)->pluck('code')->first();
        return '2' . $company_code .  date('y') . (10001 + $count_purchase_number);
    }

    public function res_purchase_number($company_id)
    {
        $count_purchase_number = RstPurchase::where('company_id', $company_id)->where('form_number', '>', 0)->count();
        $company_code          = Company::where('id', $company_id)->pluck('code')->first();
        return '2' . $company_code .  date('y') . (10001 + $count_purchase_number);
    }


    // purchase receive number / grn number
    public function purchase_receive_number($purchase)
    {
        $purchase_receive_count  = $purchase->purchaseReceiveCount();
        $purchase_receive_number = $purchase->form_number;
        $purchase_receive_number[0] = 3;
        if ($purchase_receive_count >= 1) {
            return  $purchase_receive_number . '.' . $purchase_receive_count;
        } else {
            return $purchase_receive_number;
        }
    }


    // goods requisition number
    public function goods_requisition_number($company_id)  // general store
    {
        $count_requisition_number = optional(GoodsRequisition::orderByDesc('id')->first())->id ?? 0;
        $company_code             = Company::where('id', $company_id)->pluck('code')->first();
        return '4' . $company_code .  date('y') . (10001 + $count_requisition_number);
    }


    // gin / issue number
    public function gin_issue_number($goodsRequisition)    // general store goods issue number
    {
        $gin_issue_number = $goodsRequisition->form_number;
        $gin_issue_number[0] = 5;
        return $gin_issue_number;
    }



    // ##########################      INVENTORY   #################################
    public function workOrderNumber($companyId, $pi)  // sweater yarn
    {
        $selectedPi       = DyeingOrder::orderByDesc('id')->where('supplier_pi_id', $pi)->get();
        if (count($selectedPi) == 0) {
            $companyCode  = Company::find($companyId)->code;
            $year         = fdate(now(), 'y');
            $latestNumber = DyeingOrder::all();
            $countNull = $latestNumber->where('supplier_pi_id', Null)->count();
            $countPi   = $latestNumber->where('supplier_pi_id', '!=', Null)->unique('supplier_pi_id')->count();
            return '2' . $companyCode . $year . ($countNull + $countPi + 500001);
        } else if (count($selectedPi) == 1) {
            return $selectedPi->first()->work_order_number . '.1';
        } else {
            $test = $selectedPi->first()->work_order_number;
            $countPi = $selectedPi->count();
            $data = explode(".", $test);
            return $data[0]. '.' . ((int)$data[1] + 1);
        }
    }

    public function arpWorkOrderNumber($company_id, $year)
    {
        $company_code           = Company::find($company_id)->code;
        $arp_work_order         = ArpWorkOrder::where('company_id', $company_id)->where('year', $year)->latest()->first();
        $arp_work_order_count   = 0;

        if ($arp_work_order) {
            $str = $arp_work_order->work_order_number;
            $arp_work_order_count = (int)substr($str, -4);
        }

        return ("2" . $company_code . substr($year, -2) . (20001 + $arp_work_order_count));
    }

    public function sweaterYarnWorkOrderNumber($company_id, $year)
    {
        $company_code       = Company::find($company_id)->code;
        $work_order         = SweaterYarnWorkOrder::where('company_id', $company_id)->where('date', 'like', $year.'%')->latest()->first();
        $work_order_count   = 0;

        if ($work_order) {
            $str = $work_order->wo_ref;
            $work_order_count = (int)substr($str, -4);
        }

        return ("2" . $company_code . substr($year, -2) . (30001 + $work_order_count));
    }



    public function getPiNumber($request)
    {
        $piCount        = Pi::where('group_id', auth()->user()->company->group_id)->whereYear('date', date('Y'))->count();
        $piCounter      = str_pad(($piCount+1), 4, 00, STR_PAD_LEFT);
        $buyerPiCount   = Pi::where('buyer_id', $request->buyer)->count();
        $buyerPiCounter = str_pad(($buyerPiCount+1), 4, 0, STR_PAD_LEFT);
        $buyer          = Buyer::where('id', $request->buyer)->first(['name']);
        $buyer3chr      = substr($buyer->name, 0,3);
        $piNumber       = 'PI'.$piCounter.'/'.date('Y').'/'.$buyer3chr.'/'.$buyerPiCounter;

        return $piNumber;
    }

    // payment

    public function arpPaymentRef($company_id): string
    {
        $year = date('y');
        $company = Company::query()->find($company_id);

        $ref = CashPaymentRef::query()
            ->where('type', 'ARP')
            ->where('company_id', $company->id)
            ->where('year', $year)
            ->select('id', 'count')
            ->first();

        $count = optional($ref)->count + 1;

        CashPaymentRef::query()->updateOrCreate([
            'type' => 'ARP',
            'company_id' => $company->id,
            'year' => $year,
        ], [
            'count' => $count
        ]);

        return '6' . $company->code . $year . (20000 + $count);
    }

    public function sweaterYarnPaymentRef($company_id): string
    {
        $year = date('y');
        $company = Company::query()->find($company_id);

        $ref = CashPaymentRef::query()
            ->where('type', 'SweaterYarn')
            ->where('company_id', $company->id)
            ->where('year', $year)
            ->select('id', 'count')
            ->first();

        $count = optional($ref)->count + 1;

        CashPaymentRef::query()->updateOrCreate([
            'type' => 'SweaterYarn',
            'company_id' => $company->id,
            'year' => $year,
        ], [
            'count' => $count
        ]);

        return '6' . $company->code . $year . (30000 + $count);
    }

    public function subcontractPaymentRef($company_id): string
    {
        $year = date('y');
        $company = Company::query()->find($company_id);

        $ref = CashPaymentRef::query()
            ->where('type', 'Subcontract')
            ->where('company_id', $company->id)
            ->where('year', $year)
            ->select('id', 'count')
            ->first();

        $count = optional($ref)->count + 1;

        CashPaymentRef::query()->updateOrCreate([
            'type' => 'Subcontract',
            'company_id' => $company->id,
            'year' => $year,
        ], [
            'count' => $count
        ]);

        return '6' . $company->code . $year . (40000 + $count);
    }

    public function knitYarnPaymentRef($company_id): string
    {
        $year = date('y');
        $company = Company::query()->find($company_id);

        $ref = CashPaymentRef::query()
            ->where('type', 'KnitYarn')
            ->where('company_id', $company->id)
            ->where('year', $year)
            ->select('id', 'count')
            ->first();

        $count = optional($ref)->count + 1;

        CashPaymentRef::query()->updateOrCreate([
            'type' => 'KnitYarn',
            'company_id' => $company->id,
            'year' => $year,
        ], [
            'count' => $count
        ]);

        return '6' . $company->code . $year . (50000 + $count);
    }

    public function wovenFabricPaymentRef($company_id): string
    {
        $year = date('y');
        $company = Company::query()->find($company_id);

        $ref = CashPaymentRef::query()
            ->where('type', 'WovenFabric')
            ->where('company_id', $company->id)
            ->where('year', $year)
            ->select('id', 'count')
            ->first();

        $count = optional($ref)->count + 1;

        CashPaymentRef::query()->updateOrCreate([
            'type' => 'WovenFabric',
            'company_id' => $company->id,
            'year' => $year,
        ], [
            'count' => $count
        ]);

        return '6' . $company->code . $year . (60000 + $count);
    }


    // Store Cash

    public function getWovenFabricWorkOrderRef($company_id)
    {
        $year               = fdate(today(), 'Y');
        $company_code       = Company::find($company_id)->code;
        $work_order         = WovenFabricWorkOrder::where('company_id', $company_id)->where('year', $year)->orderByDesc('id')->first();
        $work_order_count   = 0;

        if ($work_order) {
            $str                = $work_order->ref;
            $work_order_count   = (int)substr($str, -4);
        }

        return ("2" . $company_code . substr($year, -2) . (60001 + $work_order_count));
    }

    public function wovenFabricIssueRef($company_id): string
    {
        $year = date('y');
        $company = Company::query()->find($company_id);

        $ref = CashPaymentRef::query()
            ->where('type', 'WovenFabricIssue')
            ->where('company_id', $company->id)
            ->where('year', $year)
            ->select('id', 'count')
            ->first();

        $count = optional($ref)->count + 1;

        CashPaymentRef::query()->updateOrCreate([
            'type' => 'WovenFabricIssue',
            'company_id' => $company->id,
            'year' => $year,
        ], [
            'count' => $count
        ]);

        return '4' . $company->code . $year . (60000 + $count);
    }

    public function knittingDyeingIssueRef($company_id): string
    {
        $year = date('y');
        $company = Company::query()->find($company_id);

        $ref = CashPaymentRef::query()
            ->where('type', 'KnittingDyeingIssue')
            ->where('company_id', $company->id)
            ->where('year', $year)
            ->select('id', 'count')
            ->first();

        $count = optional($ref)->count + 1;

        CashPaymentRef::query()->updateOrCreate([
            'type' => 'KnittingDyeingIssue',
            'company_id' => $company->id,
            'year' => $year,
        ], [
            'count' => $count
        ]);

        return '4' . $company->code . $year . (50000 + $count);
    }

    public function knitYarnTransferRef(): string
    {
        $lastTransfer  = KnitYarnTransfer::query()->where('ref', 'like', '%-'. date('y').'%')->orderByDesc('ref')->first();
        if ($lastTransfer) {
            $yearCount = str_pad(substr($lastTransfer->ref, 5, 4) + 1, 4, 0, STR_PAD_LEFT);
        } else {
            $yearCount = '0001';
        }
        $year       = date('y');

        return "T-" . $year . '5' . $yearCount;
    }

    public function knittingDyeingProgramNo($year = null): string
    {
        $year = $year ?? date('Y');
        $year = substr($year, 2);

        $ref = CashPaymentRef::query()
            ->where('type', 'K&DProgram')
            ->where('year', $year)
            ->select('id', 'count')
            ->first();

        $count = optional($ref)->count + 1;

        return 'K&D-' . str_pad($count,4,'0',STR_PAD_LEFT) . '/' . $year;
    }

    public function saveKnittingDyeingProgramNo($ref)
    {
        preg_match('/K&D-(\d*)\/(\d*)/', $ref, $matches);

        CashPaymentRef::query()->updateOrCreate([
            'type' => 'K&DProgram',
            'year' => $matches[2],
        ], [
            'count' => (int)$matches[1]
        ]);
    }

    public function knittingDyeingGoodsReceiveRef($issue_id)
    {
        $count = KnittingDyeingGoodsReceive::query()->where('issue_id', $issue_id)->count();
        $count = $count > 0 ? ($count + 1) : 0;
        return 'RCV-' . date('y') . (500000 + $count);
    }
}
