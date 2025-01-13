<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreThemeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'type',
        'store_id',
        'theme_name',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\StoreThemeSettingFactory::new();
    }

    public static function getDefaultThemeOrder($themename)
    {
        $order = [];
        if ($themename == 'theme1') {
            $order = [
                'banner' => '1',
                'homepage-header' => '2',
                'homepage-featured-course' => '3',
                'homepage-categories' => '4',
                'homepage-on-sale' => '5',
                'homepage-email-subscriber' => '6',
            ];
        }
        if ($themename == 'theme2') {
            $order = [
                'banner' => '1',
                'homepage-header' => '2',
                'homepage-featured-course' => '3',
                'homepage-categories' => '4',
                'homepage-on-sale' => '5',
                'homepage-email-subscriber' => '6',
            ];
        }
        if ($themename == 'theme3') {
            $order = [
                'banner' => '1',
                'homepage-featured-course' => '2',
                'homepage-categories' => '3',
                'homepage-on-sale' => '4',
                'homepage-email-subscriber' => '5',
            ];
        }
        if ($themename == 'theme4') {
            $order = [
                'banner' => '1',
                'homepage-featured-course' => '2',
                'homepage-categories' => '3',
                'homepage-on-sale' => '4',
                'homepage-email-subscriber' => '5',
            ];
        }
        return $order;
    }

}
