<?php

namespace Modules\WordpressWoocommerce\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Modules\ProductService\Entities\Tax;
use Modules\WordpressWoocommerce\Entities\Woocommerceconection;
use Modules\WordpressWoocommerce\Events\EditWoocommerceTax;
use Modules\WordpressWoocommerce\Events\CreateWoocommerceTax;



class WpTaxController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('woocommerce tax manage'))
        {
            $company_settings = getCompanyAllSetting();
              $api_url = (!empty($company_settings['store_url']) ? $company_settings['store_url'] : '').'/wp-json/wc/v3/';
            // Your WooCommerce API credentials
            $consumer_key =   isset($company_settings['consumer_key']) ? $company_settings['consumer_key'] : '';
            $consumer_secret = isset($company_settings['consumer_secret']) ? $company_settings['consumer_secret'] : '';

            if(!empty($consumer_key ) && !empty($consumer_secret))
            {
                // Prepare cURL request with pagination parameters
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'taxes?per_page=' . 100);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret),
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                // Execute cURL request
                $response = curl_exec($ch);
                curl_close($ch);
                // Check for errors
                if ($response === false) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return redirect()->back()->with('error', 'cURL Error: ' . $error_message);
                }

                // Decode JSON response
                $wp_taxs = json_decode($response, true);
                $wp_conection = Woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','tax')->pluck('woocomerce_id')->toarray();
                return view('wordpresswoocommerce::wptax.index',compact('wp_taxs','wp_conection'));
            }
            else
            {
                return redirect()->back()->with('error', 'Please configure the woocommerce store settings.');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('wordpresswoocommerce::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if(Auth::user()->isAbleTo('woocommerce tax create'))
        {
            $company_settings = getCompanyAllSetting();
              $api_url = (!empty($company_settings['store_url']) ? $company_settings['store_url'] : '').'/wp-json/wc/v3/';
            // Your WooCommerce API credentials
            $consumer_key =   isset($company_settings['consumer_key']) ? $company_settings['consumer_key'] : '';
            $consumer_secret = isset($company_settings['consumer_secret']) ? $company_settings['consumer_secret'] : '';

            if(!empty($consumer_key ) && !empty($consumer_secret))
            {
                // Prepare cURL request with pagination parameters
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'taxes/' . $id);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret),
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                // Execute cURL request
                $response = curl_exec($ch);
                curl_close($ch);
                // Check for errors
                if ($response === false) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return redirect()->back()->with('error', 'cURL Error: ' . $error_message);
                }

                // Decode JSON response
                $wp_taxs = json_decode($response, true);
                $tax             = new Tax();
                $tax->name       = $wp_taxs['name'];
                $tax->rate       = $wp_taxs['rate'];
                $tax->created_by = creatorId();
                $tax->workspace_id = getActiveWorkSpace();
                $tax->save();

                $Tax                   = new Woocommerceconection();
                $Tax->type             ='tax';
                $Tax->woocomerce_id    = $wp_taxs['id'];
                $Tax->original_id      =$tax->id;
                $Tax->created_by       =creatorId();
                $Tax->workspace_id     =getActiveWorkSpace();
                $Tax->save();
                event(new CreateWoocommerceTax($wp_taxs, $Tax));


                return redirect()->back()->with('success', 'Tax Successfully Created.');

            }
            else
            {
                return redirect()->back()->with('error', 'Please configure the woocommerce store settings.');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(Auth::user()->isAbleTo('woocommerce tax edit'))
        {
            $company_settings = getCompanyAllSetting();
              $api_url = (!empty($company_settings['store_url']) ? $company_settings['store_url'] : '').'/wp-json/wc/v3/';
            // Your WooCommerce API credentials
            $consumer_key =   isset($company_settings['consumer_key']) ? $company_settings['consumer_key'] : '';
            $consumer_secret = isset($company_settings['consumer_secret']) ? $company_settings['consumer_secret'] : '';

            if(!empty($consumer_key ) && !empty($consumer_secret))
            {
                // Prepare cURL request with pagination parameters
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'taxes/' . $id);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret),
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                // Execute cURL request
                $response = curl_exec($ch);
                curl_close($ch);
                // Check for errors
                if ($response === false) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return redirect()->back()->with('error', 'cURL Error: ' . $error_message);
                }

                // Decode JSON response
                $wp_taxs = json_decode($response, true);

                $wp_conection        = Woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','tax')->where('woocomerce_id' ,$id)->first();
                $tax             = Tax::where('id',$wp_conection->original_id)->where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->first();
                $Tax             = $tax;
                $Tax->name       = $wp_taxs['name'];
                $Tax->rate       = $wp_taxs['rate'];
                $Tax->created_by = creatorId();
                $Tax->workspace_id = getActiveWorkSpace();
                $Tax->save();
                event(new EditWoocommerceTax($wp_taxs, $Tax));



                return redirect()->back()->with('success', 'Tax Successfully Updated.');

            }
            else
            {
                return redirect()->back()->with('error', 'Please configure the woocommerce store settings.');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
