<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Driver
 *
 * @author Scott Morken <scott.morken@pcmail.maricopa.edu>
 */

namespace Wyzen\MssqlBundle\Driver\PDOOdbc;
use Wyzen\MssqlBundle\Platforms\DblibPlatform;
use Wyzen\MssqlBundle\Schema\DblibSchemaManager;

class Driver implements \Doctrine\DBAL\Driver
{
    /**
     * Attempts to establish a connection with the underlying driver.
     *
     * @param array $params
     * @param string $username
     * @param string $password
     * @param array $driverOptions
     * @return Doctrine\DBAL\Driver\Connection
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = array())
    {
        if (stristr(PHP_OS, 'WIN')) {
            $conn = new \Doctrine\DBAL\Driver\PDOConnection(
                $this->_constructPdoDsn($params),
                $username,
                $password,
                $driverOptions
            );
        } else {
            $conn = new Connection(
                $this->_constructPdoDsn($params),
                $username,
                $password,
                $driverOptions
            );
        }
        
        return $conn;
    }

    /**
     * Constructs the Dblib PDO DSN.
     *
     * @return string  The DSN.
     */
    private function _constructPdoDsn(array $params)
    {
        if (stristr(PHP_OS, 'WIN') && PHP_OS != 'Darwin')
        {
            // use for testing on Win
            $dsn = 'sqlsrv:server=';

            if (isset($params['host'])) {
                $dsn .= $params['host'];
            }

            if (isset($params['port']) && !empty($params['port'])) {
                $dsn .= ',' . $params['port'];
            }

            if (isset($params['dbname'])) {
                $dsn .= ';Database=' .  $params['dbname'];
            }
            return $dsn;

        } else {

            $dsn = 'odbc:Driver={FreeTDS};';
            if (isset($params['host'])) {
                $dsn .= 'server=' . $params['host'] . ($params['port'] ? ',' . $params['port'] : '') . ';';
            }
            if (isset($params['dbname'])) {
                $dsn .= 'Database=' . $params['dbname'] . ';';
            }
            $dsn .= "PROTOCOL=TCPIP;";
            return $dsn;
        }
    }

    public function getDatabasePlatform()
    {
        return new DblibPlatform();
    }

    public function getSchemaManager(\Doctrine\DBAL\Connection $conn)
    {
        return new DblibSchemaManager($conn);
    }

    public function getName()
    {
        return 'pdo_dblib';
    }

    public function getDatabase(\Doctrine\DBAL\Connection $conn)
    {
        $params = $conn->getParams();
        return $params['dbname'];
    }
}
