<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\CrmInvoiceEmail;
use Illuminate\Console\Command;
use Module\CRM\Models\CrmCustomer;
use Module\CRM\Models\MailTemplate;
use Module\CRM\Models\CrmBillGenerate;
use Module\CRM\Services\ProjectBillingService;

class CrmSendMail extends Command
{
    protected $signature = 'crm:send-invoice';

    protected $description = 'Send CRM Billing Invoice';

    public function handle()
    {
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
