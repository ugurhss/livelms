<?php

namespace App\Exceptions;

use Exception;

class AlreadyEnrolledException extends Exception
{
    protected $message = 'Bu kursa zaten kayıtlısınız.';
    protected $code = 409;
}
