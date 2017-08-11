<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1\Controllers\Auth\Credentials;


abstract class AbstractCredential
{
	abstract public function checkUserExist(array $credential);

	abstract public function login(array $credential);
}