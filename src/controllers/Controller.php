<?php

namespace Src\Controllers;

use Src\Http\Response;

final class Controller
{
    public function index(): Response
    {
        return view('index');
    }
}
