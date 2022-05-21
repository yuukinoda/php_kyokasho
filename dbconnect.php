<?php

error_reporting(E_COMPILE_ERROR & ~ E_RECOVERABLE_ERROR & ~ E_ERROR & ~ E_CORE_ERROR);

try {
    $db = new PDO('mysql:dbname=mini_bbs;host = 127.0.0.1; charset=utf8', 'root', '');
} catch (PDOException $e) {
    echo 'DB接続エラー:' . $e->getMessage();
}

?>

