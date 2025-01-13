<?php

namespace Modules\WordpressWoocommerce\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;


class Woocommerceconection extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'woocomerce_id',
        'original_id',
        'workspace_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\WordpressWoocommerce\Database\factories\WoocommerceconectionFactory::new();
    }

    public static function upload_woo_file($request, $name, $path)
    {
        try {
            $settings = getAdminAllSetting();


            if (!empty($settings['storage_setting'])) {
                if ($settings['storage_setting'] == 'wasabi') {
                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes =  !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }


                $request = str_replace("\0", '', $request);
                $file = file_get_contents($request);
                if ($settings['storage_setting'] == 'local') {
                    $save = Storage::disk($settings['storage_setting'])->put($path . '/' . $name, $file);
                    // dd($save , $request ,$file , $path , $name);

                } else {
                    $save = Storage::disk($settings['storage_setting'])->put($path . '/' . $name, $file);
                }
                $image_url = '';
                if ($settings['storage_setting'] == 'wasabi') {
                    $url = $path . $name;
                    $image_url = \Storage::disk('wasabi')->url($url);
                } elseif ($settings['storage_setting'] == 's3') {
                    $url = $path . $name;
                    $image_url = \Storage::disk('s3')->url($url);
                } else {

                    $url = $path . $name;
                    $image_url = url($path  . $name);
                }

                $res = [
                    'flag' => 1,
                    'msg'  => 'success',
                    'url'  => $url,
                    'image_path'  => $url,
                    'full_url'    => $image_url
                ];
                return $res;
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => 'not set configurations',
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

}
