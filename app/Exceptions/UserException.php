<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class UserException extends Exception implements HttpExceptionInterface
{
	public function getStatusCode(){
		return 400;
	}

	/**
	 * Returns response headers.
	 *
	 * @return array Response headers
	 */
	public function getHeaders(){
		return [

		];
	}
}