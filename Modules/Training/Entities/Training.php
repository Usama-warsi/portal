<?php

namespace Modules\Training\Entities;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch',
        'trainer_option',
        'training_type',
        'trainer',
        'training_cost',
        'employee',
        'start_date',
        'end_date',
        'description',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Training\Database\factories\TrainingFactory::new();
    }

    public static $options = [
        'Internal',
        'External',
    ];

    public static $performance = [
        'Not Concluded',
        'Satisfactory',
        'Average',
        'Poor',
        'Excellent',
    ];

    public static $Status = [
        'Pending',
        'Started',
        'Completed',
        'Terminated',
    ];

    public function branches()
    {
        return $this->hasOne(\Modules\Hrm\Entities\Branch::class, 'id', 'branch');
    }

    public function types()
    {
        return $this->hasOne(TrainingType::class, 'id', 'training_type');
    }

    public function employees()
    {
        return $this->hasOne(\Modules\Hrm\Entities\Employee::class, 'id', 'employee');
    }

    public function trainers()
    {
        return $this->hasOne(Trainer::class, 'id', 'trainer');
    }
    public static function status($status)
    {
        if ($status == '0') {
            return 'Pending';
        }
        if ($status == '1') {
            return 'Started';
        }
        if ($status == "2") {
            return "Completed";
        }
        if ($status == "3") {
            return "Terminated";
        }
    }
    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $staff_permission = [
            'trainings manage',
            'trainer manage',
            'trainer show',
        ];

        $hr_permission = [
            'trainings manage',
            'training manage',
            'training create',
            'training edit',
            'training delete',
            'training show',
            'training update status',
            'trainingtype manage',
            'trainingtype create',
            'trainingtype edit',
            'trainingtype delete',
            'trainer manage',
            'trainer create',
            'trainer show',
            'trainer edit',
            'trainer delete',
        ];

        if ($role_id == Null) {

            // staff
            $roles_v = Role::where('name', 'staff')->get();

            foreach ($roles_v as $role) {
                foreach ($staff_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$role->hasPermission($permission_v)) {
                            $role->givePermission($permission);
                        }
                    }
                }
            }
            
            // hr
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
            if ($rolename == 'staff') {
                $roles_v = Role::where('name', 'staff')->where('id', $role_id)->first();
                foreach ($staff_permission as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!empty($permission)) {
                        if (!$roles_v->hasPermission($permission_v)) {
                            $roles_v->givePermission($permission);
                        }
                    }
                }
            }

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
