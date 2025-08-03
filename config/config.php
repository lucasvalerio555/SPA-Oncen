<?php
class Config {
    public static function configDB() {
        return [
            'db' => [
                'host'     => 'localhost',
                'dbname'   => 'spa_once',
                'user'     => 'root',
                'pass'     => '',
                'charset'  => 'utf8',
            ],
            'options' => [
                'debug'    => true,
                'logFile'  => 'db_errors.log',
            ]
        ];
    }
}
?>

