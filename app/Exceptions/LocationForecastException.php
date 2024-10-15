<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class LocationForecastException extends HttpException
{
    public function __construct(?string $message = null)
    {
        parent::__construct(500, $message);
    }
}
