<?php

namespace ComBank\Transactions\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:29 PM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Bank\BankAccount;

interface BankTransactionInterface
{
    public function applyTransaction(BankAccount $balance): float;
    public function getTransactionInfo(): String;
    public function getAmount(): float;
}
