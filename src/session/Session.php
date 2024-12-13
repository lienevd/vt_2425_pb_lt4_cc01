<?php

namespace Src\Session;

class Session
{
    /**
     * Starts the session if it is not already started.
     */
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Regenerates the session ID if 30 minutes have passed.
     */
    public static function regenerateIfExpired(): void
    {
        self::start();

        if (!isset($_SESSION['last_regenerated'])) {
            $_SESSION['last_regenerated'] = time();
        }

        if (time() - $_SESSION['last_regenerated'] > 1800) { // 1800 seconds = 30 minutes
            session_regenerate_id(true);
            $_SESSION['last_regenerated'] = time();
        }
    }

    /**
     * Adds a value to the session.
     *
     * @param string $key
     * @param mixed $value
     */
    public static function add(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieves a value from the session.
     *
     * @param string $key
     * @param mixed|null $default The default value to return if the key doesn't exist.
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Checks if a key exists in the session.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Removes a value from the session.
     *
     * @param string $key
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Clears all session data.
     */
    public static function clear(): void
    {
        self::start();
        $_SESSION = [];
    }

    /**
     * Destroys the session entirely.
     */
    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
}
