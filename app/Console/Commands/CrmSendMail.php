<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\CrmInvoiceEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Module\CRM\Models\CRMCustomer;
use Module\CRM\Models\MailTemplate;
use Module\CRM\Models\CrmBillGenerate;
use Module\CRM\Services\ProjectBillingService;

class CrmSendMail extends Command
{
    protected $signature = 'crm:send-invoice';

    protected $description = 'Send CRM Billing Invoice';

    public function handle()
    {
        // c_r_m_bill_generates is absent from the shipped schema (the original CRM
        // migrations are lost; BUGS #27) - no-op instead of crashing when run.
        if (!Schema::hasTable('c_r_m_bill_generates')) {
            $this->warn('CRM billing is not available: the c_r_m_bill_generates table is missing.');

            return;
        }

        $today = date('d');
        if ($today == 1 || $today == 7 || $today == 15) {

            $project_invoices = CrmBillGenerate::with('customer')->where('payment_status', '=', 'due')->get();


            $data['mail_template']  = $mail_template    = MailTemplate::where('status', 1)->first();

            foreach ($project_invoices as $key => $project) {
                $data['project']        = $project;

                (new ProjectBillingService())->generateInvoicePdf($data);

                CrmInvoiceEmail::dispatch($mail_template, $project)->delay(now()->addSeconds(3));
            }
        }
        $this->info('Sent CRM Billing Invoice has been successfully!');
    }
}
