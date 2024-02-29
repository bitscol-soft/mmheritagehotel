<?php

namespace App\Jobs;

use Module\HRM\Models\Employee\BankInformation;
use Module\HRM\Models\Employee\EducationalQualification;
use Module\HRM\Models\Employee\Employee;
use Module\HRM\Models\Employee\Experience;
use Module\HRM\Models\Employee\Guardian;
use Module\HRM\Models\Employee\PersonalInformation;
use Module\HRM\Models\Employee\ReferencePerson;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EmployeeTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }

}
