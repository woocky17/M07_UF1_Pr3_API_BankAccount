<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\InvalidOverdraftFundsException;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
pl('--------- [Start testing bank account #1, No overdraft] --------');
$bankAccount1 = new BankAccount(400 );

$balance1 = $bankAccount1->getBalance();

try {
    // show balance account
    pl( 'My balance : '. $balance1);
    // close account

    $bankAccount1->closeAccount();
 
    // reopen account

    $bankAccount1->reopenAccount();
    pl("My account is now reopened.");

    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());
    
    $deposit = new DepositTransaction(150);
    $deposit->applyTransaction  ($bankAccount1);
    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());

    $deposit = new WithdrawTransaction(25);
    $bankAccount1->setBalance($deposit->applyTransaction  ($bankAccount1));
    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
    $deposit = new WithdrawTransaction(600);
    $bankAccount1->setBalance($deposit->applyTransaction  ($bankAccount1));
} catch (InvalidOverdraftFundsException $e) {
    pl("Error transaction: Insufficient balance to complete the withdrawal");
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
    
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());




//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {

    $bankAccount2 = new BankAccount(200,new SilverOverdraft );
    $balance2 = $bankAccount2->getBalance();

    // show balance account
    pl( 'My balance : '. $balance2);

    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    $deposit = new DepositTransaction(100);
    $deposit->applyTransaction  ($bankAccount2);
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
    $deposit = new WithdrawTransaction(300);
    $bankAccount2->setBalance($deposit->applyTransaction  ($bankAccount2));
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
    $deposit = new WithdrawTransaction(50);
    $bankAccount2->setBalance($deposit->applyTransaction  ($bankAccount2));
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $deposit = new WithdrawTransaction(120);
    $bankAccount2->setBalance($deposit->applyTransaction  ($bankAccount2));
} catch (InvalidOverdraftFundsException $e) {
    pl("Error transaction: Insufficient balance to complete the withdrawal");
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
    $deposit = new WithdrawTransaction(20);
    $bankAccount2->setBalance($deposit->applyTransaction  ($bankAccount2));
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

try {
   $bankAccount2->closeAccount();
   $bankAccount2->closeAccount();
} catch (BankAccountException $e) {
    pl($e->getMessage());
}
