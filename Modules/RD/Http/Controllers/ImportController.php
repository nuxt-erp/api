<?php

namespace Modules\RD\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Modules\RD\Imports\RecipesImport;
use stdClass;

class ImportController extends ControllerService
{

    public function recipesImport(Request $request){

        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new RecipesImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        if(!isset($import->rows) || $import->rows <= 0){
            $this->setStatus(FALSE);
            $this->setMessage('No rows processed');
        }
        return $this->sendObject($import);

    }

}
