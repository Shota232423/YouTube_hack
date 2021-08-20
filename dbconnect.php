<?php
try {
    $driver_options = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone='+09:00'"];
    $db = new PDO('mysql:dbname='.$_ENV['DB_NAME'].';host='.$_ENV['HOST_NAME'].';charset=utf8', $_ENV['USER_NAME'], $_ENV['PASSWORD'], $driver_options);
} catch (PDOException $e) {
    echo 'DB接続エラー' . $e->getMessage();
}