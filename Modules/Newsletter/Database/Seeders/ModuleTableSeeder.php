<?php

namespace Modules\Newsletter\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Newsletter\Entities\NewsletterModule;

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
            'Invoice', 'Proposal','Purchase'
        ];
        $content = [
            [

                'Invoice' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.Select status","field_type":"select","field_name":"status","model_name": "Invoice"}]}',
                'Proposal' => '{"field":[{"label":"Price","placeholder":"e.g.enter price","field_type":"number","field_name":"price"},{"label":"status","placeholder":"e.g.Select status","field_type":"select","field_name":"status","model_name": "Proposal"}]}',
                'Purchase' => '{"field":[{"label":"Warehouse","placeholder":"Select Project","field_type":"select","field_name":"warehouse_id","model_name": "Warehouse"},{"label":"Category","placeholder":"Select Type","field_type":"select","field_name":"category_id","model_name": "Category"}]}',
            ]
        ];

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'general')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'general';
                    $new->submodule = $sm;
                    $new->type = 'company';
                    if ($sm == 'Invoice') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Proposal') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Purchase') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $sub_module = [
            'Customer', 'Vendor', 'Bill'
        ];

        $content = [
            [
                'Customer' => '{"field":[{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',
                'Vendor' => '{"field":[{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',
                'Bill' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.enter status","field_type":"select","field_name":"status", "model_name": "Bill"}]}',

            ]
        ];

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Account')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Account';
                    $new->submodule = $sm;
                    if ($sm == 'Customer') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Vendor') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Bill') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


        $sub_module = [
            'Assets'
        ];


        $content = [
            [
                'Assets' => '{"field":[{"label":"Amount","placeholder":"Please Enter Amount","field_type":"number","field_name":"amount"}]}',
            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Assets')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Assets';
                    $new->submodule = $sm;
                    if ($sm == 'Assets') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


        $sub_module = [
            'Contract'
        ];


        $content = [
            [
                'Contract' => '{"field":[{"label":"Project","placeholder":"Select Project","field_type":"select","field_name":"project_id","model_name": "Project"},{"label":"Type","placeholder":"Select Type","field_type":"select","field_name":"type","model_name": "Type"}]}',
            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Contract')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Contract';
                    $new->submodule = $sm;
                    if ($sm == 'Contract') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $sub_module = [
            'Employee', 'Leave', 'Award', 'Transfer', 'Resignation', 'Promotion', 'Termination', 'Announcement'
        ];

        $content = [
            [
                'Employee' => '{"field":[{"label":"Branch","placeholder":"Select Branch","field_type":"select","field_name":"branch_id","model_name":"Branch"},{"label":"Department","placeholder":"Select  Department","field_type":"select","field_name":"department_id","model_name":"Department"},{"label":"Designation","placeholder":"Select Designation","field_type":"select","field_name":"designation_id","model_name":"Designation"}]}',
                'Leave' => '{"field":[{"label":"Leave Type","placeholder":"Select Leave Type","field_type":"select","field_name":"type","model_name": "Leave"}]}',
                'Award' => '{"field":[{"label":"Type","placeholder":"e.g.enter type","field_type":"select","field_name":"type","model_name": "Award"}]}',
                'Transfer' => '{"field":[{"label":"Branch","placeholder":"Select Type","field_type":"select","field_name":"branch_id","model_name":"Branch"},{"label":"Department","placeholder":"Select Department","field_type":"select","field_name":"department_id","model_name":"Department"}]}',
                'Resignation' => '{"field":[{"label":"Resignation Date","placeholder":"e.g.enter type","field_type":"date","field_name":"resignation_date"},{"label":"Last Working Date","placeholder":"enter noticedate","field_type":"date","field_name":"last_working_date"}]}',
                'Promotion' => '{"field":[{"label":"Designation","placeholder":"select designation","field_type":"select","field_name":"designation" ,"model_name": "Promotion"},{"label":"Promotion Date","placeholder":"enter promotiondate","field_type":"date","field_name":"promotiondate"}]}',
                'Termination' => '{"field":[{"label":"Termination Type","placeholder":"Select Type","field_type":"select","field_name":"type","model_name": "Termination"},{"label":"Notice Date","placeholder":"enter noticedate","field_type":"date","field_name":"noticedate"},{"label":"Termination date","placeholder":"enter terminationdate","field_type":"date","field_name":"terminationdate"}]}',
                'Announcement' => '{"field":[{"label":"Branch","placeholder":"Select Type","field_type":"select","field_name":"branch_id","model_name":"Branch"},{"label":"Department","placeholder":"Select Department","field_type":"select","field_name":"department_id","model_name":"Department"}]}',
            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Hrm')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Hrm';
                    $new->submodule = $sm;
                    if ($sm == 'Employee') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Leave') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Award') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Transfer') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Resignation') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Promotion') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Termination') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Announcement') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }




        $sub_module = [
            'Lead', 'Deal'
        ];

        $content = [
            [
                'Lead' => '{"field":[{"label":"Lead Stage","field_type":"select","field_name":"stage","placeholder":"Enter Stage", "model_name": "LeadStage"},
                {"label":"Pipeline","placeholder":"Select Pipeline","field_type":"select","field_name":"pipeline","model_name": "Pipeline"}
                ]}',
                'Deal' => '{"field":[{"label":"Deal Stage","field_type":"select","field_name":"stage","placeholder":"Enter Stage", "model_name": "DealStage"},
                {"label":"Pipeline","placeholder":"Select Pipeline","field_type":"select","field_name":"pipeline","model_name": "Pipeline"}
                ]}',
            ]
        ];
        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Lead')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Lead';
                    $new->submodule = $sm;
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
            'Interview Schedule',
            'Job Application'
        ];

        $content = [
            [

                'Interview Schedule' => '{"field":[{"label":"Date","placeholder":"e.g.enter type","field_type":"date","field_name":"date"},{"label":"Time","placeholder":"enter noticedate","field_type":"time","field_name":"time"}]}',
                'Job Application' => '{"field":[{"label":"Job","placeholder":"Select Job","field_type":"select","field_name":"job","model_name": "Job" },{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',

            ]
        ];

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Recruitment')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Recruitment';
                    $new->submodule = $sm;
                    if ($sm == 'Interview Schedule') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Job Application') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


        $sub_module = [
            'Retainer'
        ];

        $content = [
            [
                'Retainer' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.enter status","field_type":"select","field_name":"status","model_name": "Retainer"}]}',
            ]
        ];

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Retainer')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Retainer';
                    $new->submodule = $sm;
                    if ($sm == 'Retainer') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $sub_module = [
            'Account', 'Contact', 'Sales Invoice', 'Sales Order', 'Meeting', 'Call'
        ];


        $content = [
            [
                'Account' => '{"field":[{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',
                'Contact' => '{"field":[{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',
                'Sales Invoice' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.enter status","field_type":"select","field_name":"status","model_name": "SalesInvoice"}]}',
                'Sales Order' => '{"field":[{"label":"Amount","placeholder":"e.g.enter amount","field_type":"number","field_name":"amount"},{"label":"status","placeholder":"e.g.enter status","field_type":"select","field_name":"status","model_name": "SalesOrder"}]}',
                'Meeting' => '{"field":[{"label":"Parent","placeholder":"Select Lead","field_type":"select","field_name":"parent","model_name": "Meeting" },{"label":"Attendees Lead","placeholder":"Select Lead","field_type":"select","field_name":"attendees_lead","model_name": "Lead" },{"label":"Start Date","placeholder":"e.g.enter type","field_type":"date","field_name":"start_date"},{"label":"End Date","placeholder":"e.g.enter type","field_type":"date","field_name":"end_date"}]}',
                'Call' => '{"field":[{"label":"Parent","placeholder":"Select Lead","field_type":"select","field_name":"parent","model_name": "Call" },{"label":"Attendees Lead","placeholder":"Select Lead","field_type":"select","field_name":"attendees_lead","model_name": "Lead" },{"label":"Start Date","placeholder":"e.g.enter type","field_type":"date","field_name":"start_date"},{"label":"End Date","placeholder":"e.g.enter type","field_type":"date","field_name":"end_date"}]}',
            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Sales')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Sales';
                    $new->submodule = $sm;
                    if ($sm == 'Account') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Contact') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Sales Invoice') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Sales Order') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Meeting') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Call') {
                        $new->field_json = $item[$sm];
                    }

                    $new->save();
                }
            }
        }

        $sub_module = [
            'Zoom Meeting'
        ];

        $content = [
            [
                'Zoom Meeting' => '{"field":[{"label":"Start Date/Time","placeholder":"Select Date/Time","field_type":"datetime-local","field_name":"start_date"}]}',

            ]
        ];


        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'ZoomMeeting')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'ZoomMeeting';
                    $new->submodule = $sm;
                    if ($sm == 'Zoom Meeting') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }


        $sub_module = [
           'Commission plan'
        ];
        $content = [
            [

                'Commission plan' => '{"field":[{"label":"Start Date","placeholder":"e.g.enter type","field_type":"date","field_name":"start_date"},{"label":"End Date","placeholder":"e.g.enter type","field_type":"date","field_name":"end_date"}]}',
            ]
        ];

        foreach ($sub_module as $sm) {
            foreach ($content as $key => $item) {
                $check = NewsletterModule::where('module', 'Commission')->where('submodule', $sm)->first();
                if (!$check) {
                    $new = new NewsletterModule();
                    $new->module = 'Commission';
                    $new->submodule = $sm;
                    $new->type = 'company';
                    if ($sm == 'Commission plan') {
                        $new->field_json = $item[$sm];
                    }
                    $new->save();
                }
            }
        }

        $sub_module = [
            'Student','Admission'
         ];
         $content = [
             [

                 'Student' => '{"field":[{"label":"Student Number","placeholder":"e.g.enter number","field_type":"number","field_name":"student_number"},{"label":"Class","placeholder":"e.g.select class","field_type":"select","field_name":"class","model_name": "Classroom"}]}',
                 'Admission' => '{"field":[{"label":"Admission Date","placeholder":"e.g.enter date","field_type":"date","field_name":"admission_date"},{"label":"Gender","placeholder":"select date","field_type":"radio","field_name":"gender"}]}',
            ]
         ];

         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'School')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'School';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Student') {
                         $new->field_json = $item[$sm];
                     }
                     if ($sm == 'Admission') {
                        $new->field_json = $item[$sm];
                    }
                     $new->save();
                 }
             }
         }

         $sub_module = [
            'Driver','Vehicle','Vehicle Booking'
         ];
         $content = [
             [

                 'Driver' => '{"field":[{"label":"Licence Number","placeholder":"e.g.enter number","field_type":"number","field_name":"lincese_number"},{"label":"Licence Type","placeholder":"e.g.select licence type","field_type":"select","field_name":"lincese_type","model_name": "License"}]}',
                 'Vehicle' => '{"field":[{"label":"Vehicle Name","placeholder":"e.g.enter vehicle name","field_type":"text","field_name":"vehicle_name"},{"label":"Vehicle Type","placeholder":"e.g.select vehicle type","field_type":"select","field_name":"vehicle_type","model_name": "VehicleType"},{"label":"Fuel Type","placeholder":"e.g.select fuel type","field_type":"select","field_name":"fuel_type","model_name": "FuelType"}]}',
                 'Vehicle Booking' => '{"field":[{"label":"Start Date/Time","placeholder":"Select Date/Time","field_type":"datetime-local","field_name":"start_date" },{"label":"End Date/Time","placeholder":"Select Date/Time","field_type":"datetime-local","field_name":"end_date" }]}',

            ]
         ];

         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'Fleet')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'Fleet';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Driver') {
                         $new->field_json = $item[$sm];
                     }
                     if ($sm == 'Vehicle') {
                        $new->field_json = $item[$sm];
                    }
                    if ($sm == 'Vehicle Booking') {
                        $new->field_json = $item[$sm];
                    }
                     $new->save();
                 }
             }
         }


         $sub_module = [
            'Car purchase','Car sale'
         ];
         $content = [
             [

                 'Car purchase' => '{"field":[{"label":"Purchase Date","placeholder":"select date","field_type":"date","field_name":"purchase_date"},{"label":"Due Date","placeholder":"select date","field_type":"date","field_name":"due_date"},{"label":"status","placeholder":"e.g.Select status","field_type":"select","field_name":"status","model_name": "CarPurchase"}]}',
                 'Car sale' => '{"field":[{"label":"Issue Date","placeholder":"e.g.enter date","field_type":"date","field_name":"issue_date"},{"label":"Due Date","placeholder":"select date","field_type":"date","field_name":"due_date"},{"label":"status","placeholder":"e.g.Select status","field_type":"select","field_name":"status","model_name": "CarSale"}]}',
            ]
         ];

         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'CarDealership')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'CarDealership';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Car purchase') {
                         $new->field_json = $item[$sm];
                     }
                     if ($sm == 'Car sale') {
                        $new->field_json = $item[$sm];
                    }
                     $new->save();
                 }
             }
         }

         $sub_module = [
            'Inquiries'
         ];
         $content = [
             [

                 'Inquiries' => '{"field":[{"label":"Inquiry Date","placeholder":"select date","field_type":"date","field_name":"inquiry_date"},{"label":"Gender","placeholder":"select date","field_type":"radio","field_name":"gender"}]}',
            ]
         ];


         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'ChildcareManagement')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'ChildcareManagement';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Inquiries') {
                         $new->field_json = $item[$sm];
                     }

                     $new->save();
                 }
             }
         }


         $sub_module = [
            'Tourist Inquiry','Tourist Booking'
         ];
         $content = [
             [
                 'Tourist Inquiry' => '{"field":[{"label":"Tour Name","placeholder":"select tour","field_type":"select","field_name":"tour_name","model_name": "Tour"},{"label":"Tour Start Date","placeholder":"select date","field_type":"date","field_name":"tour_start_date"}]}',
                 'Tourist Booking' => '{"field":[{"label":"Tour Name","placeholder":"select tour","field_type":"select","field_name":"tour_name","model_name": "Tour"},{"label":"Tour Start Date","placeholder":"select date","field_type":"date","field_name":"tour_start_date"},{"label":"Payment Status","placeholder":"select cleaning status","field_type":"select","field_name":"payment_status","model_name": "TourBooking"}]}',
                 ]
         ];


         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'TourTravelManagement')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'TourTravelManagement';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Tourist Inquiry') {
                         $new->field_json = $item[$sm];
                     }
                     if ($sm == 'Tourist Booking') {
                        $new->field_json = $item[$sm];
                    }
                     $new->save();
                 }
             }
         }



        $sub_module = [
            'Parking'
         ];
         $content = [
             [

                 'Parking' => '{"field":[{"label":"Vehicle Name","placeholder":"e.g enter vehicle name","field_type":"text","field_name":"vehicle"},{"label":"Vehicle Number","placeholder":"e.g enter vehicle number","field_type":"text","field_name":"vehicle_number"},{"label":"status","placeholder":"e.g.Select status","field_type":"select","field_name":"status","model_name": "Parking"}]}',
            ]
         ];


         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'ParkingManagement')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'ParkingManagement';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Parking') {
                         $new->field_json = $item[$sm];
                     }
                     $new->save();
                 }
             }
         }

         $sub_module = [
            'Booking'
         ];
         $content = [
             [
                'Booking' => '{"field":[{"label":"Service","placeholder":"select service","field_type":"select","field_name":"service","model_name": "BeautyService"},{"label":"Booking Date","placeholder":"select date","field_type":"date","field_name":"date"}]}',
            ]
         ];


         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'BeautySpaManagement')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'BeautySpaManagement';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Booking') {
                         $new->field_json = $item[$sm];
                     }
                     $new->save();
                 }
             }
         }

         $sub_module = [
            'Collection Requests'
         ];
         $content = [
             [
                'Collection Requests' => '{"field":[{"label":"Category","placeholder":"select category","field_type":"select","field_name":"category","model_name": "WasteCategory"},{"label":"Pickup Points","placeholder":"select pickup points","field_type":"select","field_name":"pickup_point","model_name": "WastePickupPoints"}]}',
            ]
         ];


         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'WasteManagement')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'WasteManagement';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Collection Requests') {
                         $new->field_json = $item[$sm];
                     }
                     $new->save();
                 }
             }
         }

         $sub_module = [
            'Cleaning Team'
         ];
         $content = [
             [
                'Cleaning Team' => '{"field":[{"label":"Cleaning Team Name","placeholder":"e.g.enter team name","field_type":"text","field_name":"team_name"},{"label":"Status","placeholder":"select cleaning status","field_type":"select","field_name":"status","model_name": "CleaningTeam"}]}',
            ]
         ];


         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'CleaningManagement')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'CleaningManagement';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Cleaning Team') {
                         $new->field_json = $item[$sm];
                     }
                     $new->save();
                 }
             }
         }

         $sub_module = [
            'Sales Agents'
         ];
         $content = [
             [
                'Sales Agents' => '{"field":[{"label":"Country","placeholder":"e.g.enter country","field_type":"text","field_name":"country"},{"label":"State","placeholder":"e.g.enter state","field_type":"text","field_name":"state"},{"label":"City","placeholder":"e.g.enter city","field_type":"text","field_name":"city"}]}',
            ]
         ];


         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'SalesAgent')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'SalesAgent';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Sales Agents') {
                         $new->field_json = $item[$sm];
                     }
                     $new->save();
                 }
             }
         }

         $sub_module = [
            'Pharmacy Bill','Pharmacy Invoice'
         ];
         $content = [
             [

                'Pharmacy Bill' => '{"field":[{"label":"Issue Date","placeholder":"e.g.enter type","field_type":"date","field_name":"issue_date"},{"label":"Due Date","placeholder":"e.g.enter type","field_type":"date","field_name":"due_date"}]}',

                'Pharmacy Invoice' =>'{"field":[{"label":"Issue Date","placeholder":"e.g.enter type","field_type":"date","field_name":"issue_date"},{"label":"Due Date","placeholder":"e.g.enter type","field_type":"date","field_name":"due_date"}]}',
                ]
         ];


         foreach ($sub_module as $sm) {
             foreach ($content as $key => $item) {
                 $check = NewsletterModule::where('module', 'PharmacyManagement')->where('submodule', $sm)->first();
                 if (!$check) {
                     $new = new NewsletterModule();
                     $new->module = 'PharmacyManagement';
                     $new->submodule = $sm;
                     $new->type = 'company';
                     if ($sm == 'Pharmacy Bill') {
                         $new->field_json = $item[$sm];
                     }
                     if ($sm == 'Pharmacy Invoice') {
                        $new->field_json = $item[$sm];
                      }

                     $new->save();
                 }
             }
         }


    }
}





