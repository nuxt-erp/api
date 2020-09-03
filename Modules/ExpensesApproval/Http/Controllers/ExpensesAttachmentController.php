<?php

namespace Modules\ExpensesApproval\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Modules\ExpensesApproval\Repositories\ExpensesAttachmentRepository;
use Modules\ExpensesApproval\Transformers\ExpensesAttachmentResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ExpensesAttachmentController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(ExpensesAttachmentRepository $repository, ExpensesAttachmentResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function saveFile(Request $request)
    {                   
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = 'expense-approval/attachments/' . $fileName;
            // Storage::disk('s3')->put($filePath, file_get_contents($file));

            return $this->setStatus(true)->sendObject($fileName);
        }
    }

    public function deleteFile($file_name)
    {                  
        $filePath = 'expense-approval/attachments/' . $file_name;
        // Storage::disk('s3')->delete($filePath);

        return $this->setStatus(true)->sendObject('File deleted');
    }
}
