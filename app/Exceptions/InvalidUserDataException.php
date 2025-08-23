<?php

namespace App\Exceptions;

use Exception;

class InvalidUserDataException extends Exception
{
    protected $message = 'Dados inválidos';
    protected $code = 400;
}
