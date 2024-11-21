<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

require_once __DIR__ . '/../vendor/autoload.php';


use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Bank\Person\Person;
use ComBank\Bank\NationalBankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Support\Traits\ApiTrait;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
pl('--------- [Start testing bank account #1, No overdraft] --------');
$bankAccount1 = new BankAccount(400);

$balance1 = $bankAccount1->getBalance();

try {
    // show balance account
    pl('My balance : ' . $balance1);
    // close account

    $bankAccount1->closeAccount();

    // reopen account

    $bankAccount1->reopenAccount();
    pl("My account is now reopened.");

    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());

    $deposit = new DepositTransaction(150);
    $deposit->applyTransaction($bankAccount1);
    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());

    $deposit = new WithdrawTransaction(25);
    $bankAccount1->setBalance($deposit->applyTransaction($bankAccount1));
    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600

    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
    $deposit = new WithdrawTransaction(600);
    $bankAccount1->setBalance($deposit->applyTransaction($bankAccount1));
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

    $bankAccount2 = new BankAccount(200, null, new SilverOverdraft);
    $balance2 = $bankAccount2->getBalance();

    // show balance account
    pl('My balance : ' . $balance2);

    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    $deposit = new DepositTransaction(100);
    $deposit->applyTransaction($bankAccount2);
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
    $deposit = new WithdrawTransaction(300);
    $bankAccount2->setBalance($deposit->applyTransaction($bankAccount2));
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
    $deposit = new WithdrawTransaction(50);
    $bankAccount2->setBalance($deposit->applyTransaction($bankAccount2));
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $deposit = new WithdrawTransaction(120);
    $bankAccount2->setBalance($deposit->applyTransaction($bankAccount2));
} catch (InvalidOverdraftFundsException $e) {
    pl("Error transaction: Insufficient balance to complete the withdrawal");
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
    $deposit = new WithdrawTransaction(20);
    $bankAccount2->setBalance($deposit->applyTransaction($bankAccount2));
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

//---[Bank account 3]---/
pl('--------- [Start testing national account, (No conversion)] --------');

$bankAccount3 = new NationalBankAccount(500.0);
echo "My balance: " . $bankAccount3->getBalance() . $bankAccount3->getCurrency() . "<br>";

pl('--------- [Start testing international account, (Dollar convers ion)] --------');


$bankAccount4 = new InternationalBankAccount(300.0);

echo "My balance: " . $bankAccount4->getBalance() . " " . $bankAccount4->getCurrency() . "<br>";


echo ("Converted balance: " . $bankAccount4->getConvertedBalance() . " " . $bankAccount4->getConvertedCurrency());

echo "<br>";
pl('--------- [Start testing national account] --------');

$person1 = new Person("david", "AAA1", "david@gamil.com");
$bankAccount5 = new NationalBankAccount(500.0, $person1);

echo "<br>";
pl('--------- [Start testing international account] --------');
$person2 = new Person("david", "AAA2", "david@pppp.com");
$bankAccount6 = new InternationalBankAccount(500.0, $person2);




echo "<br>";

$bankAccount7 = new NationalBankAccount(10000.0);

pl('--------- [Start testing National account (Fraud Deposit)] --------');
echo "My balance: ". $bankAccount7->getBalance();
$bankAccount7->transaction (new DepositTransaction(21000));
pl('My new balance after deposit (+21000) : ' . $bankAccount7->getBalance());

pl('--------- [Start testing National account (No Fraud Withdraw)] --------');
echo "My balance: ". $bankAccount7->getBalance();
$bankAccount7->transaction (new WithdrawTransaction(1500));
pl('My new balance after withdraw (-1500) : ' . $bankAccount7->getBalance());


$bankAccount8 = new InternationalBankAccount(10000.0);

pl('--------- [Start testing international account (No Fraud Deposit)] --------');
echo "My balance: ". $bankAccount8->getBalance();
$bankAccount8->transaction (new DepositTransaction(15000));
pl('My new balance after deposit (+15000) : ' . $bankAccount8->getBalance());

pl('--------- [Start testing international account (Fraud Withdraw)] --------');

echo "My balance: ". $bankAccount8->getBalance();
$bankAccount8->transaction (new WithdrawTransaction(7000));
pl('My new balance after deposit (-7000) : ' . $bankAccount8->getBalance());


echo "<br>";
pl("--------- [Start testing buy Bitcoin (insuficiente balance)] --------");

echo "Precio de 1 bitcoin: ". $bankAccount8->PriceBitcoins();
$bankAccount8->buyBitcoin(1);
echo "<br>";
echo "My balance: ". $bankAccount8->getBalance();
echo "<br>";

pl("--------- [Start testing buy Bitcoin (balance suficiente)] --------");

echo "Precio de 1 bitcoin: ". $bankAccount8->PriceBitcoins();
$bankAccount8->buyBitcoin(0.01);
echo "<br>";
echo "My balance: ". $bankAccount8->getBalance();
echo "<br>";

pl("--------- [Start testing sell Bitcoin (insuficiente balance)] --------");
echo "Precio de 1 bitcoin: ". $bankAccount8->PriceBitcoins();
$bankAccount8->sellBitcoin(1);
echo "<br>";
echo "My balance: ". $bankAccount8->getBalance();
echo "<br>";



pl("--------- [Start testing sell Bitcoin (balance suficiente)] --------");
echo "Precio de 1 bitcoin: ". $bankAccount8->PriceBitcoins();
$bankAccount8->sellBitcoin(0.01);
echo "<br>";
echo "My balance: ". $bankAccount8->getBalance();
echo "<br>";