<?php 

class Db {
        
        private static $db;
    
        public static function getInstance(){
    
            if(self::$db !== null){
                // Connection already established, reuse it
                return self::$db;
                
            } else {
                // Create a new connection
                $dsn = 'mysql:host=localhost;dbname=promptswap';
                $username = 'evi';
                $password = '12345';
    
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
    
                self::$db = new PDO($dsn, $username, $password, $options);
                return self::$db;
            }
        }
    }