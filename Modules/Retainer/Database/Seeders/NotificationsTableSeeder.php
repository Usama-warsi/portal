<?php

namespace Modules\Retainer\Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // email notification
        $notifications = [
            'Retainer Send','Retainer Payment Create'
        ];
        $permissions = [
            'retainer send',
            'retainer payment create'

        ];
        foreach($notifications as $key=>$n){
            $ntfy = Notification::where('action',$n)->where('type','mail')->where('module','Retainer')->count();
            if($ntfy == 0){
                $new = new Notification();
                $new->action = $n;
                $new->status = 'on';
                $new->permissions = $permissions[$key];
                $new->module = 'Retainer';
                $new->type = 'mail';
                $new->save();
            }
        }

    }
}
