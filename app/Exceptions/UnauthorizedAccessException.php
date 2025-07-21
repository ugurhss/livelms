<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedAccessException extends Exception
{
    protected $message = 'Bu işlemi yapmaya yetkiniz yok.';
    protected $code = 403;
}
