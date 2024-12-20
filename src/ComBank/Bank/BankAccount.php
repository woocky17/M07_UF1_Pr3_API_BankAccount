<?php

namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BackAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Bank\Person\Person;
use ComBank\Support\Traits\ApiTrait;

class BankAccount implements BackAccountInterface
{
    use ApiTrait;
    protected ?Person $person;
    protected float $balance;
    protected string $status;
    protected OverdraftInterface  $overdraft;
    protected string $currency;
    protected float $bitcoin;

    public function __construct(float $balance, Person $person = null, OverdraftInterface $overdraft = null, string $currency = " € (Euro)")
    {
        $this->balance = $balance;
        $this->status = $status = BackAccountInterface::STATUS_OPEN;
        $this->currency = $currency;
        $this->overdraft = $overdraft ?? new NoOverdraft();
        $this->person = $person;
        $this->bitcoin = 0.0;
    }

    public function transaction(BankTransactionInterface $transaction): void
    {

        if ($this->status === BackAccountInterface::STATUS_OPEN) {
            try {
                if (!$this->detectFraud($transaction)) {
                    $newBalance = $transaction->applyTransaction($this);
                    $this->balance = $newBalance;
                    echo "<br>";
                    echo "Allow";
                    echo "<br>";
                    echo "Risk: " . $this->getRisk();
                } else {
                    echo "<br>";
                    echo "Blocked";
                    echo "<br>";
                    echo "Risk: " . $this->getRisk();
                }
            } catch (InvalidOverdraftFundsException $e) {
                throw new FailedTransactionException("");
            }
        } else {
            throw new BankAccountException("The transaction have failed because the account is closed");
        }
    }

    public function openAccount(): bool
    {
        if ($this->status === BackAccountInterface::STATUS_CLOSED) {
            return false;
        }

        $this->status = BackAccountInterface::STATUS_OPEN;
        return true;
    }


    public function reopenAccount(): void
    {
        if ($this->status === BackAccountInterface::STATUS_CLOSED) {
            $this->status = BackAccountInterface::STATUS_OPEN;
        } else {
            throw new BankAccountException("It is already open");
        }
    }

    public function closeAccount(): void
    {
        if (!$this->openAccount()) {
            throw new BankAccountException("Error: Account is already closed");
        } else {
            echo "<br>My account is now closed.<br>";
            $this->status = BackAccountInterface::STATUS_CLOSED;
        }
    }

    public function getBalance(): float
    {
        return round($this->balance,2);
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function getOverdraft(): OverdraftInterface
    {
        return $this->overdraft;
    }

    public function applyOverdraft(OverdraftInterface $overdraft): void
    {
        $this->overdraft = $overdraft;
    }

    /**
     * Get the value of currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get the value of person
     */
    public function getPerson()
    {
        return $this->person;
    }


    public function buyBitcoin(float $amountBitcoin)
    {
        $bitcoin = $this->PriceBitcoins() * $amountBitcoin;

        if (!($this->getBalance() - $bitcoin <= 0)) {
            $this->setBalance($this->getBalance() - $bitcoin);
            $this->setBitcoin($amountBitcoin);
            echo "<br>";
            echo "Has comprado " . $this->getBitcoin() . " bitcoins";
        } else {
            echo "<br>";
            echo "No tienes suficiente balance para comprar " . $amountBitcoin . " bitcoins";
        }
    }
    public function sellBitcoin(float $amountBitcoin)
    {

        if (!($this->getBitcoin() - $amountBitcoin < 0)) {
            $bitcoin = $this->PriceBitcoins() * $amountBitcoin;
            $this->setBalance($this->getBalance() + $bitcoin);
            $this->setBitcoin($this->getBitcoin() - $amountBitcoin);
            echo "<br>";
            echo "Has vendido " . $amountBitcoin . " bitcoins";
        } else {
            echo "<br>";
            echo "No tienes suficientes bitcoins para vender";
        }
    }

    /**
     * Get the value of bitcoin
     */
    public function getBitcoin()
    {
        return $this->bitcoin;
    }

    /**
     * Set the value of bitcoin
     *
     * @return  self
     */
    public function setBitcoin($bitcoin)
    {
        $this->bitcoin = $bitcoin;

        return $this;
    }
}
