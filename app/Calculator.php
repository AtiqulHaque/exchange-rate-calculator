<?php
namespace App;

class Calculator
{
    public $amounts = [];

    public function process($path)
    {
        $fileData = $this->readFile($path);

        if(!empty($fileData)){
            foreach ($fileData as $eachData){
                $entity = new  Entity(json_decode($eachData));

                $lookUpValue = $this->getLookupValue($entity->bin);

                if(!empty($lookUpValue)){
                    $rate = $this->getExchangeRates($entity->currency);

                    if ($entity->isCurrencyEuro() or $rate == 0) {
                        $finalAmount = $entity->amount;
                    }
                    if (!$entity->isCurrencyEuro() or $rate > 0) {
                        $finalAmount = $entity->amount / $rate;
                    }

                    $this->amounts[] = $entity->isEuValue() ? $finalAmount * 0.01 : $finalAmount * 0.02;

                }
                
            }
        }

        return $this;

    }

    public function readFile($path)
    {
        $content = [];
        if(file_exists($path)){
            $fileContent = file_get_contents($path);

            foreach (explode("\n", $fileContent) as $eachLine) {
                if (!empty($eachLine)) $content[] = $eachLine;
            }
        }

        return $content;
    }

    public function getLookupValue($bin)
    {
        if(empty($bin)) return false;

        try{
            $binResults = file_get_contents("https://lookup.binlist.net/{$bin}");
            
            if($binResults){
                $lookUpValues =  json_decode($binResults);
                return $lookUpValues->country->alpha2;
            }
            
            return false;
        } catch(\Exception $e){
            return false;
        }

    }

    public function getExchangeRates($countryCode){
        if(empty($countryCode)) return 0;

        try{
            $rates = file_get_contents('https://api.exchangeratesapi.io/latest');
            $rates = json_decode($rates, true);

            if(!empty($rates['rates']) && !empty($rates['rates'][$countryCode])){
                return $rates['rates'][$countryCode];
            }
            return 0;
        } catch(\Exception $e){
            return 0;
        }
    }

    public function printAmounts(){
        foreach ($this->amounts as $each){
            print($each);
            print("\n");
        }
    }


}