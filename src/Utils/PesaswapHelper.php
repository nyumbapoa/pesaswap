<?php

namespace Nyumbapoa\Pesaswap\Utils;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

trait PesaswapHelper
{

    public  $base_url;
    public $base_url_csharp;
    public $api_key;
    public $consumer_key;

    public function __construct()
    {
        $this->base_url = config('pesaswap.environment') == 'live'
            ? 'https://www.pesaswap.com'
            : 'https://devpesaswap.azurewebsites.net';

        $this->base_url_csharp = config('pesaswap.environment') == 'live'
            ? 'https://api.pesaswap.com'
            : 'https://devpesaswap-csharp.azurewebsites.net';

        $this->api_key = config('pesaswap.api_key');
        $this->consumer_key = config('pesaswap.consumer_key');
    }

    public function tokenization()
    {
        $url = $this->base_url_csharp . '/api/tokenization';

        $data = [
            'ConsumerKey' => config('pesaswap.consumer_key'),
            'ApiKey' => config('pesaswap.api_key'),
        ];

        $response = Http::post($url, $data);

        return $response->json('accessToken');
    }


    public function phoneValidator($phoneno)
    {
        // Some validations for the phonenumber to format it to the required format
        $phoneno = (substr($phoneno, 0, 1) == '+') ? str_replace('+', '', $phoneno) : $phoneno;
        $phoneno = (substr($phoneno, 0, 1) == '0') ? preg_replace('/^0/', '254', $phoneno) : $phoneno;
        $phoneno = (substr($phoneno, 0, 1) == '7') ? "254{$phoneno}" : $phoneno;

        return $phoneno;
    }

    public function validationResponse($result_code, $result_description)
    {
        $result = json_encode([
            'ResultCode' => $result_code,
            'ResultDesc' => $result_description,
        ]);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        $response->setContent($result);

        return $response;
    }
}