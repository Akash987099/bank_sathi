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

class OccupationController extends Controller
{

    public function occuption(){
        return view('occupation');
    }

    public function occuptionlist(Request $request){

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
        $records = $apiData['data'] ?? [];

        if (!empty($searchValue)) {
            $records = array_filter($records, function ($item) use ($searchValue) {
                return stripos($item['occu_title'], $searchValue) !== false;
            });
        }

        $totalRecords = count($records);
        $paginatedData = array_slice($records, $start, $length);

        $data_arr = [];
        foreach ($paginatedData as $index => $record) {
            $id = $record['id'];
            $action  = '&nbsp;<a href="javascript:void(0);" class="edit" data-id="'.$id.'"><i class="fa fa-pencil-square text-primary" aria-hidden="true"></i></a>';
            $action .= '&nbsp;<a href="javascript:void(0);" class="delete" data-id="'.$id.'"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

            $data_arr[] = [
                "id" => $start + $index + 1,
                "name" => $record['occu_title'],
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

}
