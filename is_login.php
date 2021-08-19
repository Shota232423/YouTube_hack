<?php
session_start();
$redirect_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';

$to_data[]=array(
    'redirect_url'=>$redirect_url
);
//jsonとして出力
header('Content-type: application/json');
echo json_encode($to_data, JSON_UNESCAPED_UNICODE);