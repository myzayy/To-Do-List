<?php

namespace App\Core;

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            // Remove the root namespace
            $class = str_replace('App\\', '', $class);
            // Change \ to /
            $class = str_replace('\\', '/', $class);
            $path = __DIR__ . '/../' . $class . '.php';

            if (file_exists($path)) {
                require_once $path;
            }
        });
    }
}
