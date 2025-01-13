<?php

namespace Modules\Workflow\Database\Seeders;

use Illuminate\Database\Seeder;
use Nwidart\Modules\Facades\Module;
use Illuminate\Database\Eloquent\Model;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;

class WorkflowDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(PermissionTableSeeder::class);

        $this->call(WorkflowEmaildothisTableSeeder::class);
        $this->call(WorkflowSlackdothisTableSeeder::class);
        $this->call(WorkflowTelegramdothisTableSeeder::class);
        $this->call(WorkflowTwiliodothisTableSeeder::class);
        $this->call(WorkflowWebhookdothisTableSeeder::class);

        if(module_is_active('LandingPage'))
        {
            $this->call(MarketPlaceSeederTableSeeder::class);
        }

        $this_module = Module::find('Workflow');

            $this_module->enable();

            $sub_module = [
                [
                    'module' => 'general',
                    'submodule' => 'New Users',
                    'fields' => [
                        ['name' => 'email', 'type' => 'text'],
                        ['name' => 'name', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'general',
                    'submodule' => 'New Invoice',
                    'fields' => [
                        ['name' => 'price', 'type' => 'text'],
                        ['name' => 'Category', 'type' => 'select', 'model_name' => 'Category'],
                        ['name' => 'Tax', 'type' => 'select', 'model_name' => 'Tax'],
                        ['name' => 'quantity', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'general',
                    'submodule' => 'New Proposal',
                    'fields' => [
                        ['name' => 'price', 'type' => 'text'],
                        ['name' => 'Category', 'type' => 'select', 'model_name' => 'Category'],
                        ['name' => 'Tax', 'type' => 'select', 'model_name' => 'Tax'],
                        ['name' => 'quantity', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Retainer',
                    'submodule' => 'New Retainer',
                    'fields' => [
                        ['name' => 'price', 'type' => 'text'],
                        ['name' => 'Category', 'type' => 'select', 'model_name' => 'Category'],
                        ['name' => 'Tax', 'type' => 'select', 'model_name' => 'Tax'],
                        ['name' => 'quantity', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Lead',
                    'submodule' => 'New Lead',
                    'fields' => [
                        ['name' => 'Lead User', 'type' => 'select', 'model_name' => 'User'],
                        ['name' => 'subject', 'type' => 'text'],
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'email', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Taskly',
                    'submodule' => 'New Project',
                    'fields' => [
                        ['name' => 'Project User', 'type' => 'select', 'model_name' => 'User'],
                        ['name' => 'name', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Account',
                    'submodule' => 'New Customer',
                    'fields' => [
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'email', 'type' => 'text'],
                        ['name' => 'Contact', 'type' => 'number'],
                        ['name' => 'Tax Number', 'type' => 'number'],
                    ],
                ],
                [
                    'module' => 'Contract',
                    'submodule' => 'New Contract',
                    'fields' => [
                        ['name' => 'subject', 'type' => 'text'],
                        ['name' => 'Contract User', 'type' => 'select','model_name' => 'User'],
                        ['name' => 'value', 'type' => 'number'],
                        ['name' => 'Type', 'type' => 'select','model_name'=>'ContractType'],
                    ],
                ],
                [
                    'module' => 'SupportTicket',
                    'submodule' => 'New Ticket',
                    'fields' => [
                        ['name' => 'Category', 'type' => 'select','model_name' => 'TicketCategory'],
                        ['name' => 'status', 'type' => 'select','model_name' => 'Ticket'],
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'email', 'type' => 'text'],
                        ['name' => 'subject', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Lead',
                    'submodule' => 'New Deal',
                    'fields' => [
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'price', 'type' => 'number'],
                        ['name' => 'client', 'type' => 'select','model_name' => 'DealUser'],
                    ],
                ],
                [
                    'module' => 'Appointment',
                    'submodule' => 'New Appointment',
                    'fields' => [
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'Type', 'type' => 'select','model_name' => 'Appointment'],
                    ],
                ],
                [
                    'module' => 'Pos',
                    'submodule' => 'New Purchase',
                    'fields' => [
                        ['name' => 'price', 'type' => 'text'],
                        ['name' => 'Category', 'type' => 'select', 'model_name' => 'pos_Category'],
                        ['name' => 'Warehouse', 'type' => 'select', 'model_name' => 'Warehouse'],
                        ['name' => 'Tax', 'type' => 'select', 'model_name' => 'Tax'],
                        ['name' => 'quantity', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Hrm',
                    'submodule' => 'New Award',
                    'fields' => [
                        ['name' => 'Employee', 'type' => 'select', 'model_name' => 'Award'],
                        ['name' => 'Award Type', 'type' => 'select', 'model_name' => 'AwardType'],
                    ],
                ],
                [
                    'module' => 'Training',
                    'submodule' => 'New Training',
                    'fields' => [
                        ['name' => 'Branch', 'type' => 'select', 'model_name' => 'Branch'],
                        ['name' => 'Trainer Option', 'type' => 'select', 'model_name' => 'Training'],
                        ['name' => 'Training Type', 'type' => 'select', 'model_name' => 'TrainingType'],
                        ['name' => 'Trainer', 'type' => 'select', 'model_name' => 'Trainer'],
                        ['name' => 'Employee', 'type' => 'select', 'model_name' => 'Employee'],
                        ['name' => 'Training Cost', 'type' => 'number'],
                    ],
                ],
                [
                    'module' => 'CMMS',
                    'submodule' => 'New Supplier',
                    'fields' => [
                        ['name' => 'Name', 'type' => 'text'],
                        ['name' => 'Location', 'type' => 'select', 'model_name' => 'Location'],
                        ['name' => 'Contact', 'type' => 'number'],
                        ['name' => 'Email', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Sales',
                    'submodule' => 'New Contact',
                    'fields' => [
                        ['name' => 'email', 'type' => 'text'],
                        ['name' => 'city', 'type' => 'text'],
                        ['name' => 'state', 'type' => 'text'],
                        ['name' => 'country', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Sales',
                    'submodule' => 'New Opportunities',
                    'fields' => [
                        ['name' => 'Account', 'type' => 'select', 'model_name' => 'SalesAccount'],
                        ['name' => 'Opportunities Stage', 'type' => 'select', 'model_name' => 'OpportunitiesStage'],
                        ['name' => 'Amount', 'type' => 'text'],
                        ['name' => 'Probability', 'type' => 'number'],
                    ],
                ],
                [
                    'module' => 'Sales',
                    'submodule' => 'New Quote',
                    'fields' => [
                        ['name' => 'Account', 'type' => 'select', 'model_name' => 'SalesAccount'],
                        ['name' => 'Status', 'type' => 'select', 'model_name' => 'Quote'],
                        ['name' => 'Quote Number', 'type' => 'text'],
                        ['name' => 'Tax', 'type' => 'select','model_name' => 'Tax' ],
                    ],
                ],
                [
                    'module' => 'Sales',
                    'submodule' => 'New Sales Invoice',
                    'fields' => [
                        ['name' => 'Sales Quote', 'type' => 'select', 'model_name' => 'Quote'],
                        ['name' => 'Opportunity', 'type' => 'select', 'model_name' => 'Opportunities'],
                        ['name' => 'Account', 'type' => 'select', 'model_name' => 'SalesAccount'],
                        ['name' => 'Quote Number', 'type' => 'text'],
                        ['name' => 'Tax', 'type' => 'select','model_name' => 'Tax' ],
                    ],
                ],
                [
                    'module' => 'Fleet',
                    'submodule' => 'New Booking',
                    'fields' => [
                        ['name' => 'Customer Name', 'type' => 'select', 'model_name' => 'User'],
                        ['name' => 'Vehicle name', 'type' => 'select', 'model_name' => 'Vehicle'],
                        ['name' => 'Total price', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Fleet',
                    'submodule' => 'New Maintenance',
                    'fields' => [
                        ['name' => 'Name', 'type' => 'select', 'model_name' => 'User'],
                        ['name' => 'Vehicle name', 'type' => 'select', 'model_name' => 'Vehicle'],
                        ['name' => 'Maintenance Type', 'type' => 'select', 'model_name' => 'MaintenanceType'],
                        ['name' => 'Total price', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Fleet',
                    'submodule' => 'New Fuel History',
                    'fields' => [
                        ['name' => 'Driver Name', 'type' => 'select', 'model_name' => 'User'],
                        ['name' => 'Vehicle name', 'type' => 'select', 'model_name' => 'Vehicle'],
                        ['name' => 'Fuel Type', 'type' => 'select', 'model_name' => 'FuelType'],
                        ['name' => 'Total price', 'type' => 'text'],
                        ['name' => 'Gallons/Liters of Fuel', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'School',
                    'submodule' => 'New Admission',
                    'fields' => [
                        ['name' => 'Date Of Birth', 'type' => 'date'],
                        ['name' => 'Gender', 'type' => 'select', 'model_name' => 'Workflow'],
                        ['name' => 'State', 'type' => 'text'],
                        ['name' => 'City', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Holidayz',
                    'submodule' => 'New Room Booking',
                    'fields' => [
                        ['name' => 'Room', 'type' => 'select','model_name' => 'Rooms'],
                        ['name' => 'Total Room', 'type' => 'number'],
                        ['name' => 'Children', 'type' => 'number'],
                        ['name' => 'Adults', 'type' => 'number'],
                        ['name' => 'Rent', 'type' => 'number'],
                    ],
                ],
                [
                    'module' => 'GymManagement',
                    'submodule' => 'New Trainer',
                    'fields' => [
                        ['name' => 'Skill', 'type' => 'select','model_name' => 'Skill'],
                        ['name' => 'Member', 'type' => 'select','model_name' => 'olor fuga'],
                        ['name' => 'Country', 'type' => 'text'],
                        ['name' => 'City', 'type' => 'text'],
                        ['name' => 'State', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'GymManagement',
                    'submodule' => 'New Measurement',
                    'fields' => [
                        ['name' => 'Gender', 'type' => 'select','model_name' => 'Workflow'],
                        ['name' => 'Age', 'type' => 'number'],
                        ['name' => 'Weight', 'type' => 'number'],
                        ['name' => 'Height', 'type' => 'number'],
                        ['name' => 'Waist', 'type' => 'number'],
                    ],
                ],
                [
                    'module' => 'BeautySpaManagement',
                    'submodule' => 'New Beauty Booking',
                    'fields' => [
                        ['name' => 'Service', 'type' => 'select','model_name' => 'BeautyService'],
                        ['name' => 'Gender', 'type' => 'select','model_name' => 'Workflow'],
                        ['name' => 'Date', 'type' => 'date'],
                    ],
                ],
                [
                    'module' => 'AgricultureManagement',
                    'submodule' => 'New Agriculture Fleet',
                    'fields' => [
                        ['name' => 'Capacity', 'type' => 'text'],
                        ['name' => 'Status', 'type' => 'select','model_name' => 'AgricultureFleet'],
                        ['name' => 'Price', 'type' => 'text'],
                        ['name' => 'Quantity', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'AgricultureManagement',
                    'submodule' => 'New Agriculture Service',
                    'fields' => [
                        ['name' => 'Activity', 'type' => 'select', 'model_name' => 'AgricultureActivities'],
                        ['name' => 'Quantity', 'type' => 'number'],
                        ['name' => 'UOM', 'type' => 'text'],
                        ['name' => 'Total Price', 'type' => 'number'],
                    ],
                ],
                [
                    'module' => 'AgricultureManagement',
                    'submodule' => 'New Agriculture Cultivation',
                    'fields' => [
                        ['name' => 'Farmer', 'type' => 'select', 'model_name' => 'AgricultureUser'],
                        ['name' => 'Agriculture Cycle', 'type' => 'select', 'model_name' => 'AgricultureCycles'],
                        ['name' => 'Department', 'type' => 'select', 'model_name' => 'AgricultureDepartment'],
                        ['name' => 'Area', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'AgricultureManagement',
                    'submodule' => 'New Agriculture Crop',
                    'fields' => [
                        ['name' => 'Season', 'type' => 'select', 'model_name' => 'AgricultureSeason'],
                        ['name' => 'Cycles', 'type' => 'select', 'model_name' => 'AgricultureCycles'],
                        ['name' => 'Fleets', 'type' => 'select', 'model_name' => 'AgricultureFleet'],
                        ['name' => 'Equipments','type' => 'select', 'model_name' => 'AgricultureEquipment'],
                        ['name' => 'Processes','type' => 'select', 'model_name' => 'AgricultureProcess'],
                    ],
                ],
                [
                    'module' => 'GarageManagement',
                    'submodule' => 'New Vehicle',
                    'fields' => [
                        ['name' => 'Vehicle Type', 'type' => 'select', 'model_name' => 'VehicleType'],
                        ['name' => 'Vehicle Brand', 'type' => 'select', 'model_name' => 'VehicleBrand'],
                        ['name' => 'Vehicle Color', 'type' => 'select', 'model_name' => 'VehicleColor'],
                        ['name' => 'Vehicle FuelType','type' => 'select', 'model_name' => 'FuelType'],
                        ['name' => 'Cost','type' => 'text'],
                    ],
                ],
                [
                    'module' => 'GarageManagement',
                    'submodule' => 'New Service',
                    'fields' => [
                        ['name' => 'Assign To', 'type' => 'select', 'model_name' => 'User'],
                        ['name' => 'Vehicle Name', 'type' => 'select', 'model_name' => 'VehicleType'],
                        ['name' => 'Service Category', 'type' => 'select', 'model_name' => 'RepairCategory'],
                        ['name' => 'Service Type','type' => 'select', 'model_name' => 'Workflow'],
                        ['name' => 'Wash','type' => 'select', 'model_name' => 'Workflow'],
                        ['name' => 'Service Charge','type' => 'number'],
                    ],
                ],
                [
                    'module' => 'TourTravelManagement',
                    'submodule' => 'New Tour',
                    'fields' => [
                        ['name' => 'Tour Days', 'type' => 'number'],
                        ['name' => 'Transport Type', 'type' => 'select', 'model_name' => 'TransportType'],
                        ['name' => 'Season Name', 'type' => 'select', 'model_name' => 'Season'],
                        ['name' => 'Total Seat','type' => 'number'],
                        ['name' => 'Adult Price','type' => 'number'],
                        ['name' => 'Child Price','type' => 'number'],
                    ],
                ],
                [
                    'module' => 'Newspaper',
                    'submodule' => 'New Newspaper',
                    'fields' => [
                        ['name' => 'Varient', 'type' => 'select','model_name' => 'NewspaperVarient'],
                        ['name' => 'Tax', 'type' => 'select','model_name' => 'Tax'],
                        ['name' => 'Quantity', 'type' => 'number'],
                        ['name' => 'Price','type' => 'number'],
                        ['name' => 'Seles Price','type' => 'number'],
                    ],
                ],
                [
                    'module' => 'PropertyManagement',
                    'submodule' => 'New Unit',
                    'fields' => [
                        ['name' => 'Property', 'type' => 'select','model_name' => 'Property'],
                        ['name' => 'Bedroom', 'type' => 'number'],
                        ['name' => 'Baths', 'type' => 'number'],
                        ['name' => 'Kitchen','type' => 'number'],
                        ['name' => 'Rent Type','type' => 'select','model_name' => 'Workflow'],
                        ['name' => 'Rent','type' => 'number'],
                    ],
                ],
                [
                    'module' => 'ChildcareManagement',
                    'submodule' => 'New Inquiry',
                    'fields' => [
                        ['name' => 'Date Of Birth', 'type' => 'date'],
                        ['name' => 'Inquiry Date', 'type' => 'date'],
                        ['name' => 'Child Age', 'type' => 'number'],
                        ['name' => 'Gender','type' => 'select','model_name' => 'Workflow'],

                    ],
                ],
                [
                    'module' => 'ChildcareManagement',
                    'submodule' => 'New Child',
                    'fields' => [
                        ['name' => 'Date Of Birth', 'type' => 'date'],
                        ['name' => 'Gender','type' => 'select','model_name' => 'Workflow'],
                        ['name' => 'Age', 'type' => 'number'],

                    ],
                ],
                [
                    'module' => 'HospitalManagement',
                    'submodule' => 'New Doctor',
                    'fields' => [
                        ['name' => 'Specialization', 'type' => 'select','model_name' => 'Specialization'],
                        ['name' => 'Gender','type' => 'select','model_name' => 'Workflow'],
                        ['name' => 'Years Of Experience', 'type' => 'number'],
                        ['name' => 'Consultation Fee', 'type' => 'number'],

                    ],
                ],
                [
                    'module' => 'HospitalManagement',
                    'submodule' => 'New Medicine',
                    'fields' => [
                        ['name' => 'Manufacturer', 'type' => 'select','model_name' => 'User'],
                        ['name' => 'Price Per Unit','type' => 'number'],
                        ['name' => 'Medicine Category', 'type' => 'select','model_name' => 'MedicineCategory'],
                        ['name' => 'Quantity Available', 'type' => 'number'],

                    ],
                ],
                [
                    'module' => 'HospitalManagement',
                    'submodule' => 'New Bed',
                    'fields' => [
                        ['name' => 'Bed Type', 'type' => 'select','model_name' => 'BedType'],
                        ['name' => 'Ward', 'type' => 'select','model_name' => 'Ward'],
                        ['name' => 'Charges', 'type' => 'number'],
                        ['name' => 'Status', 'type' => 'select','model_name' => 'HospitalBed'],

                    ],
                ],
                [
                    'module' => 'LaundryManagement',
                    'submodule' => 'New Laundry Request',
                    'fields' => [
                        ['name' => 'Services', 'type' => 'select','model_name' => 'LaundryService'],
                        ['name' => 'Location', 'type' => 'select','model_name' => 'LaundryLocation'],

                    ],
                ],

            ];

            foreach ($sub_module as $sm) {
                $check = WorkflowModule::where('module', $sm['module'])->where('submodule', $sm['submodule'])->first();

                if (!$check) {
                    $sub_module_record = WorkflowModule::create([
                        'module' => $sm['module'],
                        'submodule' => $sm['submodule'],
                        'type' => 'company',
                    ]);
                } else {
                    $sub_module_record = $check;
                }

                foreach ($sm['fields'] as $field) {
                    WorkflowModuleField::updateOrInsert([
                        'workmodule_id' => $sub_module_record->id,
                        'field_name' => $field['name'],
                    ], [
                        'input_type' => $field['type'],
                        'model_name' => isset($field['model_name']) ? $field['model_name'] : null,
                    ]);
                }

            }
        }
    }
