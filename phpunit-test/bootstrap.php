<?php

abstract class PHPUnit_DbUnit_TestCase extends PHPUnit_Extensions_Database_TestCase
{
    protected static $connection;
    protected static $pdo;

    public function getConnection()
    {
        if (self::$connection == null) {
            $config = $this->getPDOConfig();
            self::$pdo = new PDO($config['dsn'], $config['username'], $config['passwd']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection = $this->createDefaultDBConnection(self::$pdo, $config['dbname']);
        }
        return self::$connection;
    }

    /**
     * return array(
     *     'dsn'      => '',
     *     'username' => '',
     *     'passwd'   => '',
     *     'dbname'   => ''
     * );
     */
    protected function getPDOConfig()
    {
        throw new Exception('you should override this method in a subclass');
    }
}

abstract class PHPUnit_DbUnit_Mysql_TestCase extends PHPUnit_DbUnit_TestCase
{
    protected static $mysqlHost     = '127.0.0.1';
    protected static $mysqlPort     = 3307;
    protected static $mysqlDbname   = 'our_phpunit_test';
    protected static $mysqlCharset  = 'utf8';
    protected static $mysqlUsername = 'root';
    protected static $mysqlPasswd   = '123456';

    protected function getPDOConfig()
    {
        return array(
            'dsn'      => sprintf(
                            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                            self::$mysqlHost,
                            self::$mysqlPort,
                            self::$mysqlDbname,
                            self::$mysqlCharset
                          ),
            'username' => self::$mysqlUsername,
            'passwd'   => self::$mysqlPasswd,
            'dbname'   => self::$mysqlDbname
        );
    }
}
