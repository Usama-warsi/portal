<?php

namespace Modules\Spreadsheet\Providers;
use  Modules\Spreadsheet\Entities\Related;
use  Modules\Spreadsheet\Entities\Spreadsheets;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot()
    {

        view()->composer(['taskly::projects.show'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "projects.show")
            {
                try {
                    $ids = \Request::segment(2);
                    if(!empty($ids))
                    {
                        try {
                            $id = $ids;
                            $related = Related::where('model_name','Project')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();
                            $module = 'Project';

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related','module')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['contract::contracts.show'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "contract.show")
            {
                try {
                    $ids = \Request::segment(2);

                    if(!empty($ids))
                    {
                        try {
                            $id = $ids;
                            $related = Related::where('model_name','Contract')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('contractButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['lead::leads.show'], function ($view)
        {
            $route = \Request::route()->getName();

            if($route == "leads.show")
            {
                try {
                    $ids = \Request::segment(2);
                    if(!empty($ids))
                    {
                        try {
                            $id = $ids;
                            $related = Related::where('model_name','Lead')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['lead::deals.show'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "deals.show")
            {
                try {
                    $ids = \Request::segment(2);
                    if(!empty($ids))
                    {
                        try {
                            $id = $ids;
                            $related = Related::where('model_name','Deal')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });


        view()->composer(['tourtravelmanagement::tour.index'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "tour.index")
            {
                try {
                    $ids = \Request::segment(1);
                    if(!empty($ids))
                    {
                        try {
                            $id = 0;
                            $related = Related::where('model_name','Tour')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['hospitalmanagement::doctor.index'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "doctor.index")
            {
                try {
                    $ids = \Request::segment(1);
                    if(!empty($ids))
                    {
                        try {
                            $id = 0;
                            $related = Related::where('model_name','Hospital')->first();

                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['purchases.index'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "purchases.index")
            {
                try {
                    $ids = \Request::segment(1);
                    if(!empty($ids))
                    {
                        try {
                            $id = 0;
                            $related = Related::where('model_name','Purchase')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });


        view()->composer(['cmms::workorder.view'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "workorder.show")
            {
                try {
                    $ids = \Request::segment(2);
                    if(!empty($ids))
                    {
                        try {
                            $id = $ids;
                            $related = Related::where('model_name','WorkOrder')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();
                            $module = 'CMMS';

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related','module')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['lms::course.index'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "course.index")
            {
                try {
                    $ids = \Request::segment(1);
                    if(!empty($ids))
                    {
                        try {
                            $id = 0;
                            $related = Related::where('model_name','Course')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['sales::salesaccount.index'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "salesaccount.index")
            {
                try {
                    $ids = \Request::segment(1);
                    if(!empty($ids))
                    {
                        try {
                            $id = 0;
                            $related = Related::where('model_name','Salesaccount')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['school::admission.index'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "admission.index")
            {
                try {
                    $ids = \Request::segment(1);
                    if(!empty($ids))
                    {
                        try {
                            $id = 0;
                            $related = Related::where('model_name','Admission')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['newspaper::newspaper.index'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "newspaper.index")
            {
                try {
                    $ids = \Request::segment(1);

                    if(!empty($ids))
                    {
                        try {
                            $id = 0;
                            $related = Related::where('model_name','Newspaper')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

        view()->composer(['assets::index'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "asset.index")
            {
                try {
                    $ids = \Request::segment(1);
                    if(!empty($ids))
                    {
                        try {
                            $id = 0;
                            $related = Related::where('model_name','Assets')->first();
                            $spreadsheets = Spreadsheets::where('related',$related->id)->get();

                            if(!is_null($spreadsheets) && (is_array($spreadsheets) || count($spreadsheets) > 0))
                            {
                                $view->getFactory()->startPush('addButtonHook', view('spreadsheet::layouts.addhook', compact('id','related')));
                            }

                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });

    }

    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
