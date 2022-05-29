<?php

namespace App\Http\Controllers\Api\Domains;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

class DomainsController extends Controller
{
    public $apiKey = 'Apikey 00eab9db-87ba-5f0f-a2df-3295774a913c';

    //get All domain
    public function getAllDomains(){

            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get('https://napi.arvancloud.com/cdn/4.0/domains');

            if($response->successful()){
                return $response;
            }else{
                 return "faild to get response";
            }

    }


    //get single domain
    public function getByDomain($domain){
        try{
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->get('https://napi.arvancloud.com/cdn/4.0/domains/'.$domain );
            return $response['data'];

        }catch(Exception $e){
            return $e;
        }
    }


    //create domain
    public function createDomain(Request $request){
        $domain = $request->domain;

            try{
                $client = new Client();
                $response = $client->request('POST', 'https://napi.arvancloud.com/cdn/4.0/domains/dns-service', [
                    'form_params' => [
                        "domain" => $request->domain,
                        "domain_type" => "full"
                    ],

                    'headers' => [
                        'Authorization' => $this->apiKey,
                    ]
                ]);

                if ($response->getBody()) {
                    echo $response->getBody();
                }

            }catch(Exception $e){
                return $e->getMessage();
            }

    }


    public function deleteDomain(Request $request){
        $domain = $request->domain;
        $findDomain = $this->getByDomain($domain);

            try{
                $client = new Client();
                $response = $client->request('POST', 'https://napi.arvancloud.com/cdn/4.0/domains/dns-service', [
                    'form_params' => [
                        "id" => $request->domain,
                    ],

                    'headers' => [
                        'Authorization' => $this->apiKey,
                    ]
                ]);


            }catch(Exception $e){
                return $e->getMessage();
            }

    }


}
