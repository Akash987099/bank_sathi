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
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{

    public function category(){
        return view('categary');
    }

    public function CategorySave(Request $request){

        // dd($request->all());

        $rules = [
            'category' => 'required|unique:category,category',
         ];

         $customMessages = [
            'category.required' => 'The category field is required.',
            'category.unique' => 'The category has already been taken.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        $data = [
            'category' => $request->category,
        ];

          $insert = Category::create($data);

        if($insert){
            return response()->json(['status' => 'success', 'message' => 'Created successfully']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'created failed!']);
        }

    }

    public function CategoryList(Request $request){

        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search_arr = $request->get('search');
        $searchValue = $search_arr['value'];
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];

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
        $records = $apiData['data'] ?? [];

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

        // Filter based on search
        if (!empty($searchValue)) {
            $records = array_filter($records, function ($item) use ($searchValue) {
                return stripos($item['title'], $searchValue) !== false;
            });
        }

        // Sort
        if (!empty($columnName) && !empty($columnSortOrder)) {
            usort($records, function ($a, $b) use ($columnName, $columnSortOrder) {
                return $columnSortOrder === 'asc'
                    ? strcmp($a[$columnName], $b[$columnName])
                    : strcmp($b[$columnName], $a[$columnName]);
            });
        }

        $totalRecords = count($records);
        $paginatedData = array_slice($records, $start, $length);

        $data_arr = [];
        foreach ($paginatedData as $index => $record) {
            $id = $record['id'];
            $action  = '&nbsp;<a href="'.route('user.product' , ['id' => $id]).'" class="" data-id="'.$id.'"><i class="fa fa-eye text-primary" aria-hidden="true"></i></a>';
            // $action .= '&nbsp;<a href="javascript:void(0);" class="delete" data-id="'.$id.'"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

            $data_arr[] = [
                "id" => $start + $index + 1,
                "name" => $record['title'], // FIXED
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

    public function CategoryDelete(Request $request){

        $delete = $request->delete;
        $edit   = $request->edit;

        if($delete){
            $data = Category::where('id' , $delete)->delete();
        }

        if($edit){
            $data = Category::where('id' , $edit)->first();
        }

        if($data){
            return response()->json(['status' => 'success', 'message' => 'deleted successfully' , 'data' => $data]);
        }else{
            return response()->json(['status' => 'error', 'message' => 'deleted failed!']);
        }

    }

    public function CategoryUpdate(Request $request){

        $id = $request->id;

        $rules = [
           'category' => [
               'required',
               Rule::unique('category', 'category')->ignore($id),
           ],
       ];

        $customMessages = [
           'category.required' => 'The category field is required.',
           'category.unique' => 'This category already exists in the category.',
       ];

       $validator = Validator::make($request->all(), $rules, $customMessages);

       if ($validator->fails()) {
           return response()->json(['status' => 'error', 'message' => $validator->errors()]);
       }

       $data = [
           'category' => $request->category,
       ];

       $update = Category::where('id' , $id)->update($data);

       if($update){
           return response()->json(['status' => 'success', 'message' => 'updated successfully']);
       }else{
           return response()->json(['status' => 'error', 'message' => 'not updated']);
       }

    }

}
