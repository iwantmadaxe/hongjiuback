<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderPackage;

class OrderRepository extends BaseRepository
{
	const STATUS = [
		'unpaid' => 1,
		'paid' => 2,
	];

	public function __construct(Order $order)
	{
		$this->model = $order;
	}

	public function getRecordByUser($userId)
	{
		return OrderPackage::where('user_id', $userId)->get();
	}
}