<?php
require('dbconnect.php');
session_start();

if (!$_SESSION) {
    $redirect_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';
    header('location:'.$redirect_url);
    exit();
}

$thread_id = $_POST['thread_id'];

$statement = $db->prepare('DELETE thread_list,comment FROM thread_list LEFT JOIN comment ON thread_list.id=comment.video_id WHERE thread_list.id=?');
$ret = $statement->execute(array($thread_id));