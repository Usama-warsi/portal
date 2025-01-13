<?php

namespace Modules\WordpressWoocommerce\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\WordpressWoocommerce\Entities\Woocommerceconection;
use Modules\ProductService\Entities\ProductService;
use Modules\WordpressWoocommerce\Events\EditWoocommerceProduct;
use Modules\WordpressWoocommerce\Events\CreateWoocommerceProduct;



use Illuminate\Routing\Controller;

class WpProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('woocommerce product manage'))
        {
            $company_settings = getCompanyAllSetting();
            $api_url = (isset($company_settings['store_url']) ? $company_settings['store_url'] : '').'/wp-json/wc/v3/';
            // Your WooCommerce API credentials
            $consumer_key =   isset($company_settings['consumer_key']) ? $company_settings['consumer_key'] : '';
            $consumer_secret = isset($company_settings['consumer_secret']) ? $company_settings['consumer_secret'] : '';
            if(!empty($consumer_key ) && !empty($consumer_secret))
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'products?per_page=' . 100);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret),
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response = curl_exec($ch);
                curl_close($ch);
                if ($response === false) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return redirect()->back()->with('error', 'cURL Error: ' . $error_message);
                }

                $wp_products = json_decode($response, true);
                $wp_conection        = Woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','product')->pluck('woocomerce_id')->toarray();

                return view('wordpresswoocommerce::wpproduct.index', compact('wp_products','wp_conection'));
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
        if(Auth::user()->isAbleTo('woocommerce product create'))
        {
            $company_settings = getCompanyAllSetting();
            $api_url = (isset($company_settings['store_url']) ? $company_settings['store_url'] : '').'/wp-json/wc/v3/';
            // Your WooCommerce API credentials
            $consumer_key =   isset($company_settings['consumer_key']) ? $company_settings['consumer_key'] : '';
            $consumer_secret = isset($company_settings['consumer_secret']) ? $company_settings['consumer_secret'] : '';
            if(!empty($consumer_key ) && !empty($consumer_secret))
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'products/' . $id);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret),
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response = curl_exec($ch);
                curl_close($ch);
                if ($response === false) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return redirect()->back()->with('error', 'cURL Error: ' . $error_message);
                }

                $wp_products = json_decode($response, true);

                // tax
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'taxes?per_page=' . 100);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret),
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response_tax = curl_exec($ch);
                curl_close($ch);
                if ($response_tax === false) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return redirect()->back()->with('error', 'cURL Error: ' . $error_message);
                }

                $wp_tax     = json_decode($response_tax, true);
                $wp_tax     = collect($wp_tax);

                $tax_check  = $wp_tax->where('class' , $wp_products['tax_class'])->pluck('id')->toArray();
                $tax = woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','tax')->whereIn('woocomerce_id' , $tax_check)->pluck('original_id')->toArray();


                // category
                $categories = collect($wp_products['categories']);
                $categories = $categories->pluck('id')->toArray();
                $cat = woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','category')->whereIn('woocomerce_id' , $categories)->pluck('original_id')->first();
                if(empty($cat)){
                    return redirect()->back()->with('error', __('Add Woocommerce Product Category'));
                }

                $productService                 = new ProductService();
                $productService->name           = $wp_products['name'];
                $productService->description    = $wp_products['description'];
                $productService->sku            = $wp_products['sku'];
                if($wp_products['images']){
                    $url    =$wp_products['images'][0]['src'];
                    $path   = '/products/';

                    $file_type = config('files_types');
                    foreach($file_type as $f){
                        $name = basename($url, ".".$f);
                    }
                    $file2 = rand(10,100).'_'.time() . "_" . $name;

                    $path =Woocommerceconection::upload_woo_file($url,$file2,$path);

                    $productService->image          = empty($path) ? null : '/uploads'.$path['url'];
                }
                $productService->sale_price     = $wp_products['sale_price'];
                $productService->purchase_price = $wp_products['price'];
                if(!empty($tax)){
                    $productService->tax_id         = implode(',', $tax);
                }else{
                    return redirect()->back()->with('error', __('Add Woocommerce Product Tax'));

                }
                $productService->unit_id        = 0;
                if(!empty($wp_products['stock_quantity']))
                {
                    $productService->quantity        = $wp_products['stock_quantity'];
                }
                else{
                    $productService->quantity   = 0;
                }
                $productService->type           = 'product';

                $productService->category_id    = $cat;
                $productService->created_by     = creatorId();
                $productService->workspace_id     = getActiveWorkSpace();
                $productService->save();


                $Product                   = new Woocommerceconection();
                $Product->type             = 'product';
                $Product->woocomerce_id    = $wp_products['id'];
                $Product->original_id      =$productService->id;
                $Product->created_by       = creatorId();
                $Product->workspace_id     =  getActiveWorkSpace();
                $Product->save();
                event(new CreateWoocommerceProduct($wp_products, $Product));



                return redirect()->back()->with('success', 'Product Create Successfully.');


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
        if(Auth::user()->isAbleTo('woocommerce product edit'))
        {
            $company_settings = getCompanyAllSetting();
            $api_url = (isset($company_settings['store_url']) ? $company_settings['store_url'] : '').'/wp-json/wc/v3/';
            // Your WooCommerce API credentials
            $consumer_key =   isset($company_settings['consumer_key']) ? $company_settings['consumer_key'] : '';
            $consumer_secret = isset($company_settings['consumer_secret']) ? $company_settings['consumer_secret'] : '';
            if(!empty($consumer_key ) && !empty($consumer_secret))
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'products/' . $id);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret),
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response = curl_exec($ch);
                curl_close($ch);
                if ($response === false) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return redirect()->back()->with('error', 'cURL Error: ' . $error_message);
                }

                $wp_products = json_decode($response, true);

                // tax
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url . 'taxes?per_page=' . 100);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Basic ' . base64_encode($consumer_key . ':' . $consumer_secret),
                ));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response_tax = curl_exec($ch);
                curl_close($ch);
                if ($response_tax === false) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return redirect()->back()->with('error', 'cURL Error: ' . $error_message);
                }

                $wp_tax     = json_decode($response_tax, true);
                $wp_tax     = collect($wp_tax);

                $tax_check  = $wp_tax->where('class' , $wp_products['tax_class'])->pluck('id')->toArray();
                $tax = woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','tax')->whereIn('woocomerce_id' , $tax_check)->pluck('original_id')->toArray();


                // category
                $categories = collect($wp_products['categories']);
                $categories = $categories->pluck('id')->toArray();
                $cat = woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','category')->whereIn('woocomerce_id' , $categories)->pluck('original_id')->first();
                if(empty($cat)){
                    return redirect()->back()->with('error', __('Add Woocommerce Product Category'));
                }

                $ProductService = woocommerceconection::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type','product')->where('woocomerce_id',$wp_products['id'])->first();
                $Product = ProductService::find($ProductService->original_id);
                $productService                 = $Product ;
                $productService->name           = $wp_products['name'];
                $productService->description    = $wp_products['description'];
                $productService->sku            = $wp_products['sku'];
                if($wp_products['images']){
                    $url    =$wp_products['images'][0]['src'];
                    $path   = '/products/';

                    $file_type = config('files_types');
                    foreach($file_type as $f){
                        $name = basename($url, ".".$f);
                    }
                    $file2 = rand(10,100).'_'.time() . "_" . $name;

                    $path =Woocommerceconection::upload_woo_file($url,$file2,$path);

                    $productService->image          = empty($path) ? null : '/uploads'.$path['url'];
                }
                $productService->sale_price     = $wp_products['sale_price'];
                $productService->purchase_price = $wp_products['price'];
                if(!empty($tax)){
                    $productService->tax_id         = implode(',', $tax);
                }else{
                    return redirect()->back()->with('error', __('Add Woocommerce Product Tax'));

                }
                $productService->unit_id        = 0;
                if(!empty($wp_products['stock_quantity']))
                {
                    $productService->quantity        = $wp_products['stock_quantity'];
                }
                else{
                    $productService->quantity   = 0;
                }
                $productService->type           = 'product';

                $productService->category_id    = $cat;
                $productService->created_by     = creatorId();
                $productService->workspace_id   = getActiveWorkSpace();
                $productService->save();
                event(new EditWoocommerceProduct($wp_products, $productService));


                return redirect()->back()->with('success', 'Product successfully updated.');


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
