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
use Carbon\Carbon;

class EligibleController extends Controller
{

    public function eligible(Request $request){

        $id = $request->id;
        $category = $request->category;
        $searchValue = $request->searchValue;

        $url = "https://tryleadapi.banksathi.com/api/b2b/eligibleProductList";

        $postData = json_encode([
            'customer_id' => $id,
            'category_id' => $category,
        ]);

        $headers = [
            'x-api-key: OVNzREdzSjcxbCs5cCtNQnVDRElTMCtCa2t4NDluRnNwRVZjTEFqUlZUZlFLWlEwV0FWZEJTbEs1ckZ6TzY3aQ==',
            'iv: Y3RuVEYxRHp5SzFQTTA2bWI0V2tmZz09',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return response()->json([
                'status' => false,
                'message' => 'Curl Error: ' . $error
            ], 500);
        }

        $apiData = json_decode($response, true);

        if (!isset($apiData['status']) || !$apiData['status']) {
            return response()->json([
                'status' => false,
                'message' => 'API returned an error',
                'api_response' => $apiData
            ], 500);
        }

        $records = $apiData['data']['eligible_product_list'] ?? [];
        // dd($records);

        if (!empty($searchValue)) {
            $records = array_filter($records, function ($item) use ($searchValue) {
                return stripos($item['title'], $searchValue) !== false;
            });
            $records = array_values($records);
        }

        return view('eligible' , compact('records'));

        return response()->json([
            'status' => true,
            'data' => $records,
            'message' => 'Products fetched successfully.'
        ]);

    }

}
