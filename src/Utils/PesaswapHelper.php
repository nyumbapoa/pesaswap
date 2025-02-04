<?php

namespace Nyumbapoa\Pesaswap\Utils;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

trait PesaswapHelper
{

    public $base_url;
    public $base_url_csharp;

    public function __construct()
    {
        $this->base_url = env('PESASWAP_ENV') == 'live'
            ? 'https://www.pesaswap.com'
            : 'https://devpesaswap.azurewebsites.net';

        $this->base_url_csharp = env('PESASWAP_ENV') == 'live'
            ? 'https://api.pesaswap.com'
            : 'https://devpesaswap-csharp.azurewebsites.net';
    }

    public function tokenization()
    {
        $url = $this->base_url_csharp . '/api/tokenization';

        $data = [
            'ConsumerKey' => env('PESASWAP_CONSUMER_KEY'),
            'ApiKey' => env('PESASWAP_API_KEY'),
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