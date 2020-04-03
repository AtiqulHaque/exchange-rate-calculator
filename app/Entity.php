<?php
namespace App;

class Entity
{
    public $euValue = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];
    public $bin;
    public $amount;
    public $currency;

    public function __construct($obj)
    {
        $this->bin = $obj->bin;
        $this->amount = $obj->amount;
        $this->currency = $obj->currency;
    }

    public function isEuValue($currency)
    {
        if (in_array($currency, $this->euValue)) return true;
        return false;
    }

    public function isCurrencyEuro(){
        return $this->currency == 'EUR';
    }

}