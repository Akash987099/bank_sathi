<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Admin;
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
use App\Models\Product;

class ProductController extends Controller
{

    public function product(Request $request){
        // dd($request->all());


        $category = Category::all();
        $id = $request->id;
        return view('products' , compact('category' , 'id'));
    }

    public function ProductSave(Request $request){

        // dd($request->all());

        $rules = [
            'category' => 'required',
            'product'  => 'required',
            'payout' => 'required|',
            'etb'    => 'nullable'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        $data = [
            'category_id' => $request->category,
            'product' => $request->product,
            'payout'  => $request->payout,
            'etb'     => $request->etb,
        ];

        $insert = Product::create($data);

        if($insert){
            return response()->json(['status' => 'success', 'message' => 'Created successfully']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'created failed!']);
        }

    }

    public function productbyId(Request $request){

        $categoryId = $request->id;
        // dd($categoryId);

            $url = "https://tryleadapi.banksathi.com/api/b2b/productByCategory?category_id={$categoryId}";

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

        return view('user.Productsbycategory' ,compact('records'));

    }

    public function ProductList(Request $request)
{
    // dd($request->all());
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

    $categoryId = $request->cateoryID;
    // dd($categoryId);


    if($categoryId == NULL){
        $url = "https://tryleadapi.banksathi.com/api/b2b/productByCategory?category_id=13";
    }else{
        $url = "https://tryleadapi.banksathi.com/api/b2b/productByCategory?category_id={$categoryId}";
    }

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

    if (!empty($searchValue)) {
        $records = array_filter($records, function ($item) use ($searchValue) {
            return stripos($item['title'], $searchValue) !== false;
        });
    }

    $totalRecords = count($records);
    $paginatedData = array_slice($records, $start, $length);

    $data_arr = [];
    foreach ($paginatedData as $index => $record) {
        $id = $record['product_id'];
        $action  = '&nbsp;<a href="javascript:void(0);" class="edit" data-id="'.$id.'"><i class="fa fa-pencil-square text-primary" aria-hidden="true"></i></a>';
        $action .= '&nbsp;<a href="javascript:void(0);" class="delete" data-id="'.$id.'"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

        $img = '<img src="'.$record['logo'].'" alt="" height="40px">';
        // dd($record['logo']);
        $data_arr[] = [
            "id" => $start + $index + 1,
            "name" => $record['title'],
            'logo' => $img,
            "action" => $action,
        ];
    }

    return response()->json([
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecords,
        "aaData" => $data_arr,
    ]);
}

    public function ProductDelete(Request $request){

        $delete = $request->delete;
        $edit   = $request->edit;

        if($delete){
            $data = Product::where('id' , $delete)->delete();
        }

        if($edit){
            $data = Product::where('id' , $edit)->first();
        }

        if($data){
            return response()->json(['status' => 'success', 'message' => 'deleted successfully' , 'data' => $data]);
        }else{
            return response()->json(['status' => 'error', 'message' => 'deleted failed!']);
        }

    }

    public function ProductEdit(Request $request){

        $rules = [
            'category' => 'required',
            'product'  => 'required',
            'payout' => 'required|',
            'etb'    => 'nullable'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        $data = [
            'category_id' => $request->category,
            'product' => $request->product,
            'payout'  => $request->payout,
            'etb'     => $request->etb,
        ];

        $insert = Product::where('id' , $request->id)->update($data);

        if($insert){
            return response()->json(['status' => 'success', 'message' => 'Created successfully']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'created failed!']);
        }

    }

}
