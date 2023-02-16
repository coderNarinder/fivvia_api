<?php

namespace App\Http\Controllers\Godpanel;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, Language, ClientPreference, MapProvider, Category, Category_translation, ClientLanguage, Variant, VariantOption, VariantTranslation, VariantOptionTranslation, VariantCategory, Brand, CategoryHistory, Type, CategoryTag, Vendor, DispatcherWarningPage, DispatcherTemplateTypeOption, Product,CategoryTranslation, BrandCategory, BrandTranslation};
use GuzzleHttp\Client as GCLIENT;
class BrandController extends BaseController
{
private $blocking = '2';
    private $folderName = 'brands/icon';
    private $view_path = 'godpanel.modules.brands.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $celebrity_check = ClientPreference::first()->value('celebrity_check');

        // $brands = Brand::with('bc.cate.primary')->with('translation_one')->where('status', '!=', 2)->orderBy('position', 'asc')->with('bc')->get();
        $brands = Brand::with(['bc.categoryDetail', 'bc.categoryDetail.translation' =>  function ($q) use ($langId) {
            $q->select('category_translations.name', 'category_translations.category_id', 'category_translations.language_id')->where('category_translations.language_id', $langId);
        }, 'translation' => function ($q) use ($langId) {
            $q->select('title', 'brand_id', 'language_id')->where('language_id', $langId);
        }])->where('status', 1)->orderBy('position', 'asc')->get();

        $variants = Variant::with('option', 'varcategory.cate.primary','translation_one')->where('status', '!=', 2)->orderBy('position', 'asc')->get();
        $categories = Category::with('translation_one','type')->where('id', '>', '1')->where('is_core', 1)->orderBy('parent_id', 'asc')->orderBy('position', 'asc')->where('deleted_at', NULL)->where('status', 1);

        if ($celebrity_check == 0)
            $categories = $categories->where('type_id', '!=', 5);   # if celebrity mod off .

        $categories = $categories->get();
        if($categories) {
            $build = $this->buildTree($categories->toArray());
            $tree = $this->printTree($build);
        }
        $langs = ClientLanguage::join('languages as lang', 'lang.id', 'client_languages.language_id')
            ->select('lang.id as langId', 'lang.name as langName', 'lang.sort_code', 'client_languages.client_code', 'client_languages.is_primary')
            ->where('client_languages.client_code', Auth::user()->code)
            ->where('client_languages.is_active', 1)
            ->orderBy('client_languages.is_primary', 'desc')->get();


        return view($this->view_path.'index')
        ->with([
            'categories' => $categories, 
            'html' => $tree,  
            'languages' => $langs, 
            'variants' => $variants, 
            'brands' => $brands, 
            'build' => $build
         ]);
    }

 public function create()
    {
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $categories = Category::with(['english', 'translation' => function($q) use($langId){
            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
            ->where('category_translations.language_id', $langId);
        }])
        ->where('id', '>', '1')
        ->where('status', 1)
        ->whereIn('type_id', ['1', '3', '4', '6'])
        ->whereNull('vendor_id')
        ->orderBy('parent_id', 'asc')
        ->orderBy('position', 'asc')->get();
        $categories_hierarchy = '';
        if($categories){
            $categories_build = $this->buildTree($categories->toArray());
            $categories_hierarchy = $this->printCategoryOptionsHeirarchy($categories_build);
        }
        $langs = Language::where('basic_lang',1)->orderBy('id','ASC')->get();
        $returnHTML = view($this->view_path.'add')->with(['categories' => $categories_hierarchy,  'languages' => $langs])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $data_cate = array();
        if ($request->has('title')) {
            $brand = new Brand();
            $brand_pos = Brand::select('id', 'position')->where('position', \DB::raw("(select max(`position`) from brands)"))->first();
            $brand->title = $request->title[0];
            $brand->position = 1;
            if ($brand_pos) {
                $brand->position = $brand_pos->position + 1;
            }
            if ($request->hasFile('image1')) {
                $file = $request->file('image1');
                $brand->image = Storage::disk('public')->put($this->folderName, $file, 'public');
            } else {
                $brand->image = 'default/default_image.png';
            }
            if ($request->hasFile('image2')) {
                $file = $request->file('image2');
                $brand->image_banner = Storage::disk('public')->put($this->folderName, $file, 'public');
            } else {
                $brand->image_banner = 'default/default_image.png';
            }
            $brand->save();
            if ($brand->id > 0) {
                $data_cate['brand_id'] = $brand->id;
                foreach ($request->cate_id as $key => $cate_id) {
                    $data_cate['category_id'] = $cate_id;

                    BrandCategory::insert($data_cate);
                }
                foreach ($request->title as $key => $value) {
                    $data[] = [
                        'title' => $value,
                        'brand_id' => $brand->id,
                        'language_id' => $request->language_id[$key]
                    ];
                }
                BrandTranslation::insert($data);
            }
            return redirect()->back()->with('success', 'Brand added successfully!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $brand = Brand::with('translation', 'bc')->where('id', $id)->firstOrFail();
        $categories = Category::with(['english', 'translation' => function($q) use($langId){
                $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                ->where('category_translations.language_id', $langId);
            }])
            ->where('id', '>', '1')
            ->where('status', 1)
            ->whereIn('type_id', ['1', '3', '4', '6'])
            ->whereNull('vendor_id')
            ->orderBy('parent_id', 'asc')
            ->orderBy('position', 'asc')
            ->get();
        $categories_hierarchy = '';
        if($categories){
            $categories_build = $this->buildTree($categories->toArray());
            $categories_hierarchy = $this->printCategoryOptionsHeirarchy($categories_build);
        }
        $langs = ClientLanguage::with(['language', 'brand_trans' => function ($query) use ($id) {
                $query->where('brand_id', $id);
            }])
            ->select('language_id', 'is_primary', 'is_active')
            ->where('is_active', 1)
            ->orderBy('is_primary', 'desc')->get();
        $submitUrl = route('godpanel.brand.update', $id);
        $returnHTML = view($this->view_path.'edit')->with(['categories' => $categories_hierarchy,  'languages' => $langs, 'brand' => $brand])->render();
        return response()->json(array('success' => true, 'html' => $returnHTML, 'submitUrl' => $submitUrl));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $brand = Brand::where('id', $id)->firstOrFail();

        $brand->title = $request->title[0];
        if ($request->hasFile('image1')) {
            $file = $request->file('image1');
            $brand->image = Storage::disk('s3')->put($this->folderName, $file, 'public');
        }
        if ($request->hasFile('image2')) {
            $file = $request->file('image2');
            $brand->image_banner = Storage::disk('s3')->put($this->folderName, $file, 'public');
        }
        $brand->save();
        $cate_array = array($request->cate_id);
        $database_array = BrandCategory::where('brand_id', $id)->pluck('category_id');
       // dd($cate_array,$database_array);
        if (count(BrandCategory::where('brand_id', $id)->pluck('category_id')) >= 1) {
            foreach ($database_array as $data) {
                if (!in_array($data, $cate_array)) {
                    BrandCategory::where('brand_id', $id)->where('category_id', $data)->delete();
                }
            }
        }
        if ($request->cate_id != null) {
            foreach ($request->cate_id as $key => $cate_id) {
                //$affected = BrandCategory::where('brand_id', $brand->id)->update(['category_id'=>$cate_id]);
                //$affected->cate()->associate($cate_id)->save();
                $brand = Brand::find($id);

                // $cat = BrandCategory::where('category_id', $cate_id)->first();

                // $cat = BrandCategory::insert(['brand_id' => $id, 'category_id' => $cate_id]);
                // $cat = BrandCategory::where('category_id', $cate_id)->first();
                // if ($cat == null)
                if (BrandCategory::where('brand_id', $id)->where('category_id', $cate_id)->first() == null) {
                    $cat = BrandCategory::insert(['brand_id' => $id, 'category_id' => $cate_id]);
                }

                // else
                //$brand->bc()->save($cat);
            }
        }
        //  $affected = BrandCategory::where('brand_id', $id)->with('cate')->get();
        // // dd($affected);
        //   $affected->cate()->sync($cate_array);

        foreach ($request->title as $key => $value) {
            $bt = BrandTranslation::where('brand_id', $brand->id)->where('language_id', $request->language_id[$key])->first();
            if (!$bt) {
                $bt = new BrandTranslation();
                $bt->brand_id = $brand->id;
                $bt->language_id = $request->language_id[$key];
            }
            $bt->title = $value;
            $bt->save();
        }
        return redirect()->back()->with('success', 'Brand updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::where('id', $id)->first();
        $brand->status = 2;
        // $brand->bc()->save();
        BrandCategory::where('brand_id', $id)->delete();
        $brand->save();
        return redirect()->back()->with('success', 'Brand deleted successfully!');
    }

    /**
     * save the order of variant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Variant  $variant
     * @return \Illuminate\Http\Response
     */
    public function updateOrders(Request $request)
    {
        $arr = explode(',', $request->orderData);
        foreach ($arr as $key => $value) {
            $brand = Brand::where('id', $value)->first();
            if ($brand) {
                $brand->position = $key + 1;
                $brand->save();
            }
        }
        return redirect('client/category')->with('success', 'Brand order updated successfully!');
    }
}
