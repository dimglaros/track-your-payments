<?php

namespace App\Exception;

use Exception;

class NonValidPaymentTypeException extends Exception
{
    protected $message = 'There is no payment type with this name.';
}
