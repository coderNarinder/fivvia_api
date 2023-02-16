<?php

namespace App\Http\Controllers\Tours\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\Custom\ClientCacheClass;
class BaseController extends Controller
{
    public $obj = null;
    public $layout = '';
    public $includes = '';
    public $modules = '';
    public $root = '';
    public $temp_id = '';
    public $client_id = '';
    public $client_business_type = '';

    public function __construct()
    {
    	$this->obj = $obj = new ClientCacheClass;
        $this->client_id = $obj->client_id;
    	$this->client_business_type = $obj->client_business_type;
        $template_path = (object)$this->obj->template_path;
        $this->layout = $template_path->layout;
        $this->includes = $template_path->includes;
        $this->modules = $template_path->modules;
        $this->temp_id = $template_path->temp_id;
        $this->root = $template_path->root;
    }
}
