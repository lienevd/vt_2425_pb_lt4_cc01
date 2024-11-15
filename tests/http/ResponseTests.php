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

    public function testJsonArray(): void
    {
        $this->assertIsArray($this->response->getJsonArray());
        $this->assertEmpty($this->response->getJsonArray());

        $this->response->setJsonArray(['test' => 'test']);
        $this->assertNotEmpty($this->response->getJsonArray());
        $this->assertArrayHasKey('test', $this->response->getJsonArray());
        $this->assertEquals('test', $this->response->getJsonArray()['test']);
    }

    public function testError(): void
    {
        $this->assertNotContains('error', $this->response->getJsonArray());

        $this->response->error('test');
        $this->assertArrayHasKey('error', $this->response->getJsonArray());
        $this->assertIsString($this->response->getJsonArray()['error']);
        $this->assertEquals('test', $this->response->getJsonArray()['error']);

        $this->response->error(['test' => 'test']);
        $this->assertIsArray($this->response->getJsonArray()['error']);
        $this->assertEquals('test', $this->response->getJsonArray()['error']['test']);
    }
}