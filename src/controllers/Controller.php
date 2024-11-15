<?php

namespace Src\Controllers;

use Src\Api\LibreConnection;
use Src\Traits\RSAEncryptionTrait;
use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;

final class Controller
{
    public function index(): Response
    {
        return view('index');
    }
}
