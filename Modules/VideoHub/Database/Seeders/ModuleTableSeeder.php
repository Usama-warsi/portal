<?php

namespace Modules\VideoHub\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\VideoHub\Entities\VideoHubModule;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $sub_module = [
            'Lead', 'Deal'
        ];

        $content = [
            [
                'Lead' => '{"field":[{"label":"Lead","field_type":"select","field_name":"lead","placeholder":"Select Lead", "model_name": "Lead"}]}',
                'Deal' => '{"field":[{"label":"Deal","field_type":"select","field_name":"deal","placeholder":"Select Deal", "model_name": "Deal"}]}',
            ]
        ];
        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'CRM')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'CRM';
                    $new->sub_module = $sm;
                    if ($sm == 'Lead') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Deal') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $sub_module = [
            'Products', 'Services', 'Parts', 'Rent', 'Music Institute', 'Restaurants'
        ];

        $content = [
            [
                'Products'          => '{"field":[{"label":"Products","field_type":"select","field_name":"product","placeholder":"Select Products", "model_name": "product"}]}',
                'Services'          => '{"field":[{"label":"Services","field_type":"select","field_name":"service","placeholder":"Select Services", "model_name": "service"}]}',
                'Parts'             => '{"field":[{"label":"Parts","field_type":"select","field_name":"parts","placeholder":"Select Parts", "model_name": "parts"}]}',
                'Rent'              => '{"field":[{"label":"Rent","field_type":"select","field_name":"rent","placeholder":"Select Rent", "model_name": "rent"}]}',
                'Music Institute'   => '{"field":[{"label":"Music Institute","field_type":"select","field_name":"music institute","placeholder":"Select Music Institute", "model_name": "music institute"}]}',
                'Restaurants'       => '{"field":[{"label":"Restaurants","field_type":"select","field_name":"restaurants","placeholder":"Select Restaurants", "model_name": "restaurants"}]}',
            ]
        ];
        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Product Service')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Product Service';
                    $new->sub_module = $sm;
                    if ($sm == 'Products') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Services') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Parts') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Rent') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Music Institute') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Restaurants') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $sub_module = [
            ''
        ];

        $content = [
            [
                '' => '{"field":[{"label":"Project","field_type":"select","field_name":"project","placeholder":"Select Project", "model_name": "Project"}]}',
            ]
        ];
        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Project')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Project';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $content = [
            [
                '' => '{"field":[{"label":"Property","field_type":"select","field_name":"property","placeholder":"Select Property", "model_name": "Property"}]}',
            ]
        ];
        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Property Management')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Property Management';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $content = [
            [
                '' => '{"field":[{"label":"Programs","field_type":"select","field_name":"programs","placeholder":"Select Programs", "model_name": "Programs"}]}',
            ]
        ];
        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Sales Agent')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Sales Agent';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $content = [
            [
                '' => '',
            ]
        ];
        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'vCard')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'vCard';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Contract')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Contract';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Appointment')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Appointment';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Feedback')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Feedback';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Insurance Management')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Insurance Management';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Rental Management')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Rental Management';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Custom Field')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Custom Field';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Assets')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Assets';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'portfolio')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'portfolio';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = VideoHubModule::where('module', 'Business Process Mapping')->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new VideoHubModule();
                    $new->module = 'Business Process Mapping';
                    $new->sub_module = $sm;
                    if ($sm == '') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }
    }
}
