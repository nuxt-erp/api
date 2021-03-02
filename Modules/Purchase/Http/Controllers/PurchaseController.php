<?php

namespace Modules\Purchase\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Purchase\Repositories\PurchaseRepository;
use Modules\Purchase\Transformers\PurchaseResource;

class PurchaseController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(PurchaseRepository $repository, PurchaseResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function getNextPONumber()
    {
        $next_po_number = $this->repository->getNextPONumber();       
        return $this->setStatus(true)->sendArray([ 'po_number' => 'PO-' . $next_po_number ]);
    }

    public function checkPoNumber($po_number)
    {
        $purchase = $this->repository->checkPoNumber($po_number);

        if ($purchase) {
            $next_po_number = $this->repository->getNextPONumber();       
            return $this->setStatus(false)->sendArray([ 'po_number' => 'PO-' . $next_po_number ]);
        } else {
            return $this->setStatus(true)->send();
        }
    }

    public function clone($id)
    {
        $cloned = $this->repository->clone($id);
        return $this->sendArray($cloned);
    }

    public function getStatuses()
    {
        $result = $this->repository->getPurchaseStatuses();
        return $this->sendArray($result);
    }

}
