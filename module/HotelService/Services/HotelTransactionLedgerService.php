<?php

namespace Module\HotelService\Services;

use Module\Hotel\Models\HotelCustomerLedger;
use Module\Hotel\Models\HotelTransactionLedger;

class HotelTransactionLedgerService
{


    public function storeTransaction(

        $source_id,
        $source_type,
        $payment_type,
        $hotel_transaction_id,
        $amount,
        $date,
        $remark = null
    ) {

        $in = $out = $amount;

        $out = $amount < 0 ? ($in = 0 ) : $out = 0;

        HotelTransactionLedger::create([
            'source_id'             => $source_id,
            'source_type'           => $source_type,
            'payment_type'          => $payment_type,
            'date'                  => $date ?? date('Y-m-d'),
            'hotel_transaction_id'  => $hotel_transaction_id,
            'in'                    => $in,
            'out'                   => $out,
            'remarks'               => $remark,
            'datetime'              => fdate(now(),'Y-m-d H:i:s'),

        ]);


    }




    public function createOrUpdateTransaction(
        $id,
        $source_id,
        $source_type,
        $payment_type,
        $hotel_transaction_id,
        $amount,
        $date,
        $remark = null
    ) {
        $in = $out = $amount;

        $out = $amount < 0 ? ($in = 0 ) : $out = 0;

        HotelTransactionLedger::updateOrCreate([
            'id'                    => $id,
        ],[
            'source_id'             => $source_id,
            'source_type'           => $source_type,
            'payment_type'          => $payment_type,
            'date'                  => $date ?? date('Y-m-d'),
            'hotel_transaction_id'  => $hotel_transaction_id,
            'in'                    => $in,
            'out'                   => $out,
            'remarks'               => $remark,
            'datetime'              => fdate(now(),'Y-m-d H:i:s'),
        ]);


    }


    public function storeCustomerLedger( $hotel_guest_id, $date = null, $debit, $credit ) {
        dd('storeCustomerLedger');

        HotelCustomerLedger::create([
            'hotel_guest_id'        => $hotel_guest_id,
            'date'                  => $date ?? date('Y-m-d'),
            'debit'                 => $debit,
            'credit'                => $credit,
        ]);

    }
}
