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

class UserController extends Controller
{

    public function index(){

        $url = 'https://tryleadapi.banksathi.com/api/b2b/allProductCategory';
        $headers = [
            'x-api-key: OVNzREdzSjcxbCs5cCtNQnVDRElTMCtCa2t4NDluRnNwRVZjTEFqUlZUZlFLWlEwV0FWZEJTbEs1ckZ6TzY3aQ==',
            'iv: Y3RuVEYxRHp5SzFQTTA2bWI0V2tmZz09',
            'Content-Type: application/json'
        ];

        // Step 1: Get all categories
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        $categoryData = json_decode($response, true);
        $categories = $categoryData['data'] ?? [];

        $result = [];

        foreach ($categories as $category) {
            $categoryId = $category['id'];
            $categoryName = $category['title'];

            $productUrl = "https://tryleadapi.banksathi.com/api/b2b/productByCategory?category_id={$categoryId}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $productUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $productResponse = curl_exec($ch);
            curl_close($ch);

            $productData = json_decode($productResponse, true);
            $productCount = count($productData['data'] ?? []);
            $randomLogo = null;

            $result[] = [
                'category_id' => $categoryId,
                'category_name' => $categoryName,
                'product_count' => $productCount,
                'logo' => $randomLogo
            ];
        }

        // Step 4: Return the result
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Category product count fetched',
        //     'data' => $result
        // ]);

        // dd($result);

        return view('dashboard' ,compact('result'));
    }

}
