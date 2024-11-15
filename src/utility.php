<?php

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;

/**
 * Returns a response with a specific view as its contents
 * @param string $path
 * @return Src\Http\Response
 */
function view(string $path, array $params = []): Response
{
    $viewDir = __DIR__ . '/views/' . $path . '.php';

    if (!file_exists($viewDir)) {
        return new Response(ResponseTypeEnum::NOT_FOUND, "View: $path not found in" . __DIR__ . "/views");
    }

    // Array word veranderd in php params
    extract($params);

    // View word in de output buffer gezet en daarna toegevoegd aan de response content
    ob_start();
    require_once $viewDir;
    $view = ob_get_clean();
    ob_end_flush();

    return new Response(ResponseTypeEnum::OK, $view);
}
