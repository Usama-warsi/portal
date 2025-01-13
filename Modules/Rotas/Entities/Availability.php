<?php

namespace Modules\Rotas\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Availability extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'start_date', 'end_date', 'repeat_week', 'availability_json', 'workspace','created_by'];

    protected static function newFactory()
    {
        return \Modules\Rotas\Database\factories\AvailabilityFactory::new();
    }

    public function getUserInfo()
    {
        return $this->hasOne('Modules\Hrm\Entities\Employee','id','employee_id');
    }

    public function getAvailabilityDate()
    {
        $start_date = '';
        $end_date = '';
        $date = '';
        $date_formate = Rota::CompanyDateFormat(' d M Y ');
        if(!empty($this->start_date)) {
            $start_date = date ($date_formate , strtotime($this->start_date) );
            $date = $start_date;
        }
        if(!empty($this->end_date)) {
            $end_date = date ($date_formate , strtotime($this->end_date) );
            $date .= ' - ' .$end_date.' ';
        }

        if(!empty($this->repeat_week)) {
            if($this->repeat_week == 1) {
                $date .= ''.__(' ( Repeating every week ) ').'';
            } else {
                $date .= '('.__(' Repeating every ').$this->repeat_week.' '.__('week').')';
            }
        }
        return $date;
    }
}
