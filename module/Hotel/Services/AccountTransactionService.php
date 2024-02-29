<?php

namespace Module\Hotel\Services;

use Module\Account\Models\Account;
use Module\Account\Models\Customer;
use Module\Account\Services\AccountTransactionService as AccountTransaction;

class AccountTransactionService
{

    public $account_name;
    public $opening;
    public $transactionService;
    public $booking;
    public $customer;



    public function __construct($name, $booking, $opening) {

        $this->account_name         = $name;
        $this->opening              = $opening;
        $this->booking              = $booking;
        $this->transactionService   = new AccountTransaction;

    }

    public function createCustomer()
    {
            $account = Account::firstOrCreate([
                'name'                  => $this->account_name,
            ],[
                'account_group_id'      => 1,
                'account_control_id'    => 1,
                'account_subsidiary_id' => 8,
                'opening_balance'       => 0,
                'balance_type'          => 'Debit'
            ]);

            $this->customer = Customer::firstOrCreate([
                'name'              => $this->account_name,
                ],[
                'account_id'        => $account->id,
                'opening_balance'   => $this->opening ?? 0,
            ]);

            return $this->customer;

    }


    public function getCustomer()
    {
        $customer = Customer::query()->where('name', $this->account_name)->first();

        if (!$customer) {
            $customer = $this->createCustomer();
        }

        return $this->customer = $customer;
    }



    public function makeAccountTransaction()
    {
        $this->getCustomer();

        $cash_account       = $this->transactionService->getCashAccount();    // credit

        $sale_account       = Account::find($this->customer->account_id);    // debit

        $customer_account   = optional($this->customer)->account;    // debit

        $booking            = $this->booking;
        $invoice_no         = $booking->invoice_no;
        $date               = $booking->booking_date;

        $description        = 'Booking for ' . ($this->customer->name ?? 'Mr. Customer');

        $this->transactionService->storeTransaction($booking->company_id ?? auth()->user()->company_id, $booking,    $invoice_no,    $sale_account,      0, $booking->transection->total_amount,  $date, 'credit', 'Booking', $description);   //  Payable Amount

        $this->transactionService->storeTransaction($booking->company_id ?? auth()->user()->company_id, $booking,    $invoice_no,    $cash_account,      $booking->transection->collection, 0,   $date, 'debit', 'Booking', $description);    //  Paid Amount

        $this->transactionService->storeTransaction($booking->company_id ?? auth()->user()->company_id, $booking,    $invoice_no,    $customer_account,  ($booking->transection->total_amount - $booking->transection->collection), 0,    $date, 'debit', 'Booking', $description);    //  Due Amount
    }
}
