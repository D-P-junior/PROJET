<?php

class DNS {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null){
            try{
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=my_shop', 'root', '',
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            } catch (PDOException $e){
                echo $e->getMessage();
            }
        }
        return self::$instance;
    }
}