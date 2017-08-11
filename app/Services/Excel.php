<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Services;

use Maatwebsite\Excel\Excel as BaseExcel;

class Excel
{
	protected $excel;

	public function __construct(BaseExcel $excel)
	{
		$this->excel = $excel;
	}

	public function parse($file)
	{
		$result = '';
		$this->excel->selectSheetsByIndex(0)->load($file, function($reader) use (&$result){
			// $this->checkType($reader->ext);
			// 将excel转换成数组
			$result = $reader->get();
		});
		return $result;
	}

	private function checkType($fileType)
	{
		$validType = [
			'xlsx', 'xls'
		];

		if (! in_array($fileType, $validType)) {
			throw new \Exception('不是正确的excel文件');
		}
	}
}