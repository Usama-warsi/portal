<?php

namespace Modules\VideoHub\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoHubModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'sub_module',
        'field_json',
    ];

    protected static function newFactory()
    {
        return \Modules\VideoHub\Database\factories\VideoHubModuleFactory::new();
    }

    // Get filter Module
    public static function filter($sub_module='',$view_data)
    {
        $video_module   = VideoHubModule::where('sub_module',$sub_module)->first();
        // $item_id        =  \Request::segment(2);
        if (isset($view_data['productService'])) {
            $item_data = $view_data['productService'];
        } elseif (isset($view_data['lead'])) {
            $item_data = $view_data['lead'];
        } elseif (isset($view_data['deal'])) {
            $item_data = $view_data['deal'];
        } elseif (isset($view_data['project'])) {
            $item_data = $view_data['project'];
        } elseif (isset($view_data['property'])) {
            $item_data = $view_data['property'];
        } elseif (isset($view_data['program'])) {
            $item_data = $view_data['program'];
        }
        if($video_module != null){
            $module = [
                'sub_module' => $video_module->id,
                'filter' => ($video_module->module == 'Product Service') ? 'Items' : $video_module->module
            ];
            if (isset($item_data) && !empty($item_data)) {
                $module = [
                    'item'          => (int)$item_data->id,
                    'sub_module'    => (isset($video_module->id) ? $video_module->id : 0),
                    'filter'        => ($video_module->module == 'Product Service') ? 'Items' : $video_module->module
                ];
            }
            return $module;
        } else {
            $module = [
                'filter' => $sub_module
            ];
            if (isset($item_data) && !empty($item_data)) {
                $module = [
                    'item'          => (int)$item_data->id,
                    'filter'        => $sub_module
                ];
            }
            return $module;
        }
    }

    public static function get_view_to_stack_hook()
    {

        $views = [
            'Project'            => 'taskly::projects.show', //show
            'Lead'               => 'lead::leads.show', //show
            'Deal'               => 'lead::deals.show', //show
            'Product Service'    => 'productservice::view', //view
            'Property Manage'    => 'propertymanagement::property.show', //show
            'Sales Agent'        => 'salesagent::programs.show', //show
        ];

        return $views;
    }
}
