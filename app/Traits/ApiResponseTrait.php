<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponseTrait
{
    protected int $statusCode   = 200;
    protected string $message   = '';
    protected bool $error       = false;
    protected int $debugInfo    = 0;

    /**
     * @param $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function successResponse($data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json(
            data: ['data' => $data],
            status: $statusCode
        );
    }

    /**
     * @param string $errorMessage
     * @param int $statusCode
     * @return JsonResponse
     */
    public function errorResponse(string $errorMessage, int $statusCode): JsonResponse
    {
        return response()->json(
            data: [
                'error' => $errorMessage,
                'error_code' => $statusCode
            ],
            status: $statusCode
        );
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondWithError(string $message): JsonResponse
    {
        $this->error = true;
        $this->message = $message;

        return $this->respond([]);
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function respondCreated($data): JsonResponse
    {
        $this->statusCode = Response::HTTP_CREATED;
        return $this->respond(data: $data);
    }

    /**
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    public function respondWithMessage(array $data, string $message): JsonResponse
    {
        $this->statusCode   = Response::HTTP_OK;
        $this->message      = $message;

        return $this->respond(data: $data);
    }

    /**
     * @param array $data
     * @param int $statusCode
     * @param string $message
     * @return JsonResponse
     */
    public function respondValidationError(
        array $data,
        int $statusCode     = Response::HTTP_UNPROCESSABLE_ENTITY,
        string $message     = 'Validation error'
    ): JsonResponse
    {
        $this->error = true;
        $this->message = $message;
        $this->statusCode = $statusCode;

        return $this->respond(data: $data);
    }

    /**
     * @param Request $request
     * @param $items
     * @return LengthAwarePaginator
     */
    public function paginate(Request $request, $items): LengthAwarePaginator
    {
        $items  = $items['data'] ?? $items;
        $limit  = min((int)$request->get('limit', 10), 10);
        $page   = (int)$request->get('page', 1);
        $offset = ($page - 1) * $limit;

        return new LengthAwarePaginator(array_slice($items, $offset, $limit), count($items), $limit, $page);
    }

    /**
     * @param LengthAwarePaginator $lengthAwarePaginator
     * @param $resourceForMap
     * @return JsonResponse
     */
    public function respondPagination(LengthAwarePaginator $lengthAwarePaginator, $resourceForMap = null): JsonResponse
    {
        if (!is_null($resourceForMap)) {
            $items = $resourceForMap->collection($lengthAwarePaginator->items());
        } else {
            $items = $lengthAwarePaginator->items();
        }
        return $this->respond([
            'pagination' => $this->getPagination($lengthAwarePaginator),
            'items'      => $items,
        ]);
    }

    /**
     * @param LengthAwarePaginator $lengthAwarePaginator
     * @return array
     */
    public function getPagination(LengthAwarePaginator $lengthAwarePaginator): array
    {
        return [
            'total'         => $lengthAwarePaginator->total(),
            'current_page'  => $lengthAwarePaginator->currentPage,
            'last_page'     => $lengthAwarePaginator->lastPage(),
            'from'          => $lengthAwarePaginator->firstItem(),
            'to'            => $lengthAwarePaginator->lastItem()
        ];
    }

    /**
     * @param $data
     * @param $headers
     * @return JsonResponse
     */
    public function respond($data = [], $headers = []): JsonResponse
    {
        $meta = [
            'meta' => [
                'error' => $this->error,
                'message' => $this->message,
                'status_code' => $this->statusCode,
            ]
        ];

        if (empty($data) && !is_array($data)) {
            $data = array_merge($meta, ['response' => null]);
        }
        else
            $data = array_merge($meta, ['response' => $data]);

        return response()->json(
            data: $data,
            status: $this->statusCode,
            headers: $headers
        );
    }
}
