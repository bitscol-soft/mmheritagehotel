<?php

namespace App\Traits;

use Module\GeneralStore\Models\Supplier;
use Illuminate\Support\Collection;

trait CashPaymentCommons
{
    private function grnCashSuppliers($paymentType): Collection
    {
        return Supplier::query()
            ->when($paymentType == 'ARP', function ($q) {
                $q->whereHas('arpWorkOrders', function ($r) {
                    $r->where('payment_mode', 'Cash')->has('arpGoodReceives');
                });
            })
            ->when($paymentType == 'SweaterYarn', function ($q) {
                $q->whereHas('sweaterYarnWorkOrders', function ($r) {
                    $r->where('payment_mode', 'Cash')->has('work_order_receives');
                });
            })
            ->when($paymentType == 'KnitYarn', function ($q) {
                $q->whereHas('knitYarnWorkOrders', function ($r) {
                    $r->where('payment_mode', 'Cash')->has('goodReceives');
                });
            })
            ->when($paymentType == 'Subcontract', function ($q) {
                $q->whereHas('subcontractWorkOrders', function ($r) {
                    $r->where('payment_mode', 'Cash')->has('work_order_receives');
                });
            })
            ->when($paymentType == 'WovenFabric', function ($q) {
                $q->whereHas('wovenFabricWorkOrders', function ($r) {
                    $r->where('payment_mode', 'Cash')->has('goodReceives');
                });
            })
            ->pluck('name', 'id');
    }

    private function paymentSuppliers($paymentType): Collection
    {
        return Supplier::query()
            ->whereHas('cashPayments', function ($r) use ($paymentType) {
                $r->where('type', $paymentType);
            })
            ->pluck('name', 'id');
    }
}
