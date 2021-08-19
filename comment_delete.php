<?php
require('dbconnect.php');
session_start();

if (!$_SESSION) {
    $redirect_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';
    header('location:'.$redirect_url);
    exit();
}

$comment_id = $_POST['comment_id'];

$statement = $db->prepare('DELETE FROM comment WHERE comment_id=?');
$ret = $statement->execute(array($comment_id));