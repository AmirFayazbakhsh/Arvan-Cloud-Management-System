<?php

namespace App\Http\Controllers\Api\Domains;
use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DomainsController extends Controller
{
    public $apiKey = 'Apikey 00eab9db-87ba-5f0f-a2df-3295774a913c';

    //get All domain
    public function getAllDomains(){


        try{

            $client = new Client();
            $response = $client->request('GET','https://napi.arvancloud.com/cdn/4.0/domains',[

                'headers' => [
                    'Authorization' => $this->apiKey,
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody(),true);
                //perform your action with $response
                return  $response['data'];
           }

        }catch(ClientException $e){
            if($e->getResponse()->getStatusCode() == 404){
                // return $e->getResponse()->getStatusCode();
                return "nothing";
            }else{
                return "faild to request";
            }
        }

    }


    //get single domain
    public function getByDomain($domain){
        try{
            $client = new Client();
            $response = $client->request('GET','https://napi.arvancloud.com/cdn/4.0/domains/'.$domain,[

                'headers' => [
                    'Authorization' => $this->apiKey,
                ]
            ]);

            if($response->getStatusCode() == 200){
                return  json_decode($response->getBody(),true);
            }


        }catch(ClientException $e){
            if($e->getResponse()->getStatusCode() == 404){
                // return $e->getResponse()->getStatusCode();
                return "domain is not exist";
            }else{
                return "faild to request";
            }
        }



    }


    //create domain
    public function createDomain(Request $request){

                $client = new Client();
                try{

                $response = $client->request('POST', 'https://napi.arvancloud.com/cdn/4.0/domains/dns-service', [
                    'form_params' => [
                        "domain" => $request->domain,
                        "domain_type" => "full"
                    ],

                    'headers' => [
                        'Authorization' => $this->apiKey,
                    ]
                ]);


                if ($response->getStatusCode() == 201) {
                    // $response = json_decode($request->getBody(),true);
                    //perform your action with $response
                    // return $response;
                    return "domain created   ". $response->getStatusCode();

                }
               return $response->getStatusCode();

            }catch(GuzzleException $e){
                if($e->getCode() == 302){
                    return "domain is already exist";
                }
            }





    }


    public function deleteDomain($domain){
        $findDomain = $this->getByDomain($domain)['data']['id'];
        if($findDomain){
            try{

                $client = new Client();
                $request = $client->request('DELETE', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain, [
                    'form_params' => [
                        "id" => $findDomain,
                    ],

                    'headers' => [
                        'Authorization' => $this->apiKey,
                    ]
                ]);

                if ($request->getStatusCode() == 200) {

                    //perform your action with $response
                    return "domain deleted";
                }



            }catch(Exception $e){
                return "";
            }
        }else{
            return "domain dont exist";
        }


    }


    // Set custom NS records for the domain
    // this option need Professional plan of arvan

    public function updateDomain(Request $request,$domain){
            $client = new Client();
            $res = $client->request('PUT', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/ns-keys', [
                'form_params' => [

                    "ns_keys" => [$request->ns_keys[0],$request->ns_keys[1]],

                ],

                'headers' => [
                    'Authorization' => $this->apiKey,
                ]
            ]);


            if ($request->getStatusCode() == 200) {
                $request = json_decode($request->getBody(),true);
                //perform your action with $response
                return "domain updated";
            }

    }


    //Reset custom Nameserver keys to the default values for the domain
    public function resetDomain($domain){

            try{

                $client = new Client();
                $request = $client->request('DELETE', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/ns-keys', [
                    'headers' => [
                        'Authorization' => $this->apiKey,
                    ]
                ]);

                if ($request->getStatusCode() == 200) {
                    $request = json_decode($request->getBody(),true);
                    //perform your action with $response
                    return "domain deleted";
                }


            }catch(Exception $e){
                return $e->getMessage();
            }

    }


}
