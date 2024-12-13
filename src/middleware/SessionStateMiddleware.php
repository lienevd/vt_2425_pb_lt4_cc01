<?php

namespace Src\Middleware;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Http\Routing\Middleware;

class SessionStateMiddleware extends Middleware
{
    public function sessionState(): Response
    {
        return new Response(ResponseTypeEnum::OK);
    }
}
