<?php namespace ComBank\OverdraftStrategy;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:39 PM
 */

/**
 * @description: Grant 100.00 overdraft funds.
 * */
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface; 
class SilverOverdraft implements OverdraftInterface
{
    function isGrantOverdraftFunds ($float): bool {
        if ($float<- 100) {
            return false;
         }
         return true;       } 
    
       function getOverdraftFundsAmount (): float {
        return 100.0;
       } 
    
}
