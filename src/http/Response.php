<?php

namespace Src\Http;

use Src\Enums\ResponseTypeEnum;

class Response
{
    public function __construct(
        private ResponseTypeEnum $response,
        private string $content = ''
    ) {}

    public function getResponse(): ResponseTypeEnum
    {
        return $this->response;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setResponseType(ResponseTypeEnum $responseType): self
    {
        $this->response = $responseType;

        return $this;
    }
}
