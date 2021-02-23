<?php

namespace Modules\Purchase\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Modules\Purchase\Imports\PurchaseImport;
use stdClass;

class ImportController extends ControllerService
{

    public function xlsInsertPurchase(Request $request)
    {

        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new PurchaseImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        if(!isset($import->rows) || $import->rows < 1){
            $this->setStatus(FALSE);
            $this->setMessage('No rows processed');
        }
        return $this->sendObject($import);
    }
   
}
