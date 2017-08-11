<?php
/*
 * Sometime too hot the eye of heaven shines
 */

namespace App\Http\Controllers\Admin;

use App\API\V1\BaseController;
use App\Models\File;
use App\Services\Excel;
use Illuminate\Http\Request;

class FileController extends BaseController
{
	private $excel;
	private $fileModel;

	public function __construct(Excel $excel, File $file)
	{
		$this->excel = $excel;
		$this->fileModel = $file;
	}

	public function upload(Request $request)
	{
		$filename = $request->file('file')->getClientOriginalName();
		$ext = $request->file('file')->getClientOriginalExtension();
		$path = $request->file('file')->store('files');

        $validType = [
            'xlsx', 'xls'
        ];

        if (! in_array($ext, $validType)) {
            throw new \Exception('不是正确的excel文件');
        }

		$this->fileModel->filename = $filename;
		$this->fileModel->path = $path;
		if ($file = $this->fileModel->save()) {
			return $this->response()->array(['data' => ['id' => $this->fileModel->id]]);
		}
	}
}