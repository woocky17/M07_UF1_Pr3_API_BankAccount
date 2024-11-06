<?php

namespace ComBank\OverdraftStrategy;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 12:27 PM
 */

use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;

class NoOverdraft implements OverdraftInterface
{
   function isGrantOverdraftFunds($float): bool
   {
      if ($float<- 0) {
         return false;
      }
      return true;
   }

   function getOverdraftFundsAmount(): float
   {
      return 0.0;
   }
}
