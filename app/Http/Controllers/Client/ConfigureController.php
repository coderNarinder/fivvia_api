<?php

namespace App\Http\Controllers\Client;

use App\Models\SmsProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, MapProvider};

class ConfigureController extends BaseController
{

    private $folderName = 'category/icon';
    public $client_id =0;
    public function __construct()
    {
       $this->client_id = getWebClientID();
        $code = Client::where('id',getWebClientID())->value('code');
        $this->folderName = '/'.$code.'/banner';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $mapTypes = MapProvider::where('status', '1')->where('client_id',$this->client_id)->get();
        $smsTypes = SmsProvider::where('status', '1')->where('client_id',$this->client_id)->get();
        $preference = ClientPreference::where('client_id',$this->client_id)->first();
        $client = Auth::user();
        if(!$preference){
            $preference = new ClientPreference();
        }
        return view('backend/setting/config')->with(['client' => $client, 'preference' => $preference, 'maps'=> $mapTypes, 'smsTypes' => $smsTypes]);
    }
}
