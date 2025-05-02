<?php

class Database {

    private static string $hostname = 'localhost';
    private static string $database = 'ptaw';
    private static string $username = 'filipe';
    private static string $password = 'ptaw';

    private static ?PDO $connection = null;

    public static function connect(): void {

        if (self::$connection === null) {

            $dsn = 'mysql:host=' . self::$hostname . ';dbname=' . self::$database;

            self::$connection = new PDO($dsn, self::$username, self::$password);

            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }
    }

    public static function getConnection(): PDO {

        if (self::$connection === null) {

            throw new Exception("Database connection not established.");

        }

        return self::$connection;
    }
}
