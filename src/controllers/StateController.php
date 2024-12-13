<?php

namespace Src\Controllers;

use Src\Enums\ResponseTypeEnum;
use Src\Http\Response;
use Src\Session\StateSession;

class StateController
{
    public function index(string $name): Response
    {
        try {
            return new Response(ResponseTypeEnum::OK, StateSession::getPage($name));
        } catch (\Exception $e) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, $e->getMessage());
        }
    }

    public function save(): Response
    {
        if (!isset($_POST['name']) || !isset($_POST['html'])) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'error trying to save state one of the following post vars not active: <name>, <html>');
        }

        $name = $_POST['name'];
        $html = $_POST['html'];

        if (empty($name)) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'error trying to save state, <name> cannot be empty');
        }

        if (empty($name)) {
            return new Response(ResponseTypeEnum::BAD_REQUEST, 'error trying to save state, <html> cannot be empty');
        }

        StateSession::addPage($name, $html);

        return new Response(ResponseTypeEnum::OK);
    }

    public function check(string $name): Response
    {
        try {
            StateSession::getPage($name);
            return new Response(ResponseTypeEnum::OK, json_encode(['exists' => true]));
        } catch (\Exception $e) {
            return new Response(ResponseTypeEnum::OK, json_encode(['exists' => false]));
        }
    }

    public function delete(string $name): Response
    {
        StateSession::removePage($name);

        return new Response(ResponseTypeEnum::OK);
    }

    public function test(): Response
    {
        return view('test-state');
    }
}
