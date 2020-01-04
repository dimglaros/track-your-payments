<?php

namespace App\Exception;

use Exception;

class NonValidUserException extends Exception
{
    protected $message = 'There is no valid user for this api key.';
}
