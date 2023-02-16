<?php

namespace App\Http\Controllers\Client;

use Image;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Banner, Vendor, Category, ClientLanguage,Client};

class BannerController extends BaseController
{
    private $folderName = 'banner';
    private $fstatus = 1;
    public $client_id =0;
    public function __construct()
    {
        $this->client_id = getWebClientID();
        $code = Client::where('id',getWebClientID())->value('code');
        // $this->folderName = '/'.$code.'/banner';
        $this->client_id = $code = getWebClientID();
        $this->folderName = 'pictures/'.$code.'/banner/';
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */   
    public function index()
    {
        $banners = Banner::where('client_id',$this->client_id)->orderBy('sorting', 'asc')->get();
        return view('backend/banner/index')->with(['banners' => $banners]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $categories = Category::with(['translation' => function($q) use($langId){
            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
            ->where('category_translations.language_id', $langId);
        }])
        ->where('status', 1)
        // ->where('can_add_products', 1)
        ->where('id', '>', 1)
        ->whereNull('vendor_id')
        ->orderBy('parent_id', 'asc')
        ->orderBy('position', 'asc')
        ->get();
        foreach($categories as $key => $category){
            $category->translation_name = ($category->translation->first()) ? $category->translation->first()->name : $category->slug;
        }
        $categories_hierarchy = '';
        if($categories){
            $categories_build = $this->buildTree($categories->toArray());
            $categories_hierarchy = $this->printCategoryOptionsHeirarchy($categories_build);
            foreach($categories_hierarchy as $k => $cat){
                if ($cat['can_add_products'] != 1) {
                    unset($categories_hierarchy[$k]);
                }
            }
        }
        $vendors = Vendor::select('id', 'name')->where('client_id',$this->client_id)->where('status', $this->fstatus)->get();
        $banner = new Banner();
        $returnHTML = view('backend.banner.form')->with(['banner' => $banner,  'vendors' => $vendors, 'categories' => $categories_hierarchy])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $banner = Banner::where('id', $id)->where('client_id',$this->client_id)->first();
        $categories = Category::with(['translation' => function($q) use($langId){
            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
            ->where('category_translations.language_id', $langId);
        }])
        ->where('status', 1)
        // ->where('can_add_products', 1)
        ->where('id', '>', 1)
        ->whereNull('vendor_id')
        ->orderBy('parent_id', 'asc')
        ->orderBy('position', 'asc')
        ->get();
        foreach($categories as $key => $category){
            $category->translation_name = ($category->translation->first()) ? $category->translation->first()->name : $category->slug;
        }
        $categories_hierarchy = '';
        if($categories){
            $categories_build = $this->buildTree($categories->toArray());
            $categories_hierarchy = $this->printCategoryOptionsHeirarchy($categories_build);
            foreach($categories_hierarchy as $k => $cat){
                if ($cat['can_add_products'] != 1) {
                    unset($categories_hierarchy[$k]);
                }
            }
        }
        $vendors = Vendor::select('id', 'name')->where('client_id',$this->client_id)->where('status', $this->fstatus)->get();
        $returnHTML = view('backend.banner.form')->with(['banner' => $banner,  'vendors' => $vendors, 'categories' => $categories_hierarchy])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name' => 'required|string|max:150',
            'start_date_time' => 'required|before:end_date_time',
            'end_date_time' => 'required|after:start_date_time',
        );

        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif,webp';
        }
        
        if ($request->hasFile('image_mobile')) {    /* upload logo file */
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif,webp';
        }

    
        $validation  = Validator::make($request->all(), $rules)->validate();
        $banner = new Banner();
        $banner->client_id = $this->client_id;
        $banner->description = $request->description;
        $savebanner = $this->save($request, $banner, 'false');
    

        if($savebanner > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Banner created Successfully!',
                'data' => $banner
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $rules = array(
            'name' => 'required|string|max:150',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
        );
        if ($request->hasFile('image')) {    /* upload logo file */
            $rules['image'] =  'image|mimes:jpeg,png,jpg,gif,webp';
        }
        $validation  = Validator::make($request->all(), $rules)->validate();

        $banner = Banner::where('id', $id)->where('client_id',$this->client_id)->first();
        $banner->client_id = $this->client_id;

 $banner->description = $request->description;
        $savebanner = $this->save($request, $banner, 'true');
        if($savebanner > 0){
            return response()->json([
                'status'=>'success',
                'message' => 'Banner updated Successfully!',
                'data' => $banner
            ]);
        }
    }

    /**
     * save and update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, Banner $banner, $update = 'false')
    {
        $banner->validity_on = ($request->has('validity_on') && $request->validity_on == 'on') ? 1 : 0; 
        $banner->name = $request->name;
        $banner->start_date_time = $request->start_date_time;
        $banner->end_date_time = $request->end_date_time;

        if( $update == 'false'){
            $bannerSort = Banner::select('id', 'sorting')->where('client_id',$this->client_id)->where('sorting', \DB::raw("(select max(`sorting`) from banners)"))->first();
            $banner->sorting = 1;
            if($bannerSort){
                $banner->sorting = $bannerSort->sorting + 1;
            }
            
        }
        if($request->has('assignTo') && !empty($request->assignTo)){
            $banner->link = $request->assignTo;
            $banner->redirect_category_id = ($request->assignTo == 'category') ? $request->category_id : NULL;
            $banner->redirect_vendor_id = ($request->assignTo == 'vendor') ? $request->vendor_id : NULL;
        }

        if ($request->hasFile('image')) {    /* upload logo file */
            $file = $request->file('image');
             $img_path = uploadFileWithAjax23($this->folderName,$file);
            $banner->image = $img_path;
        }


        if ($request->hasFile('image_mobile')) {    /* upload logo file */
            $file = $request->file('image_mobile');
             $img_path = uploadFileWithAjax23($this->folderName,$file);
            $banner->image_mobile = $img_path;
        }
        $saveRes = $banner->save();
        return $banner->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        banner::where('id',$id)->where('client_id',$this->client_id)->delete();
        return redirect()->back()->with('success', 'Banner deleted successfully!');
    }

    /**
     * save the order of banner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function saveOrder(Request $request)
    {
        foreach ($request->order as $key => $value) {
            $banner = Banner::where('id', $value)->where('client_id',$this->client_id)->first();
            $banner->sorting = $key + 1;
            $banner->save();
        }
        return response()->json([
            'status'=>'success',
            'message' => 'Banner order updated Successfully!',
        ]);
    }
    /**
     * update the validity of banner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function validity(Request $request)
    {
        $banner = Banner::where('id', $request->banId)->where('client_id',$this->client_id)->first();
        $banner->validity_on = ($request->value == 1) ? 1 : 0;
        $banner->save();
        return response()->json([
            'status'=>'success',
            'message' => 'Banner order updated Successfully!',
        ]);

    }

}