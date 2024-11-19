<?php

namespace ComBank\Support\Traits;

use ComBank\Transactions\Contracts\BankTransactionInterface;

trait ApiTrait
{
    public function convertBalance(float $amount): float
    {
        $ch = curl_init();

        $api = "https://api.fxfeed.io/v1/latest?api_key=fxf_SVpPYbu7Y2epYDj4xppY&from=EUR&to=USD&amount={$amount}";

        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $array = json_decode($result, true);

        if (isset($array['rates']['USD'])) {
            $convertedAmount = $amount * $array['rates']['USD'];
            return $convertedAmount;
        } else {
            return 0;
        }
    }

    public function validateEmail(String $gmail): bool
    {
        $ch = curl_init();

        $api =  "https://apilayer.net/api/check?access_key=5d759f78833b5505837c8b98fff23c3e&email={$gmail}";

        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $array = json_decode($result, true);

        if (isset($array["format_valid"]) && isset($array['mx_found']) && isset($array['disposable'])) {
            if ($array["format_valid"] && $array['mx_found'] && !$array['disposable']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    private $risk = 0;
    public function detectFraud(BankTransactionInterface $transaction): bool
    {
        $ch = curl_init();

        $api = "https://673784244eb22e24fca564ef.mockapi.io/Fraud";
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);


        $fraud = false;
        foreach ($data as $key => $value) {
            if ($data[$key]['Type'] == $transaction->getTransactionInfo()) {
                if ($data[$key]['Amount'] < $transaction->getAmount()) {
                    if (!$data[$key]['Allow']) {
                        $fraud = true;
                        $this->risk = $data[$key]['Risk'];
                        break;
                    } else {
                        $this->risk = $data[$key]['Risk'];
                        $fraud = false;
                    }
                }
            }
        }
        return $fraud;
    }

    /**
     * Get the value of risk
     */ 
    public function getRisk()
    {
        return $this->risk;
    }
}
