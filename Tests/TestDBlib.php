<?php

namespace Wyzen\MssqlBundle\Tests;

require_once(__DIR__ . "/../vendor/autoload.php");

use Wyzen\MssqlBundle\Driver\PDODblib\Driver;

if (count($argv) != 6) {
  echo "Usage TestDBlib.php <host> <port> <database> <username> <password>";
  die;
}

// Test DB lib connection
$driver = new Driver();

try {
  $connection = $driver->connect([
    "host" => $argv[1],
    "port" => $argv[2],
    "dbname" => $argv[3]
  ], $argv[4], $argv[5]);
} catch (\Exception $exception) {
  echo "ERROR:: Test Failed!";
  echo "\n";
  echo $exception->getMessage();
  die();
}

echo "--------\nINFO:: Test Passed!\n--------\n";
