<?php

namespace Config;

use PDO;

class Database
{
    public static function connect(): PDO
    {
        return new PDO(
            'mysql:host=localhost;dbname=todo_list;charset=utf8mb4',
            'root',     // user
            '',         // heslo
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
}
