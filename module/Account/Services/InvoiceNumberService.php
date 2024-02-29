<?php


namespace Module\Account\Services;


use App\Models\Company;
use Module\Account\Models\InvoiceNo;
use Illuminate\Http\Request;

class InvoiceNumberService
{
    public function getVoucherInvoiceNo($company_id, $voucher_type): string
    {
        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Voucher')
            ->where('year', date('Y'))
            ->where('company_id', $company_id)
            ->first())->next_id;

        if ($nextId == null)
            $nextId = InvoiceNo::query()
                ->Create([
                    'type' => 'Voucher',
                    'year' => date('Y'),
                    'company_id' => $company_id,
                    'next_id' => 1
                ])->next_id;

        if ($voucher_type == "Journal") {
            return 'J-'
                . date('Y')
                . '-'
                . str_pad($nextId, 6, "0", STR_PAD_LEFT);
        } else {
            // Contra
            return 'C-'
                . date('Y')
                . '-'
                . date('m')
                . '-'
                . str_pad($nextId, 6, "0", STR_PAD_LEFT);
        }
    }


    public function getFundTransferInvoiceNo($company_id): string
    {
        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Fund Transfer')
            ->where('year', date('Y'))
            ->where('company_id', $company_id)
            ->first())->next_id;

        if ($nextId == null)
            $nextId = InvoiceNo::query()
                ->Create([
                    'type' => 'Voucher',
                    'year' => date('Y'),
                    'company_id' => $company_id,
                    'next_id' => 1
                ])->next_id;

        return 'INV-FT-'
            . date('Y')
            . '-'
            . $this->getCompanyCode($company_id)
            . '-'
            . str_pad($nextId, 6, "0", STR_PAD_LEFT);
    }

    // Get Purchase Details Transection
    public function getPurchaseDetailTransactionNo($key, $invoice_no)
    {
        $substr = substr($invoice_no, -6);

        return str_replace($substr, str_pad($key + 1, 2, '0', STR_PAD_LEFT) . '-' . $substr, $invoice_no);
    }

    // Get Sale Details Transection
    public function getSaleDetailTransactionNo($key, $invoice_no)
    {
        $substr = substr($invoice_no, -6);

        return str_replace($substr, str_pad($key + 1, 2, '0', STR_PAD_LEFT) . '-' . $substr, $invoice_no);
    }

    private function getCompanyCode($company_id)
    {
        $company = Company::query()->find($company_id);

        return $company->code ?? ($company_id * 10);
    }














    /*
     |--------------------------------------------------------------------------
     | SET NEXT INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function setNextInvoiceNo($company_id, $type, $year)
    {
        $invoice_no = InvoiceNo::query()
            ->firstOrCreate([
                'type'          => $type,
                'year'          => $year,
                // 'company_id'    => 1
            ]);

        $invoice_no->increment('next_id');
    }














    /*
     |--------------------------------------------------------------------------
     | PURCHASE INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getPurchaseInvoiceNo($company_id)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Purchase')
            ->where('year', date('Y'))
            ->where('company_id', $company_id)
            ->first())
            ->next_id;

        if ($nextId == null)
            $nextId = InvoiceNo::create([
                'type'          => 'Purchase',
                'year'          => date('Y'),
                'company_id'    => $company_id,
                'next_id'       => 1
            ])
                ->next_id;



        return 'Pur-INV-'
            . date('Y')
            . '-'
            . $company_id
            . '-'
            . str_pad($nextId, 6, "0", STR_PAD_LEFT);
    }














    /*
     |--------------------------------------------------------------------------
     | RECEIVE VOUCHER INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getReceiveVoucherInvoiceNo($company_id)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Receive Voucher')
            ->where('year', date('Y'))
            ->where('company_id', $company_id)
            ->first())
            ->next_id;

        if ($nextId == null)

            $nextId = InvoiceNo::Create([
                'type'          => 'Receive Voucher',
                'year'          => date('Y'),
                'company_id'    => $company_id,
                'next_id'       => 1
            ])
                ->next_id;

        return 'RCV-'
            . date('Y')
            . '-'
            . str_pad($nextId, 6, "0", STR_PAD_LEFT);
    }














    /*
     |--------------------------------------------------------------------------
     | CONTRA VOUCHER INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getContraVoucherInvoiceNo($company_id)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Contra Voucher')
            ->where('year', date('Y'))
            ->where('company_id', $company_id)
            ->first())
            ->next_id;

        if ($nextId == null)

            $nextId = InvoiceNo::Create([
                'type'          => 'Contra Voucher',
                'year'          => date('Y'),
                'company_id'    => $company_id,
                'next_id'       => 1
            ])
                ->next_id;

        return 'contra-'
            . date('Y')
            . '-'
            . str_pad($nextId, 6, "0", STR_PAD_LEFT);
    }














    /*
     |--------------------------------------------------------------------------
     | JOURNAL VOUCHER INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getJournalVoucherInvoiceNo($company_id)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Journal Voucher')
            ->where('year', date('Y'))
            ->where('company_id', $company_id)
            ->first())
            ->next_id;

        if ($nextId == null)

            $nextId = InvoiceNo::Create([
                'type'          => 'Journal Voucher',
                'year'          => date('Y'),
                'company_id'    => $company_id,
                'next_id'       => 1
            ])
                ->next_id;

        return 'journal-'
            . date('Y')
            . '-'
            . str_pad($nextId, 6, "0", STR_PAD_LEFT);
    }














    /*
     |--------------------------------------------------------------------------
     | PAYMENT VOUCHER INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getPaymentVoucherInvoiceNo($company_id)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Payment Voucher')
            ->where('year', date('Y'))
            ->where('company_id', $company_id)
            ->first())
            ->next_id;

        if ($nextId == null)

            $nextId = InvoiceNo::Create([
                'type'          => 'Payment Voucher',
                'year'          => date('Y'),
                'company_id'    => $company_id,
                'next_id'       => 1
            ])
                ->next_id;

        return 'PMNT-'
            . date('Y')
            . '-'
            . str_pad($nextId, 6, "0", STR_PAD_LEFT);
    }














    /*
     |--------------------------------------------------------------------------
     | SALE INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getSaleInvoiceNo($company_id)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Sale')
            ->where('year', date('Y'))
            ->first())
            ->next_id;

        if ($nextId == null)

            $nextId = InvoiceNo::Create([
                'type'          => 'Sale',
                'year'          => date('Y'),
                'next_id'       => 1
            ])
                ->next_id;

        return 'Sale-INV-'
            . date('Y')
            . '-'
            . str_pad($nextId, 6, "0", STR_PAD_LEFT);
    }







    /*
     |--------------------------------------------------------------------------
     | SALE RETURN INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getSaleReturnInvoiceNo($company_id)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Sale Return')
            ->where('year', date('Y'))
            ->first())
            ->next_id;

        if ($nextId == null)

            $nextId = InvoiceNo::Create([
                'type'          => 'Sale',
                'year'          => date('Y'),
                'next_id'       => 1
            ])
            ->next_id;

        return 'SRI-'
            . date('Y')
            . '-'
            . str_pad($nextId, 5, "0", STR_PAD_LEFT);
    }






    /*
     |--------------------------------------------------------------------------
     | PURCHASE RETURN INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getPurchaseReturnInvoiceNo($company_id)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Purchase Return')
            ->where('year', date('Y'))
            ->first())
            ->next_id;

        if ($nextId == null)

            $nextId = InvoiceNo::Create([
                'type'          => 'Purchase',
                'year'          => date('Y'),
                'next_id'       => 1
            ])
            ->next_id;

        return 'PRI-'
            . date('Y')
            . '-'
            . str_pad($nextId, 5, "0", STR_PAD_LEFT);
    }





    /*
     |--------------------------------------------------------------------------
     | DAMAGE INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getDamageInvoiceNo($company_id)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', 'Damage')
            ->where('year', date('Y'))
            ->first())
            ->next_id;

        if ($nextId == null)

            $nextId = InvoiceNo::Create([
                'type'          => 'Damage',
                'year'          => date('Y'),
                'next_id'       => 1
            ])
            ->next_id;

        return 'DMG-'
            . date('Y')
            . '-'
            . str_pad($nextId, 5, "0", STR_PAD_LEFT);
    }









    /*
     |--------------------------------------------------------------------------
     | GET INVOICE NUMBER
     |--------------------------------------------------------------------------
    */
    public function getInvoiceNo($company_id,$type)
    {

        $nextId = optional(InvoiceNo::query()
            ->where('type', $type)
            ->where('year', date('Y'))
            ->where('company_id', $company_id)
            ->first())
            ->next_id;

        if ($nextId == null)

            $nextId = InvoiceNo::Create([
                'type'          => $type,
                'year'          => date('Y'),
                'company_id'    => $company_id,
                'next_id'       => 1
            ])
                ->next_id;

        return $type.'-INV-'
            . date('Y')
            . '-'
            . str_pad($nextId, 6, "0", STR_PAD_LEFT);
    }














    /*
     |--------------------------------------------------------------------------
     | VOUCHER DETAIL TRANSACTION NO
     |--------------------------------------------------------------------------
    */
    public function getVoucherDetailTransactionNo($key, $invoice_no)
    {
        $substr = substr($invoice_no, -6);

        return str_replace($substr, str_pad($key + 1, 2, '0', STR_PAD_LEFT) . '-' . $substr, $invoice_no);
    }
}
