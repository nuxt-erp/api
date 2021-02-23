<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Modules\Inventory\Imports\AvailabilityImport;
use Modules\Inventory\Imports\ProductsImport;
use Modules\Inventory\Imports\BinsImport;
use Modules\Inventory\Imports\StockAdjustmentImport;
use Modules\Inventory\Imports\StockCountImport;
use Modules\Inventory\Imports\TransferImport;
use stdClass;
use Modules\Inventory\Transformers\ProductResource;

class ImportController extends ControllerService
{

    public function productsImport(Request $request)
    {

        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new ProductsImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        if (!isset($import->rows) || $import->rows <= 0) {
            $this->setStatus(FALSE);
            $this->setMessage('No rows processed');
        }
        return $this->sendObject($import);
    }

    public function availabilityImport(Request $request)
    {

        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new AvailabilityImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        if (!isset($import->rows) || $import->rows <= 0) {
            $this->setStatus(FALSE);
            $this->setMessage('No rows processed');
        }
        return $this->sendObject($import);
    }

    public function binsImport(Request $request)
    {

        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new BinsImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        if (!isset($import->rows) || $import->rows <= 0) {
            $this->setStatus(FALSE);
            $this->setMessage('No rows processed');
        }
        return $this->sendObject($import);
    }

    public function xlsInsertStock(Request $request)
    {

        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new StockCountImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        if (!isset($import->rows) || $import->rows < 1) {
            $this->setStatus(FALSE);
            $this->setMessage('No rows processed');
        }
        return $this->sendObject($import);
    }

    public function xlsInsertTransfer(Request $request)
    {

        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new TransferImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        if (!isset($import->rows) || $import->rows < 1) {
            $this->setStatus(FALSE);
            $this->setMessage('No rows processed');
        }
        return $this->sendObject($import);
    }

    public function xlsAdjustStock(Request $request)
    {
        $import = new stdClass;
        if ($request->hasFile('excel') && $request->file('excel')->isValid()) {
            $path = $request->excel->store('imports');
            $import = new StockAdjustmentImport;
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        if (!isset($import->rows) || $import->rows < 1) {
            $this->setStatus(FALSE);
            $this->setMessage('No rows processed');
        }
        return $this->sendObject($import);
    }

    public function syncProduct($sku = null)
    {
        $api = resolve('Dear\API');
        $product = $api->syncProds($sku);
        return $this->sendObjectResource($product, ProductResource::class);
    }

    public function dearSyncSuppliers()
    {
        $api = resolve('Dear\API');
        $result = $api->syncSuppliers();

        return $this->sendArray([
            'errors'    => [],
            'rows'      => $result
        ]);
    }

    public function dearSyncCategories()
    {
        $api = resolve('Dear\API');
        $result = $api->syncCategories();

        return $this->sendArray([
            'errors'    => [],
            'rows'      => $result
        ]);
    }

    public function dearSyncLocations()
    {

        $api = resolve('Dear\API');
        $result = $api->syncLocations();

        return $this->sendArray([
            'errors'    => [],
            'rows'      => $result
        ]);
    }

    public function dearSyncAvailabilities()
    {

        $api = resolve('Dear\API');
        $result = $api->syncAvailability();

        return $this->sendArray([
            'errors'    => [],
            'rows'      => $result
        ]);
    }

    public function dearSyncBrands()
    {
        $api = resolve('Dear\API');
        $result = $api->syncBrands();

        return $this->sendArray([
            'errors'    => [],
            'rows'      => $result
        ]);
    }

    public function dearSyncProducts()
    {
        $api = resolve('Dear\API');
        $result = $api->syncProds();

        return $this->sendArray([
            'errors'    => [],
            'rows'      => $result
        ]);
    }
}
