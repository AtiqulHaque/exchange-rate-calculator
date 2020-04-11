<?php


namespace App\Services;


interface ExchangeRateInterFace
{
    /**
     * @param $currency
     * @return mixed
     */
    public function getRate($currency);
}