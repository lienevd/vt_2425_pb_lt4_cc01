<?php

namespace Src\Middleware;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Http\Routing\Middleware;

class RequestMiddleware extends Middleware
{
    public function checkForApiKey(): Response
    {
        $response = new Response(ResponseTypeEnum::FORBIDDEN);

        if (isset($_POST['public_key']) && trim($_POST['public_key']) === trim(PUBLIC_KEY)) {
            $response->setResponseType(ResponseTypeEnum::OK);
            return $response;
        }

        $response->error('Invalid API key given.');
        return $response;
    }
}