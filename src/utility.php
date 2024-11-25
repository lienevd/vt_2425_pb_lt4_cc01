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

    try {
        // Start output buffering
        ob_start();
        require_once $viewDir;
        $view = ob_get_clean(); // Automatically clears the buffer
    } catch (Throwable $e) {
        ob_end_clean(); // Clean buffer on error
        return new Response(
            ResponseTypeEnum::BAD_REQUEST,
            "Error rendering view: {$e->getMessage()}"
        );
    }
    
    return new Response(ResponseTypeEnum::OK, $view);
}
