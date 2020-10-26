<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\ContactRepository;
use App\Resources\ContactResource;

class ContactController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ContactRepository $repository, ContactResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
