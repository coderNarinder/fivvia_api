<?php

namespace App\Http\Controllers\Client;

use Auth;
use Image;
use Password;
use DataTables;
use Carbon\Carbon;
use App\Models\Vendor;
use App\Models\UserVendor;
use App\Models\Permissions;
use Illuminate\Http\Request;
use App\Models\UserPermissions;
use App\Models\Timezone;
use Illuminate\Support\Str;
use App\Imports\CustomerImport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordReset;
use App\Http\Traits\ToasterResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use App\Models\UserDevice;
use Session;
use App\Models\{Payment, User, Client, Country, CsvCustomerImport, Currency, Language, UserVerification, Role, Transaction};

class UserController extends BaseController
{
    use ToasterResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $folderName = 'banner';
    private $folderName2 = 'banner';
    private $fstatus = 1;
    public $client_id =0;
    public function __construct()
    {
        $this->client_id = getWebClientID(); 
        // $this->folderName = '/'.$code.'/banner';
        $this->client_id = $code = getWebClientID();
        $this->folderName = 'pictures/'.$code.'/users/';
        $this->folderName2 = 'pictures/'.$code.'/Clientlogo/';
    }
    public function index()
    {
        $roles = Role::all();
        $countries = Country::all();
        $active_users = User::where('status', 1)->where('client_id',$this->client_id)->where('is_superadmin', '!=', 1)->count();
        $inactive_users = User::where('status', 3)->where('client_id',$this->client_id)->count();
        $users = User::withCount(['orders', 'activeOrders'])
        ->where('client_id',$this->client_id)
        ->where('status', '!=', 3)
        ->where('is_superadmin', '!=', 1)
        ->orderBy('id', 'desc')
        ->paginate(10);
        $social_logins = 0;
        foreach ($users as  $user) {
            if (!empty($user->facebook_auth_id)) {
                $social_logins++;
            } elseif (!empty($user->twitter_auth_id)) {
                $social_logins++;
            } elseif (!empty($user->google_auth_id)) {
                $social_logins++;
            } elseif (!empty($user->apple_auth_id)) {
                $social_logins++;
            }
        }
        $csvCustomers = CsvCustomerImport::where('client_id',$this->client_id)->get();
        return view('backend/users/index')->with(['inactive_users' => $inactive_users, 'social_logins' => $social_logins, 'active_users' => $active_users, 'users' => $users, 'roles' => $roles, 'countries' => $countries,'csvCustomers'=>$csvCustomers]);
    }
    public function getFilterData(Request $request)
    {
        $current_user = Auth::user();
        $users = User::withCount(['orders', 'currentlyWorkingOrders'])
        ->where('client_id',$this->client_id)
        ->where('status', '!=', 3)
        ->where('is_superadmin', '!=', 1)
        ->orderBy('id', 'desc');
       
        return Datatables::of($users)
            ->addColumn('edit_url', function($users) {
                return route('customer.new.edit', $users->id);
            })
            ->addColumn('delete_url', function($users) {
                return route('customer.account.action', [$users->id, 3]);
            })
            ->addColumn('image_url', function($users) {
                return  $users->image['image_path'];
            })
            ->addColumn('login_type', function($users) {
                if (!empty($users->facebook_auth_id)) {
                    return 'Facebook';
                } elseif (!empty($users->twitter_auth_id)) {
                    return 'Twitter';
                } elseif (!empty($users->google_auth_id)) {
                    return 'Google';
                } elseif (!empty($users->apple_auth_id)) {
                    return 'Apple';
                } else{
                    return 'Email';
                }
            })
            ->addColumn('is_superadmin', function($users) use($current_user) {
                return $current_user->is_superadmin;
            })
            ->addColumn('wallet', function($users) {
                return $users->wallet;
            })
            ->addColumn('login_type_value', function($users) {
                if (!empty($users->facebook_auth_id)) {
                    return $users->facebook_auth_id;
                } elseif (!empty($users->twitter_auth_id)) {
                    return $users->twitter_auth_id;
                } elseif (!empty($users->google_auth_id)) {
                    return $users->google_auth_id;
                } elseif (!empty($users->apple_auth_id)) {
                    return $users->apple_auth_id;
                } else{
                    return $users->email;
                }
            })
            ->addColumn('balanceFloat', function($users) {
                return $users->balanceFloat;
            })
            ->addColumn('edit_url', function($users) {
                return route('customer.new.edit', $users->id);
            })
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $search = $request->get('search');
                    $instance->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%')
                    ->orWhere('phone_number', 'LIKE', '%'.$search.'%')
                    ->orWhere('import_user_id', 'LIKE', '%'.$search.'%');
                }
            }, true)
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomer($uid, $action)
    {
        $user = User::where('id', $uid)->where('client_id',$this->client_id)->firstOrFail();
        $user->status = 3;
        $user->save();
        $msg = 'activated';
        if ($action == 2) {
            $msg = 'blocked';
        }
        if ($action == 3) {
            $msg = 'deleted';
        }
        return redirect()->back()->with('success', 'Customer account ' . $msg . ' successfully!');
    }

    /*      block - activate customer account*/
    public function changeStatus(Request $request)
    {
        $user = User::where('id', $request->userId)->where('client_id',$this->client_id)->firstOrFail();
        $user->status = ($request->value == 1) ? 1 : 2; // 1 for active 2 for block
        $user->save();
        $msg = 'activated';
        if ($request->value == 0) {
            $msg = 'blocked';
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Customer account ' . $msg . ' successfully!',
        ]);
    }

    /**              Add customer             */
    public function show($uid)
    {
        $user = User::where('id', $uid)->firstOrFail();
        return redirect()->back();
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $customer = new User();
       $validation  = Validator::make($request->all(), $customer->rules())->validate();
       //$validator = $this->validator($request->all())->validate();
       
        $saveId = $this->save($request, $customer, 'false');
        if ($saveId > 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Customer created Successfully!',
                'data' => $saveId,
                'aaa' => $request->all()
            ]);
        }
    }

    /**
     * save and update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, User $user, $update = 'false')
    {
        $request->contact;
        $request->phone_number;
        $phone = ($request->has('contact') && !empty($request->contact)) ? $request->contact : $request->phone_number;
        $user->name = $request->name;
        $user->client_id = $this->client_id;
        $user->dial_code = $request->dial_code;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $phone;
        $user->is_email_verified = ($request->has('is_email_verified') && $request->is_email_verified == 'on') ? 1 : 0;
        $user->is_phone_verified = ($request->has('is_phone_verified') && $request->is_phone_verified == 'on') ? 1 : 0;
        if ($request->hasFile('image')) {    /* upload logo file */
            $file = $request->file('image');
            $img_path = uploadFileWithAjax23($this->folderName,$file);
            $user->image = $img_path;
        }
        $user->save();
        $wallet = $user->wallet;
        $userCustomData = $this->userMetaData($user->id, 'web', 'web');
        return $user->id;
    }



     /**
     * Import Excel file for vendors
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importCsv(Request $request)
    {
        if($request->has('customer_csv')){
            $csv_vendor_import = new CsvCustomerImport();
            if($request->file('customer_csv')) {
                $fileName = time().'_'.$request->file('customer_csv')->getClientOriginalName();
                $filePath = $request->file('customer_csv')->storeAs('csv_customers', $fileName, 'public');
                $csv_vendor_import->name = $fileName;
                $csv_vendor_import->path = '/storage/' . $filePath;
                $csv_vendor_import->status = 1;
                $csv_vendor_import->client_id = $this->client_id;
                $csv_vendor_import->save();
            }
            $data = Excel::import(new CustomerImport($csv_vendor_import->id), $request->file('customer_csv'));
            return response()->json([
                'status' => 'success',
                'message' => 'File Successfully Uploaded!'
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'File Upload Pending!'
        ]);
    }



    public function edit($id)
    {
        $user = User::where('id', $id)->where('client_id',$this->client_id)->first();
        return response()->json(array('success' => true, 'user' => $user->toArray()));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newEdit($id)
    {
        $subadmin = User::where('id',$id)->where('client_id',$this->client_id)->firstOrFail();
        $permissions = Permissions::where('status',1)->whereNotin('id',[4,5,6,7,8,9,10,11,14,15,16,22,23,24,25])->get();
        $user_permissions = UserPermissions::where('user_id', $id)->get();
        $vendor_permissions = UserVendor::where('user_id', $id)->pluck('vendor_id')->toArray();
        $vendors = Vendor::where('status', 1)->where('client_id',$this->client_id)->get();
        return view('backend.users.editUser')->with(['subadmin' => $subadmin, 'vendors' => $vendors, 'permissions' => $permissions, 'user_permissions' => $user_permissions, 'vendor_permissions' => $vendor_permissions]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newUpdate(Request $request, $id)
    {
        $data = [
            'status' => $request->status,
            'is_admin' => $request->is_admin,
            'is_superadmin' => 0
        ];
        $client = User::where('id', $id)->where('client_id',$this->client_id)->update($data);
        //for updating permissions
        $removepermissions = UserPermissions::where('user_id', $id)->delete();
        if ($request->permissions) {
            $userpermissions = $request->permissions;
            $addpermission = [];
            for ($i = 0; $i < count($userpermissions); $i++) {
                $addpermission[] =  array('user_id' => $id, 'permission_id' => $userpermissions[$i]);
            }
            UserPermissions::insert($addpermission);
        }
        //for updating vendor permissions
        if ($request->vendor_permissions) {
            $teampermissions = $request->vendor_permissions;
            $addteampermission = [];
            $removeteampermissions = UserVendor::where('user_id', $id)->delete();
            for ($i = 0; $i < count($teampermissions); $i++) {
                $addteampermission[] =  array('user_id' => $id, 'vendor_id' => $teampermissions[$i]);
            }
            UserVendor::insert($addteampermission);
        }
        return redirect()->route('customer.index')->with('success', 'Customer Updated successfully!');
    }

    public function profile()
    {
        $countries = Country::all();
        $client = Client::where('id',$this->client_id)->first();
        $tzlist = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        $tzlist = Timezone::whereIn('timezone',$tzlist)->get();
        return view('backend/setting/profile')->with(['client' => $client, 'countries' => $countries, 'tzlist' => $tzlist]);
    }

    public function updateProfile(Request $request,$id)
    {
        $user = Auth::user();
       $client = Client::where('id', $this->client_id)->firstOrFail();
        $rules = array(
            'name' => 'required|string|max:50',
            'phone_number' => 'required|min:8|max:15',
            'company_name' => 'required',
            'company_address' => 'required',
            'country_id' => 'required',
            'timezone' => 'required',
        );
        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()
            ->withInput()
            ->withErrors($validation);
        }
        $data = array();
        foreach ($request->only('name', 'phone_number', 'company_name', 'company_address', 'country_id', 'timezone') as $key => $value) {
            $data[$key] = $value;
        }
        // $client = Client::where('code', $user->code)->first();
        $client = Client::where('id', $this->client_id)->firstOrFail();
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            
            $img_path = uploadFileWithAjax23($this->folderName2,$file);
            $path =  $img_path;//Storage::disk('s3')->put($file_name, file_get_contents($file), 'public');
            $client->logo = $img_path;
        }  
         
         
        $client->update($data);
        $userdata = array();
        foreach ($request->only('name', 'phone_number', 'timezone') as $key => $value) {
            $userdata[$key] = $value;
        }
        $user = $user->update($userdata);
        return redirect()->back()->with('success', 'Client Updated successfully!');
    }
    public function changePassword(Request $request)
    {
        $client = User::where('id', Auth::id())->first();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        if (Hash::check($request->old_password, $client->password)) {
            $client->password = Hash::make($request->password);
            $client->save();
            $clientData = 'empty';
            return redirect()->back()->with('success', 'Password Changed successfully!');
        } else {
            $request->session()->flash('error', 'Wrong Old Password');
            return redirect()->back();
        }
    }

    public function filterWalletTransactions(Request $request)
    {
        $pagiNate = 10;
        $user_transactions = Transaction::where('wallet_id', $request->walletId)
        ->where('client_id',$this->client_id)
        ->orderBy('id', 'desc')->get();
        // dd($user_transactions->toArray());
        foreach ($user_transactions as $key => $trans) {
            // $user = User::find($trans->payable_id);
            $trans->serial = $key + 1;
            $trans->date = Carbon::parse($trans->created_at)->format('M d, Y, H:i A');
            // $trans->date = convertDateTimeInTimeZone($trans->created_at, $user->timezone, 'l, F d, Y, H:i A');
            $trans->description = json_decode($trans->meta)[0];
            $trans->amount = '$' . sprintf("%.2f", ($trans->amount / 100));
            $trans->type = $trans->type;
        }
        return Datatables::of($user_transactions)
            ->addIndexColumn()
            ->rawColumns(['description'])
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        if (Str::contains(Str::lower($row['date']), Str::lower($request->get('search')))) {
                            return true;
                        } elseif (Str::contains(Str::lower($row['meta']), Str::lower($request->get('search')))) {
                            return true;
                        } elseif (Str::contains(Str::lower($row['amount']), Str::lower($request->get('search')))) {
                            return true;
                        }
                        return false;
                    });
                }
        })->make(true);
    }

    public function export()
    {
        return Excel::download(new CustomerExport, 'users.xlsx');
    }

    public function save_fcm(Request $request)
    {
        UserDevice::updateOrCreate(['device_token' => $request->fcm_token], ['user_id' => Auth::user()->id, 'device_type' => "web"])
        ->where('client_id',$this->client_id)
        ->first();
        Session::put('current_fcm_token', $request->fcm_token);
        return response()->json(['status' => 'success', 'message' => 'Token updated successfully']);
    }
}
