<?php

namespace Nyumbapoa\Pesaswap;


use Illuminate\Support\Facades\Http;
use Nyumbapoa\Pesaswap\Utils\PesaswapHelper;

class Pesaswap
{
    use PesaswapHelper;

    public function __construct()
    {
        $this->base_url = env('PESASWAP_ENV') == 'live'
            ? 'https://www.pesaswap.com'
            : 'https://devpesaswap.azurewebsites.net';

        $this->base_url_csharp = env('PESASWAP_ENV') == 'live'
            ? 'https://api.pesaswap.com'
            : 'https://devpesaswap-csharp.azurewebsites.net';

    }

    public function createCustomer($firstname, $lastname, $email, $phone, $address1, $address2, $state, $country, $external_id)
    {
        $url = $this->base_url . '/api/pesaswap/create/customer';

        $data = [
            'api_key' => env('PESASWAP_API_KEY'),
            'consumer_key' => env('PESASWAP_CONSUMER_KEY'),
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'phone' => $phone,
            'address1' => $address1,
            'address2' => $address2,
            'state' => $state,
            'country' => $country,
            'external_id' => $external_id,
            'environment' => env('PESASWAP_ENV'),
        ];

        $response = Http::post($url, $data);

        return $response->json();
    }

    public function cardPayment($currency, $amount, $expiry_date, $card_security_code, $credit_card_number, $external_id, $transaction_external_id)
    {
        $url = $this->base_url . '/api/regular/card-payment';

        $data = [
            'api_key' => env('PESASWAP_API_KEY'),
            'consumer_key' => env('PESASWAP_CONSUMER_KEY'),
            'currency' => $currency,
            'amount' => $amount,
            'expiry_date' => $expiry_date,
            'card_security_code' => $card_security_code,
            'credit_card_number' => $credit_card_number,
            'external_id' => $external_id,
            'transaction_external_id' => $transaction_external_id,
            'environment' => env('PESASWAP_ENV'),
        ];

        $response = Http::post($url, $data);

        return $response->json();
    }

    public function mpesaBalance()
    {
        $url = $this->base_url_csharp . '/api/balance';

        $response = Http::withHeaders([
            'MerchantIdentifier' => '867tLWWx0Pa4GyJ9ZcmSxRrIv',
            'Country' => 'KE',
            'Currency' => 'KES',
        ])->withToken($this->tokenization())->get($url);

        return $response->json();
    }

    public function collectionPayment($country = 'KE', $currency = 'KES', $amount, $phone, $external_id, $comment, $processor)
    {
        $url = $this->base_url_csharp . '/api/collection-payment';

        $data = [
            'PaybillDescription' => 'PaybillDescription',
            'Country' => $country,
            'Currency' => $currency,
            'Amount' => $amount,
            'PhoneNumber' => $phone,
            'TransactionExternalId' => $external_id,
            'Comment' => $comment,
            'Processor' => $processor,
        ];

        $response = Http::withToken($this->tokenization())->post($url, data: $data);

        return $response->json();
    }

    public function mpesaC2bBillRefNo($paybillDescription, $amount, $commandId = 'CustomerPayBillOnline', $phone, $short_code, $external_id, $billRefNumber)
    {
        $url = $this->base_url_csharp . '/api/mpesa-c2b-billrefno';

        $data = [
            'PaybillDescription' => $paybillDescription,
            'Amount' => $amount,
            'CommandId' => $commandId,
            'Msisdn' => $phone,
            'ShortCode' => $short_code,
            'ExternalId' => $external_id,
            'BillRefNumber' => $billRefNumber
        ];

        $response = Http::withToken($this->tokenization())->post($url, data: $data);

        return $response->json();
    }

    public function refund($merchantIdentifier, $sourceTransactionExternalId, $refundTransactionExternalId)
    {
        $url = $this->base_url_csharp . '/api/refund';

        $data = [
            'MerchantIdentifier' => $merchantIdentifier,
            'SourceTransactionExternalId' => $sourceTransactionExternalId,
            'RefundTransactionExternalId' => $refundTransactionExternalId,
        ];

        $response = Http::withToken($this->tokenization())->post($url, data: $data);

        return $response->json();
    }

    public function reconcileTransaction($transaction_external_id)
    {
        $url = $this->base_url . '/api/reconcile-transaction';

        $data = [
            'api_key' => env('PESASWAP_API_KEY'),
            'consumer_key' => env('PESASWAP_CONSUMER_KEY'),
            'transaction_external_id' => $transaction_external_id,
        ];

        $response = Http::post($url, $data);

        return $response->json();
    }

    public function paymentRequest($paybill_description, $desc, $range, $external_id, $billing_date, $last_billing_date, $total_amount)
    {
        $url = $this->base_url . '/api/payment/request';

        $data = [
            'consumer_key' => env('PESASWAP_CONSUMER_KEY'),
            'api_key' => env('PESASWAP_API_KEY'),
            'paybill_description' => $paybill_description,
            'description' => $desc,
            'range' => $range,
            'external_id' => $external_id,
            'billing_date' => $billing_date,
            'last_billing_date' => $last_billing_date,
            'total_amount' => $total_amount
        ];

        $response = Http::post($url, $data);

        return $response->json();
    }

    public function paymentRequestRecurringBill($paybill_description, $description, $range, $external_id, $billing_date, $last_billing_date, $total_amount)
    {
        $url = $this->base_url . '/api/payment/request/recurring/billing';

        $data = [
            'consumer_key' => env('PESASWAP_CONSUMER_KEY'),
            'api_key' => env('PESASWAP_API_KEY'),
            'paybill_description' => $paybill_description,
            'description' => $description,
            'range' => $range,
            'external_id' => $external_id,
            'billing_date' => $billing_date,
            'last_billing_date' => $last_billing_date,
            'total_amount' => $total_amount
        ];

        $response = Http::post($url, $data);

        return $response->json();
    }

    public function airtelCollection($merchantIdentifier, $transactionExternalId, $msisdn, $amount, $country = 'KE', $currency = 'KES')
    {
        $url = $this->base_url_csharp . '/api/wallet/airtel-collection-payment';

        $data = [
            'MerchantIdentifier' => $merchantIdentifier,
            'TransactionExternalId' => $transactionExternalId,
            'Msisdn' => $msisdn,
            'Amount' => $amount,
            'Country' => $country,
            'Currency' => $currency
        ];

        $response = Http::withToken($this->tokenization())->post($url, data: $data);

        return $response->json();
    }

    public function balanceSummary($merchantIdentifier, $originalTransactionExternalId, $transactionExternalId)
    {
        $url = $this->base_url_csharp . '/api/wallet/balance-summary';

        $data = [
            'MerchantIdentifier' => $merchantIdentifier,
            'OriginalTransactionExternalId' => $originalTransactionExternalId,
            'TransactionExternalId' => $transactionExternalId
        ];

        $response = Http::withToken($this->tokenization())->post($url, data: $data);

        return $response->json();
    }

    public function walletMpesaLipa($merchantIdentifier, $transactionExternalId, $amount, $phone, $comment)
    {
        $url = $this->base_url_csharp . '/api/wallet/mpesa-lipa';

        $data = [
            'MerchantIdentifier' => $merchantIdentifier,
            'TransactionExternalId' => $transactionExternalId,
            'Amount' => $amount,
            'PhoneNumber' => $phone,
            'Comment' => $comment,
        ];

        $response = Http::withToken($this->tokenization())->post($url, data: $data);

        return $response->json();
    }

    public function walletMpesaB2c($merchantIdentifier, $transactionExternalId, $amount, $commandId, $phone)
    {
        $url = $this->base_url_csharp . '/api/wallet/mpesa-b2c';

        $data = [
            'MerchantIdentifier' => $merchantIdentifier,
            'TransactionExternalId' => $transactionExternalId,
            'Amount' => $amount,
            'CommandId' => $commandId,
            'PartyB' => $phone
        ];

        $response = Http::withToken($this->tokenization())->post($url, data: $data);

        return $response->json();
    }

    public function walletRefund($merchantIdentifier, $originalTransactionExternalId, $transactionExternalId)
    {
        $url = $this->base_url_csharp . '/api/wallet/refund';

        $data = [
            'MerchantIdentifier' => $merchantIdentifier,
            'OriginalTransactionExternalId' => $originalTransactionExternalId,
            'TransactionExternalId' => $transactionExternalId
        ];

        $response = Http::withToken($this->tokenization())->post($url, data: $data);

        return $response->json();
    }

    public function walletTransaction($merchantIdentifier, $transactionExternalId, $fromDate, $toDate, $status, $skip, $take)
    {
        $url = $this->base_url_csharp . '/api/wallet/transactions';

        $data = [
            'MerchantIdentifier' => $merchantIdentifier,
            'TransactionExternalId' => $transactionExternalId,
            'FromDate' => $fromDate,
            'ToDate' => $toDate,
            'Status' => $status,
            'Skip' => $skip,
            'Take' => $take,
        ];

        $response = Http::withToken($this->tokenization())->post($url, data: $data);

        return $response->json();
    }
}