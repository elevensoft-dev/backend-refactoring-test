<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $message = 'Usuário não encontrado';
    protected $code = 404;
}
