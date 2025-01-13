<?php

namespace Modules\Rotas\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeaveRequest extends Model
{
    protected $fillable =[
        'user_id', 'issue_by', 'leave_type', 'start_date', 'end_date', 'message', 'leave_approval', 'approved_by', 'approved_date', 'paid_status', 'response_message', 'leave_time', 'created_by'
    ];

    public function getCreatedByName() {
        return $this->hasOne('App\Models\Employee','id','created_by');
    }

    public function getApprovedBy() {
        return $this->hasOne('App\Models\Employee','id','approved_by');
    }

    public function getUserIdName() {
        return $this->hasOne('App\Models\Employee','id','user_id');
    }

    public function getRequestdateFormet() {
        $date_format = User::CompanyDateFormat('d F Y');
        return date($date_format, strtotime($this->created_at));
    }

    public function getRequestdatebetween()
    {
        $date_format = User::CompanyDateFormat('D d F Y');
        $start_date = date($date_format, strtotime($this->start_date));
        $end_date = date($date_format, strtotime($this->end_date));
        if($start_date == $end_date)
        {
            $datebetween = $start_date;
        }
        else
        {
            $datebetween = $start_date .' - '. $end_date;
        }
        return $datebetween;
    }

    public function getRequestResponse()
    {
        $leave_approval = $this->leave_approval;
        $created_by = $this->created_by;
        $approved_by = $this->approved_by;
        $name = '';
        $result = '';
        $date_format = User::CompanyDateFormat('d F Y');

            $user_data = User::find($this->issue_by);
            $ApprovedByName = $user_data->first_name.' '.$user_data->last_name;

        if ($leave_approval != 0) {

            $created_at = date($date_format, strtotime($this->approved_date));
            $fname = (!empty($this->getApprovedBy->first_name)) ? $this->getApprovedBy->first_name : '';
            $lname = (!empty($this->getApprovedBy->last_name)) ? $this->getApprovedBy->last_name : '';
            $ApprovedByName = $fname .' '.$lname;

            if($leave_approval == 1) { $span = '<span class="badge badge-success  mr-2"><b> '.__("APPROVED").' </b></span>'; }
            if($leave_approval == 2) { $span = '<span class="badge badge-danger  mr-2"><b> '.__("DENIED").' </b></span>'; }
            if($leave_approval == 3) { $span = '<span class="badge badge-info  mr-2"><b> '.__("DELETED").' </b></span>'; }
            $result = $span.''.__(' by ').' '.$ApprovedByName.' '.__(' on ').' '.$created_at.' ';

        } else {

            $created_at = date($date_format, strtotime($this->created_at));

            $span = '<span class="badge badge-secondary mr-2"><b> '.__("REQUESTED").' </b></span>';
            $result = $span.''.__(' by ').' '.$ApprovedByName.' '.__(' on ').' '.$created_at.' ';
        }
        return $result;
    }

    public function getLeaveApprovalStatus()
    {
        $userType = Auth::user()->type;
        $leave_approval = $this->leave_approval;
        $display = 'd-none';
        if($leave_approval == 0)
        {
            if(Auth::user()->acount_type == 1)
            {
                $display = '';
            }
            if(Auth::user()->acount_type == 2 && !empty(Auth::user()->manager_permission)) {
                $manager_permission = json_decode(Auth::user()->manager_permission,true);
                if(!empty($manager_permission['manager_manage_leave_and_approve_leave_requests_for_other']))
                {
                    $display = '';
                }
            }
        }

        return $display;
    }

    public static function getLeaveHasPermission()
    {
        $haspermission['embargoes'] = 0;
        $haspermission['request_rule'] = 0;
        $haspermission['leave_request'] = 0;
        if(Auth::user()->acount_type == 2 && !empty(Auth::user()->manager_permission))
        {
            $manager_permission = json_decode(Auth::user()->manager_permission,true);
            if(!empty($manager_permission))
            {
                $haspermission['leave_request'] = $manager_permission['manager_add_employees_and_manage_basic_information'];
                $haspermission['embargoes'] = (!empty($manager_permission['manager_manage_leave_embargoes'])) ? 1 : 0;
            }
        }
        return $haspermission;
    }
}
