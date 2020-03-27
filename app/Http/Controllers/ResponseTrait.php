<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait ResponseTrait
{
    /**
     * Status code of response
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * Message of response
     *
     * @var string
     */
    protected $message = '';

    /**
     * Status of response
     *
     * @var boolean
     */
    protected $status = TRUE;

    /**
     * Getter for statusCode
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Setter for status
     *
     * @param boolean $status Value to set
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function respond()
    {

        return response()->json([
            'status'    => $this->status,
            'data'      => [],
            'message'   => $this->message
        ], $this->statusCode);
    }

    public function respondWithArray($data)
    {

        return response()->json([
            'status'    => $this->status,
            'data'      => $data,
            'message'   => $this->message
        ], $this->statusCode, [], JSON_NUMERIC_CHECK);
    }

    protected function respondWithObject($item, $callback)
    {
        return response()->json([
            'status'    => $this->status,
            'data'      => new $callback($item),
            'message'   => $this->message
        ], $this->statusCode, [], JSON_NUMERIC_CHECK);
    }

    protected function respondWithNativeObject($item)
    {
        return response()->json([
            'status'    => $this->status,
            'data'      => $item,
            'message'   => $this->message
        ], $this->statusCode, [], JSON_NUMERIC_CHECK);
    }

    /**
     * Return collection response from the application
     *
     * @param array|LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection $collection
     * @param \Closure|TransformerAbstract $callback
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection($collection, $callback)
    {
        $resource = ['data' => []];
        if($collection){
            $callback::collection($collection);
            $resource = $collection->toArray();
        }

        return response()->json([
            'status' => true,
            'data' => $resource['data'],
            'pagination' => Arr::except($resource, 'data')
        ], $this->statusCode, [], JSON_NUMERIC_CHECK);
    }

    protected function respondWithNativeCollection($collection, $callback)
    {
        $resource = [];
        if($collection){
            $new_item = $callback::collection($collection);
            $resource = $new_item->toArray($collection);
        }

        return response()->json([
            'status' => true,
            'data' => $resource,
        ], $this->statusCode, [], JSON_NUMERIC_CHECK);
    }

    protected function validationResponse($validatorResponse)
    {
        return $this->setStatus(FALSE)
            ->setStatusCode(400)
            ->setMessage('validation_error')
            ->respondWithArray($validatorResponse);
    }

    protected function emptyResponse()
    {
        return $this->setStatus(FALSE)
            ->setStatusCode(400)
            ->setMessage('empty_request')
            ->respond();
    }

    protected function notFoundResponse($data)
    {
        return $this->setStatus(FALSE)
            ->setStatusCode(404)
            ->setMessage('not_found')
            ->respondWithArray($data);
    }

    protected function deletedResponse()
    {
        return $this->setStatus(TRUE)
            ->setStatusCode(200)
            ->setMessage('deleted')
            ->respond();
    }
}
