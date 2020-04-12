<?php

namespace App;

use App\Reader\Reader;
use App\Services\ExchangeRateInterFace as ExchangeRate;
use App\Services\LookUp;
use Exception;

class Calculator
{
    protected $commissions = [];

    /**
     * @param $path
     * @param Reader $fileReader
     * @param LookUp $binLookUp
     * @param ExchangeRate $exchangeRate
     * @return $this
     */
    public function calculateCommission($path, Reader $fileReader, LookUp $binLookUp, ExchangeRate $exchangeRate)
    {
        $fileData = $fileReader->read($path);

        if (!empty($fileData)) {
            foreach ($fileData as $eachData) {

                $entity = new  Entity(json_decode($eachData));

                try {
                    $lookUpValue = $binLookUp->getLookUpValue($entity->bin);
                } catch (Exception $e) {
                    $lookUpValue = null;
                }

                if (!empty($lookUpValue)) {

                    try {
                        $rate = $exchangeRate->getRate($entity->currency);
                    } catch (Exception $e) {
                        $rate = 0;
                    }


                    if ($entity->isCurrencyEuro() or $rate == 0) {
                        $finalAmount = $entity->amount;
                    }

                    if (!$entity->isCurrencyEuro() or $rate > 0) {
                        $finalAmount = $entity->amount / $rate;
                    }

                    $this->commissions[] = round(
                        ($entity->isEuValue($lookUpValue) ? $finalAmount * 0.01 : $finalAmount * 0.02)
                        , 2);

                }

            }
        }

        return $this;

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