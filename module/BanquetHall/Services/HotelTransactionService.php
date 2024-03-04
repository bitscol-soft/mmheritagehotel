<?php

namespace Module\BanquetHall\Services;

use Module\Account\Models\Account;
use Module\Account\Models\AccountGroup;
use Module\Hotel\Models\InvoiceGenerate;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\HotelTransactionLedger;
use Module\Account\Services\AccountTransactionService;
use Module\HotelService\Services\HotelTransactionLedgerService;

class HotelTransactionService
{

    private $transactionService;



    // CONSTRUCT METHOD
    public function __construct()
    {
        $this->transactionService   = new AccountTransactionService();
    }



    //-----------------------------------------------------------------//
    //                      STORE TRANSACTION METHOD                   //
    //-----------------------------------------------------------------//
    public function storeTransaction(
        $model,
        $source_id,
        $source_type,
        $account_type_id,
        $total_amount,
        $discount,
        $collection,
        $vat_amount,
        $service_amount,
        $booking_id,
        $invoice = null,
        $date = null,
        $remark = null,
        $extra_charge = 0,
        $currency_type = 141,
        $paidDueAmount = null
    ) {


        setSourceType($source_type);


        //--------------------- UPDATE TRANSACTION ---------------------//
        if ($hotel_transaction = HotelTransection::where([
                'source_id'         => $source_id,
                'source_type'       => $source_type,
            ])->first()) {
            $hotel_transaction->update([
                // 'invoice_no'        => $invoice,
                'date'              => $date ?? date('Y-m-d'),
                'account_type_id'   => $account_type_id,
                'total_amount'      => convertToBDTCurrency($total_amount) ?? $hotel_transaction->$total_amount,
                'collection'        => $collection ?? 0,
                'discount'          => $discount ?? 0,
                'vat_amount'        => $vat_amount,
                'service_charge'    => $service_amount,
                'change_amount'     => convertToBDTCurrency(request('change_amount')),
            ]);

        }



        //--------------------- CREATE TRANSACTION ---------------------//
        else {

            if ($currency_type == 141) {
                $collection = convertToBDTCurrency($collection, 141);
            }
            if ($currency_type == 96) {
                $collection = convertToBDTCurrency($collection, 96);
            }


            $hotel_transaction = HotelTransection::create([
                'source_id'             => $source_id,
                'source_type'           => $source_type,
                'invoice_no'            => $invoice,
                'date'                  => fdate($date, 'Y-m-d') ?? today_from_system(),
                'account_type_id'       => $account_type_id,
                'payment_currency_id'   => setting('root_currency'),
                'currency_conversion_id'=> getCurrencyConversion(setting('root_currency')),
                'total_amount'          => convertToBDTCurrency($total_amount),
                'collection'            => $collection,
                'discount'              => convertToBDTCurrency($discount ?? 0),
                'vat_amount'            => convertToBDTCurrency($vat_amount),
                'service_charge'        => convertToBDTCurrency($service_amount),
                'extra_charge'          => convertToBDTCurrency($extra_charge),
                'change_amount'         => convertToBDTCurrency(request('change_amount')),
                'booking_id'            => $booking_id ?? null,
                'datetime'              => fdate(now(),'Y-m-d H:i:s'),
            ]);
        }


        // DELETING ACC ACCOUNT TRANSACTION WHILE BOOKING EDIT
        if ( request('is_from_booking_edit') == 1 || request('from_booking_migration') == 1) {

            if ($model->transactions != null) {
                $model->transactions()->delete();
            }
        }

        $description        = null;

        if (!setting('account_transaction_when_night_audit')){


            $sale_account       = $this->transactionService->getSaleAccount();

            if($source_type == 'Booking' || $source_type == 'Booking Adjust' || $source_type == 'Hotel Service Sale'){

                $sale_account       = $this->transactionService->getServiceAccount();
            }

            $balance_type       = optional(AccountGroup::find(1))->balance_type;

            $module_account     = Account::where('name', $source_type)
                                        ->where('account_group_id', 1)
                                        ->where('account_control_id', 1)
                                        ->where('account_subsidiary_id', 8)
                                        ->where('balance_type', $balance_type)
                                        ->first();




            // ACC ACCOUNT SALE TRANSACTION
            $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $sale_account,    0,                                           convertToBDTCurrency($total_amount),  $date ?? date('Y-m-d'),   'credit',  'Sale',         $description);
            //  Payable Amount



            // MODULE TRANSACTION / CUSTOMER DUE TRANSACTION IF HAVE
            $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $module_account,  convertToBDTCurrency($total_amount),         $collection,                          $date ?? date('Y-m-d'),   'debit',   'Customer Due', $description);
            //  Due Amount

        }


        //----------- EXECUTE "HOTEL TRANSACTION LEDGER" & "ACC ACCOUNT TRANSACTION" WHEN COLLECTION IS MORE THAN 0 -----------//
        // if ($collection > 0) { // DISABLE THIS CONDITION FOR ZERO TRANSACTION => DISCUSSED WITH MASUD


            //--------------------- FOR MULTIPLE PAYMENT METHOD ---------------------//
            if (request('is_multiple_way') == 1) {

                foreach (request('modal_account_types') ?? [] as $key => $id) {

                    (new HotelTransactionLedgerService())->createOrUpdateTransaction(
                        request('transaction_ledger_ids')[$key] ?? null,
                        $source_id,
                        $source_type,
                        request('modal_account_types')[$key],
                        $hotel_transaction->id,
                        request('modal_account_paid_amounts')[$key] ?? $collection,
                        $date,
                        $remark
                    );

                    if (!setting('account_transaction_when_night_audit')){

                        $cashAccount        = getPaymentTypeAccount(request('modal_account_types')[$key]);

                        // MULTIPLE PAYMENT METHOD TRANSACTION IF SELECT MULTIPLE TYPE
                        $this->transactionService->storeTransaction($model->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $cashAccount,     request('modal_account_paid_amounts')[$key], 0,                                    $date ?? date('Y-m-d'),   'debit',   'Payment',      null);    //  Paid Amount

                    }
                }

                if (count(request('transaction_ledger_ids') ?? []) > 0) {
                    HotelTransactionLedger::where('hotel_transaction_id', $hotel_transaction->id)->whereNotIn('id', request('transaction_ledger_ids') ?? [])->delete();
                }

            }

            //---------------------- FOR SINGLE PAYMENT METHOD ----------------------//
            else{

                (new HotelTransactionLedgerService())->storeTransaction(
                    $source_id,
                    $source_type,
                    $account_type_id,
                    $hotel_transaction->id,
                    $paidDueAmount != null ? $paidDueAmount : $collection,
                    $date,
                    $remark
                );


                if (request('from_booking_due_collection') == 1) {
                    $account_type_id = request('payment_type');
                }

                $cashAccount            = $account_type_id != null ? getPaymentTypeAccount($account_type_id) : Account::find(55);


                // CHECK ACC ACCOUNT TRANSACTION ALREADY EXIST OR NOT FOR DUE COLLECTION - BY PAYMENT METHOD
                if (request('is_from_due_collection') == 1 || request('from_booking_due_collection') == 1 ) {

                    $transection = $model->transactions()->where([
                        'invoice_no'            => $model->invoice_no ?? $model->booking_number,
                        'transaction_item_type' => 'Payment',
                        'balance_type'          => 'debit',
                        'account_id'            => $cashAccount->id,
                    ])->first();

                    if ($transection != null) {
                        $paidDueAmount = $transection->debit_amount + $paidDueAmount; // IF EXIST THEN UPDATE DEBIT AMOUNT
                    }

                // }

                if (!setting('account_transaction_when_night_audit')){
                    // SINGLE PAYMENT METHOD TRANSACTION IF SELECT ONLY ONE PAYMENT TYPE
                    $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $cashAccount,   request('is_from_due_collection') == 1 || $paidDueAmount > 0 ? $paidDueAmount : $collection,                                 0,                                    $date ?? date('Y-m-d'),   'debit',   'Payment',      null);
                    //  Paid Amount
                }
            }

        }


    }







    // COLLECT TRANSACTION DUE AMOUNT
    public function collectTransactionDueAmount(
        $source_id,
        $source_type,
        $account_type_id,
        $total_amount,
        $discount,
        $collection,
        $booking_id,
        $invoice = null,
        $date = null
    ) {

        if ($hotel_transaction = HotelTransection::where([
            'source_id'         => $source_id,
            'source_type'       => $source_type,
        ])->first()) {
            $hotel_transaction->update([
                'invoice_no'        => $invoice,
                'date'              => $date ?? today_from_system(),
                'account_type_id'   => $account_type_id,
                'collection'        => convertToBDTCurrency($collection ?? 0),
                'discount'          => convertToBDTCurrency($discount ?? 0),
            ]);
        } else {

            $hotel_transaction = HotelTransection::create([
                'source_id'         => $source_id,
                'source_type'       => $source_type,
                'invoice_no'        => $invoice,
                'date'              => $date ?? today_from_system(),
                'account_type_id'   => $account_type_id,
                'total_amount'      => convertToBDTCurrency($total_amount),
                'collection'        => convertToBDTCurrency($collection ?? 0),
                'discount'          => convertToBDTCurrency($discount ?? 0),
                'booking_id'        => $booking_id ?? null
            ]);
        }

        // if ($collection != 0) { // DISABLE THIS CONDITION FOR ZERO TRANSACTION => DISCUSSED WITH MASUD
            (new HotelTransactionLedgerService())->storeTransaction(
                $source_id,
                $source_type,
                $account_type_id,
                $hotel_transaction->id,
                $collection,
                $date
            );
        // }
    }





    // SET SALE INVOICE NO METHOD
    public function getInvoiceNo(): string
    {
        $year = date('Y');
        $month = date('m');
        $date  = $year . '-' . $month;

        $nextId = optional(InvoiceGenerate::query()
            ->where('type', 'Service Sale')
            ->where('year', $date)
            ->first())->next_id;

        if ($nextId == null)

            $nextId = InvoiceGenerate::query()
                ->create([
                    'type' => 'Service Sale',
                    'year' => $date,
                    'next_id' => 1,
                ])->next_id;

        return $date
            . '-'
            . str_pad($nextId, 4, "0", STR_PAD_LEFT);
    }




    // SET NEXT INVOICE NO METHOD
    public function setNextInvoiceNo($type, $time)
    {
        $invoice_no = InvoiceGenerate::query()
            ->firstOrCreate([
                'type' => $type,
                'year' => $time,
            ]);

        $invoice_no->increment('next_id');
        $invoice_no->save();
    }


}
