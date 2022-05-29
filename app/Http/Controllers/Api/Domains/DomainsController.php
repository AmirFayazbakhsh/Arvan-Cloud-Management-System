<?php

namespace App\Http\Controllers\Api\Domains;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DomainsController extends Controller
{

    public $apiKey = 'Apikey 00eab9db-87ba-5f0f-a2df-3295774a913c';

    //get All domain
    public function getAllDomains(){
        try{
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get('https://napi.arvancloud.com/cdn/4.0/domains');

            return $response['data'];

        }catch(Exception $e){
            return "faild to send request";
        }
    }


}
