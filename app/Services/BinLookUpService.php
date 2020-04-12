<?php
namespace App\Services;


use GuzzleHttp\Client;

class BinLookUpService implements LookUp
{

    /**
     * @param $binNumber
     * @return bool
     * @throws \Exception
     */
    public function getLookUpValue($binNumber)
    {
        $client = new Client(['base_uri' => 'https://lookup.binlist.net/']);
        $response = $client->request('GET', "{$binNumber}");
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $binResults = json_decode($body->getContents());
            return $binResults->country->alpha2;
        }

        throw new \Exception("Something went wrong");
    }
}