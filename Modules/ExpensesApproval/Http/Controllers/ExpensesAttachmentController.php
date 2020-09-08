<?php

namespace Modules\ExpensesApproval\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Modules\ExpensesApproval\Repositories\ExpensesAttachmentRepository;
use Modules\ExpensesApproval\Transformers\ExpensesAttachmentResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

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
            $file->storeAs('attachments', $fileName, ['disk' => 's3']);

            return $this->setStatus(true)->sendObject($fileName);
        }
    }    

    public function deleteFile($file_name)
    {                  
        $filePath = 'attachments/' . $file_name;
        Storage::disk('s3')->delete($filePath);

        $this->repository->deleteFile($file_name);

        return $this->setStatus(true)->sendObject(['file_name' => $file_name, 'hide' => true]);
    }

    public function downloadFile($file_name)
    {           
        $filePath = 'attachments/' . $file_name;        
        return Storage::disk('s3')->download($filePath);
    }
}
