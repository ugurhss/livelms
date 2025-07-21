<?php

namespace App\Exceptions;

use Exception;

class CourseNotPublishedException extends Exception
{
    protected $message = 'Bu kurs henüz yayınlanmamış.';
    protected $code = 403;
}
