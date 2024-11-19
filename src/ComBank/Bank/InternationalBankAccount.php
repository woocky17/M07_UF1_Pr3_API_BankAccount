<?php

namespace ComBank\Bank;

use ComBank\Bank\Person\Person;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;


class InternationalBankAccount extends BankAccount
{
 
    public function __construct(float $balance, Person $person = null,OverdraftInterface $overdraft = null,string $currency = "€ (Euro)")
    {
        parent::__construct( $balance, $person,$overdraft , $currency);
    }


  
    public function getConvertedBalance(): float
{
    $balance = $this->getBalance();

    return $this->convertBalance($balance);
}


    public function getConvertedCurrency(): String {
        return "$ (USD)";
    }
}
