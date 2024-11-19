<?php

namespace ComBank\Bank\Person;

use ComBank\Support\Traits\ApiTrait;

class Person
{
    use ApiTrait;

    private string $name;
    private string $idCard;
    private string $gmail;

    public function __construct(string $name, string $idCard, string $gmail)
    {
        $this->name = $name;
        $this->idCard = $idCard;
        if ($this->validateEmail($gmail)) {
            echo "Validating email: ". $gmail ."<br>";
            echo "Email is valid.";
            $this->gmail = $gmail;
        }else{
            echo "Validating email: ". $gmail ."<br>";
            echo "Error: Invalid email address: $gmail";
        }
    }

    /**
     * Get the value of gmail
     */ 
    public function getGmail()
    {
        return $this->gmail;
    }
}
