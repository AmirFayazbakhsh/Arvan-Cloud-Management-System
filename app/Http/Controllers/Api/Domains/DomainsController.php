<?php

namespace App\Http\Controllers\Api\Domains;
use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\VarDumper\VarDumper;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\TooManyRedirectsException;

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
                    'Accept' => 'application/json',

                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody(),true);
                //perform your action with $response
                return  $response['data'];
            }

        }catch(RequestException $e){
            if ($e->hasResponse()) {

                return $e->getMessage();

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
                    'Accept' => 'application/json',

                ]
            ]);

            if($response->getStatusCode() == 200){
                return  json_decode($response->getBody(),true);
            }


        }catch(RequestException $e){

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $response->getReasonPhrase(); // Response message;

            }
        }



    }


    //create domain
    public function createDomain(Request $request){

                try{
                $client = new Client();
                $response = $client->request('POST', 'https://napi.arvancloud.com/cdn/4.0/domains/dns-service', [
                    'form_params' => [
                        "domain" => $request->domain,
                        "domain_type" => "full"
                    ],

                    'headers' => [
                        'Authorization' => $this->apiKey,
                        'Accept' => 'application/json',

                    ]
                ]);


                if ($response->getStatusCode() == 201) {

                    return "domain created   ". $response->getStatusCode();

                }
               return $response->getStatusCode();

            }catch(RequestException $e){

                if ($e->hasResponse()) {

                    return $e->getMessage();

                }
            }
    }




    //delete domain
    public function deleteDomain($domain){

        $findDomain = $this->getByDomain($domain);;

        if($findDomain !== 'Not Found'){

            try{

                    $client = new Client();
                    $request = $client->request('DELETE', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain, [
                        'form_params' => [
                            "id" => $findDomain['data']['id'],
                        ],

                        'headers' => [
                            'Authorization' => $this->apiKey,
                            'Accept' => 'application/json',

                        ]
                    ]);


                if ($request->getStatusCode() == 200) {

                    //perform your action with $response
                    return "domain deleted";
                }

            }catch(RequestException $e){

                if ($e->hasResponse()) {

                    return $e->getMessage();

                }

            }


        }else{

            return "domain not found";
        }

    }


    // Set custom NS records for the domain
    // this option need Professional plan of arvan

    public function updateDomain(Request $request,$domain){

        try{

            $client = new Client();
            $res = $client->request('PUT', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/ns-keys', [
                'form_params' => [

                    "ns_keys" => [$request->ns_keys[0],$request->ns_keys[1]],

                ],

                'headers' => [
                    'Authorization' => $this->apiKey,
                    'Accept' => 'application/json',
                ]
            ]);


            if ($res->getStatusCode() == 200) {
                $res = json_decode($res->getBody(),true);
                //perform your action with $response
                return "domain updated";
            }
        }catch(RequestException $e){

            if ($e->hasResponse()) {

               return $e->getMessage();

            }
        }

    }


    //Reset custom Nameserver keys to the default values for the domain
    public function resetDomain($domain){

            try{

                $client = new Client();
                $request = $client->request('DELETE', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/ns-keys', [
                    'headers' => [
                        'Authorization' => $this->apiKey,
                        'Accept' => 'application/json',

                    ]
                ]);

                if ($request->getStatusCode() == 200) {
                    $request = json_decode($request->getBody(),true);
                    //perform your action with $response
                    return "Reset custom Nameserver keys to the default values";
                }


            }catch(RequestException $e){

                if ($e->hasResponse()) {

                    return $e->getResponse();

                }
            }

    }




    public function ActivityDomain($domain){
        try{
            $client = new Client();
            $request = $client->request('GET', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/ns-keys/check', [
                'headers' => [
                    'Authorization' => $this->apiKey,
                    'Accept' => 'application/json',

                ]
            ]);

            $response = json_decode($request->getBody(),true);
            $isActive = response()->json($response['data']['ns_status'], 200);
            // //perform your action with $response

                return "activity --> " . $isActive->content();


        }catch(ClientException $e){

            // echo Psr7\Message::toString($e->getRequest());
            if($e->getCode() == 404){
                return "Domain is not exist";
            }
            return $e->getCode();
        }


    }


    // Set a custom record for using CNAME Setup
    //this option need Enterprise plan
    public function cnameSetup(Request $data, $domain){

        try{

            $client = new Client();
            $request = $client->request('PUT', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/cname-setup/custom', [
                'form_params' => [
                    "address" => $data->address,
                ],

                'headers' => [
                    'Authorization' => $this->apiKey,
                    'Accept' => 'application/json',

                ]
            ]);

            if ($request->getStatusCode() == 200) {
                $request = json_decode($request->getBody(),true);
                //perform your action with $response
                return "Set a custom record for using CNAME Setup";
            }

        }catch(RequestException $e){

            if ($e->hasResponse()) {

                return $e->getMessage();
            }
        }


    }


    //Reset the custom record of CNAME Setup to the default value
    //this option need Enterprise plan
    public function resetCnameSetup($domain){

        try{

            $client = new Client();
            $request = $client->request('DELETE', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/cname-setup/custom', [
                'headers' => [
                    'Authorization' => $this->apiKey,
                    'Accept' => 'application/json',

                ]
            ]);

            if ($request->getStatusCode() == 200) {
                //perform your action with $response
                $request = json_decode($request->getBody(),true);
                return $request['message'];
            }

        }catch(RequestException $e){

            if ($e->hasResponse()) {

                return $e->getMessage();
            }
        }
    }


    // Convert domain setup to cname
    // Cname setup can be used with sub domain
    public function convertToCname($domain){

        try{
            $client = new Client();
            $request = $client->request('POST', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/cname-setup/convert', [
                'headers' => [
                    'Authorization' => $this->apiKey,
                    'Accept' => 'application/json',

                ]
            ]);


            if ($request->getStatusCode() == 200) {
                //perform your action with $response
                return "successfully Convert domain setup to cname";
            }

        }catch(RequestException $e){


            if ($e->hasResponse()) {

                return $e->getMessage();
            }

        }
    }


     // Check Cname Setup to find whether domain is activated
    public function checkCnameForActivity($domain){

        try{
            $client = new Client();
            $request = $client->request('GET', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/cname-setup/check', [
                'headers' => [
                    'Authorization' => $this->apiKey,
                    'Accept' => 'application/json',

                ]
            ]);

            if ($request->getStatusCode() == 200) {
                //perform your action with $response
                return "successfully Convert domain setup to cname";
            }

        }catch(RequestException $e){

            if ($e->hasResponse()) {

                return $e->getMessage();
            }
        }


    }

    // Clone a domain config from another one
    public function cloneConfig($domain,Request $request){


            try{

                $findDomain = $this->getByDomain($request->from);;


                if($findDomain !== 'Not Found'){

                    $client = new Client();
                    $res = $client->request('POST', 'https://napi.arvancloud.com/cdn/4.0/domains/'.$domain.'/clone', [
                        'headers' => [
                            'Authorization' => $this->apiKey,
                            'Accept' => 'application/json',

                        ],

                        'form_params' => [
                            "from" => $findDomain,
                        ],
                    ]);

                    if ($res->getStatusCode() == 200) {
                        //perform your action with $response
                        return "successfully Clone a domain config from another one";
                    }

                }else{

                    return $request->from.' is not found';
                }



            }catch(RequestException $e){

                if($e->getCode() == 404){
                    return 'the '.$domain.' not found';
                }

                if ($e->hasResponse()) {

                    return $e->getMessage();
                }

            }


    }









}
