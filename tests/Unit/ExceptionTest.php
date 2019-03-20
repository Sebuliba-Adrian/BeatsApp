<?php

namespace Tests\Unit;

use App\Exceptions\AuthorizationException;
use App\Exceptions\MethodNotAllowedHttpException;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\NotFoundHttpException;
use App\Exceptions\RandomException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\ExceptionTrait;
use App\Exceptions\HttpResponseException;

class ExceptionTraitWrapper
{
    use ExceptionTrait;
}

class MockResponse extends Response{

}
class ExceptionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_exceptions()
    {
        $exception = new ExceptionTraitWrapper();
        $response = json_decode($exception->apiException(null, new HttpResponseException(new MockResponse()))->getContent())->success;

        $this->assertEquals(false, $response);

        $response = json_decode($exception->apiException(null, new AuthorizationException())->getContent())->success;
        $this->assertEquals(false, $response);

        $response = json_decode($exception->apiException(null, new MethodNotAllowedHttpException([]))->getContent())->success;
        $this->assertEquals(false, $response);

        $response = json_decode($exception->apiException(null, new ModelNotFoundException())->getContent())->success;
        $this->assertEquals(false, $response);

        $response = json_decode($exception->apiException(null, new NotFoundHttpException())->getContent())->success;
        $this->assertEquals(false, $response);

        $response = json_decode($exception->apiException(null, new RandomException())->getContent())->success;
        $this->assertEquals(false, $response);


    }
}
