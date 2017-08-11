<?php
/**
 * Created by PhpStorm.
 * User: keal
 * Date: 2017/7/26
 * Time: 下午2:13
 */

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class OrderException extends Exception implements HttpExceptionInterface
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