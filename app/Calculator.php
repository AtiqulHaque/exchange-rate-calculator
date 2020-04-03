<?php

namespace App;

use GuzzleHttp\Client;

class Calculator
{
    protected $commissions = [];

    /**
     * @param $path
     * @return $this
     */
    public function calculateCommission($path)
    {
        $fileData = $this->readFile($path);

        if (!empty($fileData)) {
            foreach ($fileData as $eachData) {

                $entity = new  Entity(json_decode($eachData));

                $lookUpValue = $this->getLookupValue($entity->bin);

                if (!empty($lookUpValue)) {
                    $rate = $this->getExchangeRates($entity->currency);

                    if ($entity->isCurrencyEuro() or $rate == 0) {
                        $finalAmount = $entity->amount;
                    }

                    if (!$entity->isCurrencyEuro() or $rate > 0) {
                        $finalAmount = $entity->amount / $rate;
                    }

                    $this->commissions[] = round(($entity->isEuValue($lookUpValue) ? $finalAmount * 0.01 : $finalAmount * 0.02), 2);

                }

            }
        }

        return $this;

    }

    /**
     * @param $path
     * @return array
     */
    protected function readFile($path)
    {
        $content = [];
        if (file_exists($path)) {
            $fileContent = file_get_contents($path);

            foreach (explode("\n", $fileContent) as $eachLine) {
                if (!empty($eachLine)) $content[] = $eachLine;
            }
        }

        return $content;
    }

    /**
     * @param $bin
     * @return bool
     */
    protected function getLookupValue($bin)
    {
        if (empty($bin)) return false;

        try {
            $client = new Client(['base_uri' => 'https://lookup.binlist.net/']);
            $response = $client->request('GET', "{$bin}");
            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $binResults = json_decode($body->getContents());
                return $binResults->country->alpha2;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * @param $currency
     * @return int
     */
    protected function getExchangeRates($currency)
    {
        if (empty($currency)) return 0;

        try {
            $client = new Client(['base_uri' => 'https://api.exchangeratesapi.io/']);
            $response = $client->request('GET', "latest");

            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $rates = json_decode($body->getContents(), true);
                if (!empty($rates['rates']) && !empty($rates['rates'][$currency])) {
                    return $rates['rates'][$currency];
                }
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     *
     */
    public function printCommissions()
    {
        foreach ($this->getCommissions() as $each) {
            print($each);
            print("\n");
        }
    }

    /**
     * @return array
     */
    public function getCommissions()
    {
        return $this->commissions;
    }


}