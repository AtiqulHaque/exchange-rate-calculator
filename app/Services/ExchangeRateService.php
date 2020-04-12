<?php


namespace App\Services;


use GuzzleHttp\Client;

class ExchangeRateService implements ExchangeRateInterFace
{

    /**
     * @param $currency
     * @return int|mixed
     * @throws \Exception
     */
    public function getRate($currency)
    {
        $client = new Client(['base_uri' => 'https://api.exchangeratesapi.io/']);
        $response = $client->request('GET', "latest");

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $rates = json_decode($body->getContents(), true);
            if (!empty($rates['rates']) && !empty($rates['rates'][$currency])) {
                return $rates['rates'][$currency];
            }
            return 0;
        }
        throw new \Exception("Something went wrong");
    }
}