<?php

namespace Modules\Rotas\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\AIAssistant\Entities\AssistantTemplate;

class AIAssistantTemplateListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $defaultTemplate = [
            [
                'template_name'=>'note',
                'template_module'=>'rota',
                'prompt'=> "Ggenerate description '##task##' in ##name## inform to the employee what work he have to do",
                'field_json'=>'{"field":[{"label":"Project Name","placeholder":"e.g. rotago","field_type":"text_box","field_name":"name"},{"label":"Task detail","placeholder":"e.g.","field_type":"textarea","field_name":"task"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'leave_reason',
                'template_module'=>'leave',
                'prompt'=> "Generate a comma-separated string of common leave reasons that employees may provide to their employers. Include both personal and professional reasons for taking leave, such only '##type##'. Aim to generate a diverse range of leave reasons that can be used in different situations. Please provide a comprehensive and varied list of leave reasons that can help employers understand and accommodate their employees' needs.",
                'field_json'=>'{"field":[{"label":"Leave Type","placeholder":"e.g.illness, family emergencies,vacation","field_type":"text_box","field_name":"type"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'remark',
                'template_module'=>'leave',
                'prompt'=> "Generate a comma-separated string of common leave reasons that employees may provide to their employers. Include both personal and professional reasons for taking leave, such only '##type##'. Aim to generate a diverse range of leave reasons that can be used in different situations. Please provide a comprehensive and varied list of leave reasons that can help employers understand and accommodate their employees' needs.",
                'field_json'=>'{"field":[{"label":"Leave Type","placeholder":"e.g.illness, family emergencies,vacation","field_type":"text_box","field_name":"type"}]}',
                'is_tone'=> 1,
            ],
        ];

        foreach($defaultTemplate as $temp)
        {
            $check = AssistantTemplate::where('template_module',$temp['template_module'])->where('module','Rotas')->where('template_name',$temp['template_name'])->exists();
            if(!$check)
            {
                AssistantTemplate::create(
                    [
                        'template_name' => $temp['template_name'],
                        'template_module' => $temp['template_module'],
                        'module' => 'Rotas',
                        'prompt' => $temp['prompt'],
                        'field_json' => $temp['field_json'],
                        'is_tone' => $temp['is_tone'],
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
            }
        }

        // $this->call("OthersTableSeeder");
    }
}
