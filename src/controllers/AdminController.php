<?php

namespace Src\Controllers;

use Src\Http\Response;

final class AdminController
{
    public function index(): Response
    {
        return view('admin/index');
    }
}