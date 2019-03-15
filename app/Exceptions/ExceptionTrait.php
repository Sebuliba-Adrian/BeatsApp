<?php
/**
 * Created by PhpStorm.
 * User: adrian
 * Date: 14/03/2019
 * Time: 02:25
 */

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionTrait
{

    /**
     * @param $request
     * @param $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiException($request, $exception)
    {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpResponseException) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        } else
            if ($exception instanceof MethodNotAllowedHttpException) {
                $status = Response::HTTP_METHOD_NOT_ALLOWED;
                $exception = new MethodNotAllowedHttpException([], 'HTTP_METHOD_NOT_ALLOWED', $exception);
            } elseif ($exception instanceof NotFoundHttpException) {
                $status = Response::HTTP_NOT_FOUND;
                $exception = new NotFoundHttpException('HTTP_BAD_URL', $exception);
            } elseif ($exception instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
                $exception = new NotFoundHttpException('HTTP_NOT_FOUND', $exception);
            } elseif ($exception instanceof AuthorizationException) {
                $status = Response::HTTP_FORBIDDEN;
                $exception = new AuthorizationException('HTTP_FORBIDDEN', $status);
            } elseif ($exception instanceof \Dotenv\Exception\ValidationException && $exception->getResponse()) {
                $status = Response::HTTP_BAD_REQUEST;
                $exception = new \Dotenv\Exception\ValidationException('HTTP_BAD_REQUEST', $status, $exception);
            } elseif ($exception) {
                $exception = new HttpException($status, 'HTTP_INTERNAL_SERVER_ERROR');
            }
        return response()->json([
            'success' => false,
            'status' => $status,
            'message' => $exception->getMessage()
        ], $status);
    }
}