<?php

namespace App\Services;

use App\Models\Company;
use Module\HRM\Models\Department;
use Module\HRM\Models\Designation;
use Module\GeneralStore\Models\Purchase;
use Module\HRM\Models\Attendance\OutsideWork;
use Module\HRM\Models\Leave\LeaveApplication;
use Module\GeneralStore\Models\GoodsRequisition;
use Module\HRM\Models\Leave\ShortLeaveApplication;
use Module\HRM\Models\Leave\ShortLeaveAuthor\SLeaveAppDesig;
use Module\HRM\Models\Leave\ApprovalAuthor\ApplicantDesignation;
use Module\Permission\Models\Module;

class AppNotificationService
{
    public $totalNotificationCount = 0;

    private $userDepartmentIds = [];
    private $userCompanyIds = [];

    // global
    public $news_notification_count = 0;

    // hrm
    public $leave_recommend_count = 0;
    public $leave_approve_count = 0;
    public $leave_application_count = 0;
    public $short_leave_application_count = 0;
    public $out_of_works_count = 0;

    // general store
    public $gs_purchase_approve_count = 0;
    public $gs_requisition_approve_count = 0;


    // garmetns
    public $order_costing_approve_count = 0;
    public $pending_adjustment_sids_count = 0;
    public $pending_arp_requisition_count = 0;
    public $sweater_yarn_approve_pending = 0;
    public $sweater_yarn_approve_pending_count = 0;


    public $mid_upgrade_count = 0;


    public function __construct()
    {
        $active_modules = session()->get('active_modules') ?? [];
        if (auth()->user()) {
            $this->slugs = auth()->user()->permissions()->pluck('slug')->toArray();
        }

        if(in_array('HRM', $active_modules) && file_exists(base_path() . '/module/HRM/routes/web_hrm.php')) {

            $this->userDesignationIds   = Designation::userDesignationId();
            $this->userDepartmentIds    = Department::userDepartmentId();
        }
        $this->userCompanyIds       = Company::userCompanyId();


        // global
        if(in_array('News & Events', $active_modules)) {

            $this->news_notification_count = news_notifications();
        }

        // hrm
        if(in_array('HRM', $active_modules) && view()->exists('partials.sidebars.__sidebar_hrm')) {


            $this->getLeaveRecommendations();

            $this->getShortLeaveApplications();

            $this->getLeaveApplications();

            $this->OutOfWork();
        }



        // general store
        if(in_array('General Store', $active_modules)) {
            $this->getGeneralStoreNotifications();
        }



        $this->totalNotificationCount = $this->news_notification_count

                                        + $this->leave_recommend_count
                                        + $this->leave_approve_count
                                        + $this->leave_application_count
                                        + $this->short_leave_application_count
                                        + $this->out_of_works_count

                                        + $this->gs_purchase_approve_count
                                        + $this->gs_requisition_approve_count

                                        + $this->order_costing_approve_count
                                        + $this->pending_adjustment_sids_count
                                        + $this->pending_arp_requisition_count
                                        + $this->sweater_yarn_approve_pending_count

                                        + $this->mid_upgrade_count;

    }



    private function getGeneralStoreNotifications()
    {
        try {
            if(file_exists(base_path() . '/module/GeneralStore/routes/web_generalstore.php')) {

                $this->gs_purchase_approve_count = Purchase::whereIn('company_id', $this->userCompanyIds)
                                                        ->where('is_approved', 0)
                                                        ->count();


                $this->gs_requisition_approve_count = GoodsRequisition::whereIn('company_id', $this->userCompanyIds)
                                                                        ->whereIn('department_id', $this->userDepartmentIds)
                                                                        ->where('is_approved', 0)
                                                                        ->count();
            }
        } catch (\Exception $th) {}



    }

    private function getLeaveRecommendations()
    {

        try {
            $userDesignationId = optional(optional(auth()->user()->employee)->active_employment)->designation_id;
        $userDepartmentId = optional(optional(auth()->user()->employee)->active_employment)->department_id;
        $userCompanyId = optional(optional(auth()->user()->employee)->active_employment)->company_id;

        $applicantDesig = ApplicantDesignation::with(['recommender_infos' => function($q) use($userCompanyId, $userDepartmentId, $userDesignationId) {
                                                    $q->where('recommender_company_id', $userCompanyId)
                                                    ->where('recommender_department_id', $userDepartmentId)
                                                    ->where('recommender_designation_id', $userDesignationId);
                                                }])->whereHas('recommender_infos', function($q) use($userCompanyId, $userDepartmentId, $userDesignationId) {
                                                   $q->where('recommender_company_id', $userCompanyId)
                                                   ->where('recommender_department_id', $userDepartmentId)
                                                   ->where('recommender_designation_id', $userDesignationId);
                                                })
                                                ->get();



        $company_ids = $applicantDesig->map(function($item) {
            return $item->company_id;
        });

        $department_ids = $applicantDesig->map(function($item) {
            return optional($item->applicant_department)->department_id;
        });

        $designation_ids = $applicantDesig->map(function($item) {
            return $item->designation_id;
        });

        $new_department_ids = [];

        foreach($department_ids as $department_id) {
            if($department_id != null) {
                $new_deparment_ids[] = $department_id;
            }
        }


        $this->leave_recommend_count = LeaveApplication::

            when(auth()->id() != 1, function($qr) use($company_ids, $new_department_ids, $designation_ids) {
                $qr->whereHas('employee', function ($q) use($company_ids, $new_department_ids, $designation_ids) {
                    $q->whereIn('company_id', $company_ids)
                    ->when(count($new_department_ids) > 0, function($q) use($new_department_ids) {
                        $q->whereIn('department_id', $new_department_ids)
                        ->orWhere('department_id', null);
                    })
                    ->whereIn('designation_id', $designation_ids);
                });
            })
            ->where('recommended_by', '=', null)
            ->where('cancel', 0)

            ->count();
        } catch (\Throwable $th) {
            //throw $th;
        }


    }

    private function getLeaveApplications()
    {

        try {
            $userDesignationId = optional(optional(auth()->user()->employee)->active_employment)->designation_id;
        $userDepartmentId = optional(optional(auth()->user()->employee)->active_employment)->department_id;
        $userCompanyId = optional(optional(auth()->user()->employee)->active_employment)->company_id;

        $applicantDesig = ApplicantDesignation::with(['recommender_infos' => function($q) use($userCompanyId, $userDepartmentId, $userDesignationId) {
                                                    $q->where('approval_company_id', $userCompanyId)
                                                    ->where('approval_department_id', $userDepartmentId)
                                                    ->where('approval_designation_id', $userDesignationId);
                                                }])->whereHas('recommender_infos', function($q) use($userCompanyId, $userDepartmentId, $userDesignationId) {
                                                    $q->where('approval_company_id', $userCompanyId)
                                                    ->where('approval_department_id', $userDepartmentId)
                                                    ->where('approval_designation_id', $userDesignationId);
                                                })
                                                ->get();


        $company_ids = $applicantDesig->map(function($item) {
            return $item->company_id;
        });


        $department_ids = $applicantDesig->map(function($item) {
            return optional($item->applicant_department)->department_id;
        });

        $designation_ids = $applicantDesig->map(function($item) {
            return $item->designation_id;
        });

        $new_department_ids = [];

        foreach($department_ids as $department_id) {
            if($department_id != null) {
                $new_deparment_ids[] = $department_id;
            }
        }


        $this->leave_approve_count = LeaveApplication::where('recommended_by', '!=', "")

                                        ->when(auth()->id() != 1, function($qr) use($company_ids, $new_department_ids, $designation_ids) {
                                            $qr->whereHas('employee', function ($q) use($company_ids, $new_department_ids, $designation_ids) {
                                                $q->whereIn('company_id', $company_ids)
                                                ->when(count($new_department_ids) > 0, function($q) use($new_department_ids) {
                                                    $q->whereIn('department_id', $new_department_ids)
                                                    ->orWhere('department_id', null);
                                                })
                                                ->whereIn('designation_id', $designation_ids);
                                            });
                                        })

                                        ->where('approved_by', null)
                                        ->where('cancel', 0)
                                        ->count();
        } catch (\Throwable $th) {
            //throw $th;
        }

        // $this->leave_application_count = Notification::whereNotNull('employee_id')->where('employee_id', auth()->user()->employee_id)->count();
    }

    private function getShortLeaveApplications()
    {

        try {
            $userDesignationId = optional(optional(auth()->user()->employee)->active_employment)->designation_id;
        $userCompanyId = optional(optional(auth()->user()->employee)->active_employment)->company_id;

        $applicantDesig = SLeaveAppDesig::whereHas('s_leave_companies',function ($q) use($userCompanyId, $userDesignationId){
                                $q->whereHas('s_leave_recom_desigs',function ($sQ) use($userDesignationId){
                                    $sQ->where('recommender_designation_id', $userDesignationId);
                                })->where('company_id',$userCompanyId);
                            })->pluck('applicant_designation_id');

                        $applicantCompany = SLeaveAppDesig::whereHas('s_leave_companies',function ($q) use($userCompanyId,$userDesignationId){
                                $q->whereHas('s_leave_recom_desigs',function ($sQ) use($userDesignationId){
                                    $sQ->where('recommender_designation_id', $userDesignationId);
                                })->where('company_id', $userCompanyId);
                            })->pluck('company_id');


        $this->short_leave_application_count = ShortLeaveApplication::whereHas('employee',function ($q) use ($applicantCompany, $applicantDesig){
                                $q->whereIn('company_id', $applicantCompany)->whereIn('designation_id',$applicantDesig);
                            })
                            ->distinct()
                            ->where('seen_by', '=', null)
                            ->count();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // HRM Out of works Count

    private function OutOfWork(){
        try {
            $userCompanyId = optional(optional(auth()->user()->employee)->active_employment)->company_id;

        $this->out_of_works_count=OutsideWork::whereCompanyId(auth()->user()->company_id)->whereStatus(0)->count();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
