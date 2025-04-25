<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\DataTables;
use App\Rules\PasswordRule;
use Illuminate\Support\Str;
use Symfony\Component\Mime\Part\HtmlPart;
use Illuminate\Validation\Rule;
use DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CustomerController extends Controller
{

    // exist customer

    public function existcustomer(){

        $url = 'https://tryleadapi.banksathi.com/api/b2b/allProductCategory';

        $headers = [
            'x-api-key: OVNzREdzSjcxbCs5cCtNQnVDRElTMCtCa2t4NDluRnNwRVZjTEFqUlZUZlFLWlEwV0FWZEJTbEs1ckZ6TzY3aQ==',
            'iv: Y3RuVEYxRHp5SzFQTTA2bWI0V2tmZz09',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json([
                'status' => false,
                'message' => 'Curl Error: ' . $error
            ]);
        }

        $apiData = json_decode($response, true);
        $category = $apiData['data'] ?? [];

        return view('exist-customer' , compact('category'));
    }

    public function checkcustomer(Request $request){

        $data = [
            'mobile_no'      => $request->mobile_no,
            'pan'            => $request->pan,
            'category_id'    => $request->category_id,
            'is_user_validated' => 1,
        ];

        $url = "https://tryleadapi.banksathi.com/api/b2b/checkCustomerExists?mobile_no={$request->mobile_no}&pan={$request->pan}&category_id={$request->category_id}&is_user_validated=1";

        $headers = [
            'x-api-key: OVNzREdzSjcxbCs5cCtNQnVDRElTMCtCa2t4NDluRnNwRVZjTEFqUlZUZlFLWlEwV0FWZEJTbEs1ckZ6TzY3aQ==',
            'iv: Y3RuVEYxRHp5SzFQTTA2bWI0V2tmZz09',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json([
                'status' => false,
                'message' => 'Curl Error: ' . $error
            ]);
        }
    //    dd($response);
        $apiData = json_decode($response, true);
        $records = $apiData['data'] ?? [];

        $data = [
            'mobile_no'    => $records['mobile_no'],
            'customer_id'  => $records['profile_details']['customer_id'],
            'credit_score' => $records['profile_details']['credit_score'],
            'category_id'  => $records['profile_details']['category_id'],
        ];

        $customer = DB::table('customers')->insert($data);


        if($customer){
            return response()->json([
                'status' => 'success',
                'message' => 'Success'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Not Existing User'
        ]);

    }

    public function verifycustomer(){
        return view('verify-customer');
    }

    public function verifycustomerlist(Request $request){

    $draw = $request->get('draw');
    $start = $request->get('start');
    $length = $request->get('length');
    $search_arr = $request->get('search');
    $searchValue = $search_arr['value'] ?? '';
    $columnIndex_arr = $request->get('order');
    $columnName_arr = $request->get('columns');
    $order_arr = $request->get('order');
    $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
    $columnName = $columnName_arr[$columnIndex]['data'] ?? 'title';
    $columnSortOrder = $order_arr[0]['dir'] ?? 'asc';

        $userid = Auth::user()->id;

        $data = DB::table('customers');

        if ($searchValue != null) {
            $data->where('mobile_no', 'like', '%' . $searchValue . '%');
        }

        $totalRecordswithFilter = $data->count();
        $totalRecords = $totalRecordswithFilter;

        $data = $data->get();

        $data_arr = array();
        foreach($data as $sno => $record){
               $id = $record->id;

               $id = $id;
               $action  = '&nbsp;<a href="'.route('user.customer' , ['cusID' => $record->customer_id]).'" class="edit" data-id="'.$id.'"><i class="fa fa-pencil-square text-primary" aria-hidden="true"></i></a>';

               if($record->response != NULL){
               $action .= '&nbsp;&nbsp;<a href="'.route('user.eligible' , ['id' => $record->customer_id , 'category' => $record->category_id]).'" class="" data-id="'.$id.'"><i class="fa fa-info text-info" aria-hidden="true"></i></a>';
               }

            $data_arr[] = array(
                "id" => ++$start,
                  "mobile" => $record->mobile_no,
                  "cutomber_id" => $record->customer_id,
                  "credit_score" => $record->credit_score,
              "action" => $action,
            );
         }

        $response = array(
            "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordswithFilter,
           "aaData" => $data_arr,
        );

        // dd($response);
        echo json_encode($response);

    }

    public function customer(Request $request){

        $cusID = $request->cusID;

        $customer = Customer::where('customer_id' , $cusID)->first();
        // dd($customer);

        $url = "https://tryleadapi.banksathi.com/api/b2b/companies?searchKey=ss";

        $headers = [
            'x-api-key: OVNzREdzSjcxbCs5cCtNQnVDRElTMCtCa2t4NDluRnNwRVZjTEFqUlZUZlFLWlEwV0FWZEJTbEs1ckZ6TzY3aQ==',
            'iv: Y3RuVEYxRHp5SzFQTTA2bWI0V2tmZz09',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json([
                'status' => false,
                'message' => 'Curl Error: ' . $error
            ]);
        }
    //    dd($response);
        $apiData = json_decode($response, true);
        $company = $apiData['data'] ?? [];

        $url = 'https://tryleadapi.banksathi.com/api/b2b/allProductCategory';

        $headers = [
            'x-api-key: OVNzREdzSjcxbCs5cCtNQnVDRElTMCtCa2t4NDluRnNwRVZjTEFqUlZUZlFLWlEwV0FWZEJTbEs1ckZ6TzY3aQ==',
            'iv: Y3RuVEYxRHp5SzFQTTA2bWI0V2tmZz09',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json([
                'status' => false,
                'message' => 'Curl Error: ' . $error
            ]);
        }

        $apiData = json_decode($response, true);
        $category = $apiData['data'] ?? [];

        $url = "https://tryleadapi.banksathi.com/api/b2b/occupation";

        $headers = [
            'x-api-key: OVNzREdzSjcxbCs5cCtNQnVDRElTMCtCa2t4NDluRnNwRVZjTEFqUlZUZlFLWlEwV0FWZEJTbEs1ckZ6TzY3aQ==',
            'iv: Y3RuVEYxRHp5SzFQTTA2bWI0V2tmZz09',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json([
                'status' => false,
                'message' => 'Curl Error: ' . $error
            ]);
        }
    //    dd($response);
        $apiData = json_decode($response, true);
        $occuption = $apiData['data'] ?? [];

        // dd($occuption);
        return view('customer' , compact('company' , 'category' , 'occuption' , 'cusID' , 'customer'));
    }

    public function customersave(Request $request){

    //  dd($request->all());

     if($request->pincode != NULL){
           $pincode = $this->getpincode($request->pincode);
     }

    $dob = Carbon::parse($request->dob)->format('Y-m-d');

    $data = [
        "first_name"      => $request->first_name,
        "last_name"       => $request->last_name,
        "mobile_no"       => $request->mobile_no,
        "email"           => $request->email,
        "dob"             => $dob,
        "company"         => $request->company,
        "occupation"      => $request->occupation,
        "monthly_salary"  => $request->monthly_salary,
        "itr_amount"      => $request->itr_amount,
        "pincode"         => $pincode,
        'address'         => $request->Address,
        'category'        => $request->category,
        'category_id'     => $request->category_id,
        'gender'          => $request->gender,
        'customer_id'     => $request->customer_id,
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://tryleadapi.banksathi.com/api/b2b/createLeadProfile',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'x-api-key: OVNzREdzSjcxbCs5cCtNQnVDRElTMCtCa2t4NDluRnNwRVZjTEFqUlZUZlFLWlEwV0FWZEJTbEs1ckZ6TzY3aQ==',
            'iv: Y3RuVEYxRHp5SzFQTTA2bWI0V2tmZz09',
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($err) {
        return response()->json([
            'success' => false,
            'message' => 'cURL Error: ' . $err,
        ], 500);
    }

    $responseData = json_decode($response, true);
    $data['response'] = json_encode($responseData);
    $customer = Customer::where('customer_id' , $request->customer_id)->update($data);

    if ($customer) {
        return response()->json([
            'status' => 'success',
            'success' => true,
            'message' => 'Lead profile created successfully.',
            'data'    => $responseData,
        ]);
    }

    return response()->json([
            'success' => false,
            'message' => $responseData['message'] ?? 'Something went wrong',
            'data'    => $responseData,
        ], 400);

    }

    public function getpincode($pincode){
        // dd('pincode' , $pincode);

        $url = "https://tryleadapi.banksathi.com/api/b2b/pincodes?searchKey=$pincode";

        $headers = [
            'x-api-key: OVNzREdzSjcxbCs5cCtNQnVDRElTMCtCa2t4NDluRnNwRVZjTEFqUlZUZlFLWlEwV0FWZEJTbEs1ckZ6TzY3aQ==',
            'iv: Y3RuVEYxRHp5SzFQTTA2bWI0V2tmZz09',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json([
                'status' => false,
                'message' => 'Curl Error: ' . $error
            ]);
        }
        $apiData = json_decode($response, true);
        $records = $apiData['data'] ?? [];

        $pincode = $records[0]['id'] ?? null;

        return $pincode;

    }

}
