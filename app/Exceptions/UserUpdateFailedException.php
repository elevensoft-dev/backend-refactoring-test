<?php

namespace App\Exceptions;

use Exception;

class UserUpdateFailedException extends Exception
{
    protected $message = 'Erro ao atualizar usuário';
    protected $code = 500;
}
