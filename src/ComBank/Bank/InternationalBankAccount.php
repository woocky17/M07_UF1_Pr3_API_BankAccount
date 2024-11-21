<?php

namespace ComBank\Bank;

use ComBank\Bank\Person\Person;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;


class InternationalBankAccount extends BankAccount
{
  
    public function getConvertedBalance(): float
{
    $balance = $this->getBalance();

    return $this->convertBalance($balance);
}


    public function getConvertedCurrency(): String {
        return "$ (USD)";
    }
}
