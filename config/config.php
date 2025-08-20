<?php
class Config {
    public static function configDB() {
        return [
            'db' => [
                'host'     => 'localhost',
                'dbname'   => 'spa_once',
                'user'     => 'SPATermalOnce',
                'pass'     => 'SpaOnce#360@2000#PrivilyDany',
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

