<?php

namespace App\Http\Controllers\Tours;

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

    public function __construct()
    {
    	$this->obj = new ClientCacheClass;
        $template_path = (object)$this->obj->template_path;
        $this->layout = $template_path->layout;
        $this->includes = $template_path->includes;
        $this->modules = $template_path->modules;
        $this->temp_id = $template_path->temp_id;
        $this->root = $template_path->root;
    }
}
