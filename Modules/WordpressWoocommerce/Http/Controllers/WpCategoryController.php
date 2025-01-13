<?php

namespace Modules\WordpressWoocommerce\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Modules\ProductService\Entities\Category;
use Modules\WordpressWoocommerce\Entities\Woocommerceconection;
use Modules\WordpressWoocommerce\Events\CreateWoocommerceCategory;
use Modules\WordpressWoocommerce\Events\EditWoocommerceCategory;

class WpCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('woocommerce category manage'))
        {
            $company_settings = getCompanyAllSetting();
            $api_url = (isset($company_settings['store_url']) ? $company_settings['store_url'] : '').'/wp-json/wc/v3/';
            // Your WooCommerce API credentials
            $consumer_key =   isset($company_settings['consumer_key']) ? $company_settings['consumer_key'] : '';
            $consumer_secret = isset($company_settings['consumer_secret']) ? $company_settings['consumer_secret'] : '';

            if(!empty($consumer_key ) && !empty($consumer_secret))
            {
                // Prepare cURL request with pagination parameters
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'products/categories?per_page=' . 100);
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
                $wp_categorys = json_decode($response, true);
                $wp_conection = Woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','category')->pluck('woocomerce_id')->toarray();
                return view('wordpresswoocommerce::wpcategory.index',compact('wp_categorys','wp_conection'));
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
    public function store(Request $request ,$id)
    {
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if(Auth::user()->isAbleTo('woocommerce category create'))
        {
            $company_settings = getCompanyAllSetting();
            $api_url = (isset($company_settings['store_url']) ? $company_settings['store_url'] : '').'/wp-json/wc/v3/';
            // Your WooCommerce API credentials
            $consumer_key =   isset($company_settings['consumer_key']) ? $company_settings['consumer_key'] : '';
            $consumer_secret = isset($company_settings['consumer_secret']) ? $company_settings['consumer_secret'] : '';

            if(!empty($consumer_key ) && !empty($consumer_secret))
            {
                // Prepare cURL request with pagination parameters
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'products/categories/' . $id);

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
                $wp_categorys = json_decode($response, true);
                $category             = new Category();
                $category->name       = $wp_categorys['name'];
                $category->color      = '#fc544b'	;
                $category->type       = 0;
                $category->created_by       = creatorId();
                $category->workspace_id     =  getActiveWorkSpace();
                $category->save();

                $Category                   = new Woocommerceconection();
                $Category->type             = 'category';
                $Category->woocomerce_id    = $wp_categorys['id'];
                $Category->original_id      =$category->id;
                $Category->created_by       = creatorId();
                $Category->workspace_id     =  getActiveWorkSpace();
                $Category->save();
                event(new CreateWoocommerceCategory($wp_categorys, $Category));


                return redirect()->back()->with('success', 'Categories Successfully Created.');
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
        if(Auth::user()->isAbleTo('woocommerce category edit'))
        {
            $company_settings = getCompanyAllSetting();
            $api_url = (isset($company_settings['store_url']) ? $company_settings['store_url'] : '').'/wp-json/wc/v3/';
            // Your WooCommerce API credentials
            $consumer_key =   isset($company_settings['consumer_key']) ? $company_settings['consumer_key'] : '';
            $consumer_secret = isset($company_settings['consumer_secret']) ? $company_settings['consumer_secret'] : '';

            if(!empty($consumer_key ) && !empty($consumer_secret))
            {
                // Prepare cURL request with pagination parameters
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'products/categories/' . $id);

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
                $wp_categorys = json_decode($response, true);
                $wp_conection        = Woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','category')->where('woocomerce_id' ,$id)->first();
                $category             = Category::where('id',$wp_conection->original_id)->where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type',0)->first();
                $Category                   = $category;
                $Category->name             = $wp_categorys['name'];
                $Category->created_by       = creatorId();
                $Category->workspace_id     =  getActiveWorkSpace();
                $Category->save();
                event(new EditWoocommerceCategory($wp_categorys, $Category));



                return redirect()->back()->with('success', 'Categories Successfully Updated.');
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
