<?php

namespace App\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Resources\ListResource;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ControllerService extends LaravelController implements ControllerInterface
{
    use ResponseTrait, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if($user && empty(config('database.connections.tenant.schema'))){
                $company = DB::table('companies')->find($user->company_id);
                config(['database.connections.tenant.schema' => $company->schema]);
                DB::reconnect('tenant');
            }
            return $next($request);
        });
    }

    public function count()
    {
        $count = $this->repository->modelCount();
        return $this->sendArray($count);
    }

    public function index(Request $request)
    {
        $isList = $request->has('list') && $request->list;

        if($this instanceof CheckPolicies){
            // call the police associated with this model
            if($isList){
                $this->authorize('list', get_class($this->repository->model));
            }
            else{
                $this->authorize('index', get_class($this->repository->model));
            }
        }

        $items = $this->repository->findBy($request->all());
        if($isList){
            return $this->sendCollectionResponse($items, ListResource::class);
        }
        else{
            return $this->sendFullCollectionResponse($items, $this->resource);
        }
    }

    public function show($id)
    {
        $item = $this->repository->findOne($id);
        if($this instanceof CheckPolicies){
            $this->authorize('show', $item);
        }

        if ($item) {
            return $this->sendObjectResource($item, $this->resource);
        } else {
            return $this->notFoundResponse(['id' => $id]);
        }
    }

    public function store(Request $request)
    {

        if($this instanceof CheckPolicies){
            $this->authorize('store', get_class($this->repository->model));
        }

        // Validation
        $validatorResponse = $this->validateRequest($request);

        // Send failed response if empty request
        if (empty($request->all())) {
            if ($request->has('tx_path')) {
                Storage::delete($request->tx_path);
            }
            return $this->emptyResponse();
        }

        // Send failed response if validation fails and return array of errors
        if (!empty($validatorResponse)) {
            if ($request->has('tx_path')) {
                Storage::delete($request->tx_path);
            }
            return $this->validationResponse($validatorResponse);
        }

        $this->repository->store($request->all());
        return $this->setStatusCode(201)->sendObjectResource($this->repository->model, $this->resource);
    }

    public function update(Request $request, $id)
    {
        $item = $this->repository->findOne($id);

        if($this instanceof CheckPolicies){
            $this->authorize('update', $item);
        }

        // Validation
        $validatorResponse = $this->validateRequest($request, $item);

        // Send failed response if empty request
        if (empty($request->all())) {
            return $this->emptyResponse();
        }

        // Send failed response if validation fails and return array of errors
        if (!empty($validatorResponse)) {
            return $this->validationResponse($validatorResponse);
        }

        $this->repository->update($item, $request->all());
        if ($this->repository->model->exists) {
            return $this->sendObjectResource($this->repository->model, $this->resource);
        } else {
            return $this->notFoundResponse(['id' => $id]);
        }
    }

    public function destroy($id)
    {

        $item = $this->repository->findOne($id);

        if($this instanceof CheckPolicies){
            $this->authorize('destroy', $item);
        }

        $result = $this->repository->delete($item);

        if ($result) {
            return $this->deletedResponse();
        } else {
            return $this->notFoundResponse(['id' => $id]);
        }
    }

    protected function validateRequest(Request $request, $item = null, $rules = [])
    {
        //Perform Validation
        $validator = \Validator::make(
            $request->all(),
            count($rules) > 0 ? $rules : $this->repository->model->getRules($request, $item)
        );
        return $this->getValidationErrors($validator);
    }

    public function getValidationErrors($validator)
    {
        $result = [];
        if ($validator->fails()) {
            $errorTypes = $validator->failed();
            $errorValues = $validator->errors();
            // crete error message by using key and value
            foreach ($errorTypes as $key => $value) {
                $type = strtolower(array_keys($value)[0]);
                $result[$key] = strstr($type, 'illuminate') === FALSE ? $type : $errorValues->get($key)[0];
            }
        }

        return $result;
    }
}
