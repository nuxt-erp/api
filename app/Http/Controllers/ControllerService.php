<?php

namespace App\Http\Controllers;

use App\Concerns\WithAllPolicies;
use App\Resources\ListResource;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ControllerService extends LaravelController implements ControllerInterface
{
    use ResponseTrait, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function count()
    {
        $count = $this->repository->modelCount();
        return $this->respondWithArray($count);
    }

    public function index(Request $request)
    {

        if($this instanceof WithAllPolicies){
            // call the police associated with this model
            $this->authorize('index', get_class($this->repository->model));
        }

        if($request->has('list')){
            // this request is to populate lists
            $itens = $this->repository->getList($request->except(['list']));
            return $this->respondWithNativeCollection($itens, ListResource::class);
        }
        else{
            $itens = $this->repository->findBy($request->all());
            if($this instanceof WithAllPolicies){
                $user = auth()->user();
                foreach ($itens as $item) {
                    $item->can_be_deleted = $user->can('destroy', $item);
                }
            }
            return $this->respondWithCollection($itens, $this->resource);
        }
    }

    public function show($id)
    {
        $item = $this->repository->findOne($id);
        if($this instanceof WithAllPolicies){
            $this->authorize('show', $item);
        }

        if ($item) {
            return $this->respondWithObject($item, $this->resource);
        } else {
            return $this->notFoundResponse(['id' => $id]);
        }
    }

    public function store(Request $request)
    {

        if($this instanceof WithAllPolicies){
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
        return $this->setStatusCode(201)->respondWithObject($this->repository->model, $this->resource);
    }

    public function update(Request $request, $id)
    {
        $item = $this->repository->findOne($id);

        if($this instanceof WithAllPolicies){
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
            return $this->respondWithObject($this->repository->model, $this->resource);
        } else {
            return $this->notFoundResponse(['id' => $id]);
        }
    }

    public function destroy($id)
    {

        $item = $this->repository->findOne($id);

        if($this instanceof WithAllPolicies){
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
