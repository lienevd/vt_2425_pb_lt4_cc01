<?php

namespace Src\Session;

class StateSession
{
    public static function start(): void
    {
        Session::start();

        if (Session::has('state')) {
            return;
        }

        if (!Session::has('state')) {
            Session::add('state', ['pages' => []]);
        }
    }

    public static function add(string $key, mixed $value): void
    {
        self::start();

        $state = Session::get('state');
        $state[$key] = $value;
        Session::add('state', $state);
    }

    public static function addPage(string $name, string $html): void
    {
        self::start();

        $state = self::getState();
        $pages = $state['pages'];
        $pages[$name] = $html;
        self::add('pages', $pages);
    }

    public static function getPage(string $name): string
    {
        self::start();

        if (!isset(self::getState()['pages'][$name])) {
            throw new \Exception("The page: $name does not exist in the state session");
        }

        return self::getState()['pages'][$name];
    }

    public static function removePage(string $name): void
    {
        self::start();

        $state = self::getState();

        if (!isset($state['pages'][$name])) {
            throw new \Exception("The page: $name does not exist in the state session");
        }

        unset($state['pages'][$name]);

        self::add('pages', $state['pages']);
    }

    public static function getState(): array
    {
        self::start();

        return Session::get('state');
    }

    public static function get(string $key): mixed
    {
        self::start();

        $state = self::getState();
        if (!isset($state[$key])) {
            throw new \Exception("Key: $key does not exist in the state session");
        }

        return $state[$key];
    }

    public static function remove(string $key): void
    {
        self::start();

        $state = self::getState();
        if (!isset($state[$key])) {
            throw new \Exception("Key: $key does not exist in the state session");
        }

        unset($state[$key]);
        Session::add('state', $state);
    }
}
