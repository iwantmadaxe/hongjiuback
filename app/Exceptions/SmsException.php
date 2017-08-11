<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class SmsException extends Exception implements HttpExceptionInterface
{
    public function getStatusCode()
    {
        return 400;
    }

    public function getHeaders()
    {
        return [];
    }
}