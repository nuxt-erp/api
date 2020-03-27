<?php

namespace App\Http\Controllers\Productions;

use App\Concerns\WithAllPolicies;
use App\Http\Controllers\ControllerService;
use App\Models\ProductionOrder;
use App\Repositories\ProductionOrderRepository;
use App\Resources\PhaseResultResource;
use App\Resources\PObyRolesResource;
use App\Resources\POResultsResource;
use App\Resources\ProductionOrderResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ProductionOrderController extends ControllerService implements WithAllPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProductionOrderRepository $repository, ProductionOrderResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function store(Request $request)
    {
        //@todo important validations
        /*
        1 - Machine mandatory
        2 - Machine need to have flow
        3 - Flow need first phase
        4 - Phase need to have operation
        */
        return parent::store($request);
    }

    public function merge(Request $request){
        $this->repository->merge($request->all());

        if ($this->repository->model->exists) {
            return $this->setStatusCode(201)->respondWithObject($this->repository->model, $this->resource);
        } else {
            return $this->notFoundResponse([]);
        }
    }

    public function getByRoles(Request $request){

        // this request is to populate lists
        $itens = $this->repository->getByRoles($request->all());
        return $this->respondWithNativeCollection($itens, PObyRolesResource::class);
    }

    public function getOperationResults(Request $request){

        // this request is to populate lists
        $itens = $this->repository->getOperationResults($request->all());
        return $this->respondWithNativeCollection($itens, PObyRolesResource::class);
    }

    public function getPhaseResults(Request $request){
        $itens = $this->repository->getPhaseResults($request->all());
        return $this->respondWithNativeCollection($itens, PhaseResultResource::class);
    }

    public function processAction(Request $request){

        $rules = [
            'flow_action_id'        => ['required', 'exists:flow_actions,id'],
            'production_order_id'   => ['required', 'exists:production_orders,id'],

            'first_operator_id'     => ['nullable', 'exists:employees,id'],
            'second_operator_id'    => ['nullable', 'exists:employees,id'],
            'reason_type_id'        => ['nullable', 'exists:parameters,id'],
            'handled_qty'           => ['nullable', 'integer'],
            'to_handle_qty'         => ['nullable', 'integer'],
            'process_code'          => ['nullable'],
            'comment'               => ['nullable']
        ];
        $validatorResponse = $this->validateRequest($request, NULL, $rules);

        //@todo this flow_action_id must be owned by the flow on the machine from the po

        if (!empty($validatorResponse)) {
            return $this->validationResponse($validatorResponse);
        }

        $this->repository->processAction($request->only(array_keys($rules)));

        $production_order = ProductionOrder::find($request->input('production_order_id'));

        $user = auth()->user();
        $phases = $user->getAuthorizedPhases();
        if(!in_array($production_order->phase_id, array_column($phases, 'id'))){
            return $this->respondWithArray(['id' => $production_order->id, 'hide' => true]);
        }

        return $this->respondWithObject($production_order, PObyRolesResource::class);


    }
}
