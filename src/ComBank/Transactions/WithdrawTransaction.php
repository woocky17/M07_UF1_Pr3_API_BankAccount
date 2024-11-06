<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Bank\BankAccount;


class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{
    public function applyTransaction(BankAccount $balance): float
    {
        if ((($balance->getOverdraft()->getOverdraftFundsAmount() + ($balance->getBalance())) - ($this->amount)) >= 0) {
            return ($balance->getBalance() - $this->amount);
        }
        throw new InvalidOverdraftFundsException("failed transaction due to overdraft ");
    }

    public function getTransactionInfo (): String {
        return "WITHDRAW_TRANSACTION";
    }
}
