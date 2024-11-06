<?php namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Bank\BankAccount;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface
{
   public function applyTransaction(BankAccount $account): float
    {
        $newBalance = $account->getBalance() + $this->amount; 
        $account->setBalance($newBalance); 
        return $newBalance; 
    }

    public function getTransactionInfo (): String {
        return "DEPOSIT_TRANSACTION";
    }
}
