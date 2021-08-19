<?php
try {
    $db = new PDO('mysql:dbname='.$_ENV['DB_NAME'].';host='.$_ENV['HOST_NAME'].';charset=utf8', $_ENV['USER_NAME'], $_ENV['PASSWORD']);
} catch (PDOException $e) {
    echo 'DB接続エラー' . $e->getMessage();
}