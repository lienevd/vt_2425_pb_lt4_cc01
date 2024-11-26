<?php

namespace Tests\Http;

use PHPUnit\Framework\TestCase;
use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;

class ResponseTests extends TestCase
{
    private Response $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new Response(ResponseTypeEnum::OK);
    }

    public function testResponseType(): void
    {
        $this->assertInstanceOf(ResponseTypeEnum::class, $this->response->getResponse());
        $this->assertEquals(ResponseTypeEnum::OK, $this->response->getResponse());

        $this->response->setResponseType(ResponseTypeEnum::BAD_REQUEST);
        $this->assertEquals(ResponseTypeEnum::BAD_REQUEST, $this->response->getResponse());
    }
}
