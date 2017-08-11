<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\API\V1;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected function response()
	{
		return app('output.response');
	}
}