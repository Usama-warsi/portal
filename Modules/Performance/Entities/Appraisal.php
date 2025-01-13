<?php

namespace Modules\Performance\Entities;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Modules\Performance\Entities\Indicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch',
        'employee',
        'appraisal_date',
        'customer_experience',
        'marketing',
        'administration',
        'professionalism',
        'integrity',
        'attendance',
        'remark',
        'workspace',
        'created_by',
    ];
    
    public static $technical = [
        'None',
        'Beginner',
        'Intermediate',
        'Advanced',
        'Expert / Leader',
    ];

    public static $organizational = [
        'None',
        'Beginner',
        'Intermediate',
        'Advanced',
    ];
    protected static function newFactory()
    {
        return \Modules\Performance\Database\factories\AppraisalFactory::new();
    }
    public function branches()
    {
        return $this->hasOne(\Modules\Hrm\Entities\Branch::class, 'id', 'branch');
    }

    public function employees()
    {
        return $this->hasOne(\Modules\Hrm\Entities\Employee::class, 'id', 'employee');
    }
    public static function getTargetrating($designationid, $competencyCount)
    {
        $indicator = Indicator::where('designation', $designationid)->first();

        if (!empty($indicator->rating) && ($competencyCount != 0))
        {
            $rating = json_decode($indicator->rating, true);
            $starsum = array_sum($rating);

            $overallrating = $starsum / $competencyCount;
        } else {
            $overallrating = 0;
        }
        return $overallrating;
    }

    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $hr_permission = [
            'performance manage',
            'indicator manage',
            'indicator create',
            'indicator edit',
            'indicator delete',
            'indicator show',
            'appraisal manage',
            'appraisal create',
            'appraisal edit',
            'appraisal delete',
            'appraisal show',
            'goaltracking manage',
            'goaltracking create',
            'goaltracking edit',
            'goaltracking delete',
            'goal type manage',
            'goal type create',
            'goal type edit',
            'goal type delete',
            'performancetype manage',
            'performancetype create',
            'performancetype edit',
            'performancetype delete',
            'competencies manage',
            'competencies create',
            'competencies edit',
            'competencies delete',
        ];

        if ($role_id == Null) {

            // staff
            $roles_v = Role::where('name', 'hr')->get();

            foreach ($roles_v as $role) {
                foreach ($hr_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$role->hasPermission($permission_v)) {
                            $role->givePermission($permission);
                        }
                    }
                }
            }
        } else {
            if ($rolename == 'hr') {
                $roles_v = Role::where('name', 'hr')->where('id', $role_id)->first();
                foreach ($hr_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$roles_v->hasPermission($permission_v)) {
                            $roles_v->givePermission($permission);
                        }
                    }
                }
            }
        }
    }
}
