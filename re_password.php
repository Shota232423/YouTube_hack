<?php
require('dbconnect.php');
session_start();

if (!$_SESSION) {
    $redirect_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';
    header('location:'.$redirect_url);
    exit();
}

$username = $_POST['username'];//ユーザーネーム取得
$now_password = $_POST['now_password'];//現在のパスワード取得
$new_password1 = $_POST['new_password1'];//新しいパスワード取得
$new_password2 = $_POST['new_password2'];//新しい再入力パスワード取得

$new_password1_word_count = mb_strlen(str_replace(array(" ", "　"), "", $new_password1));
$new_password2_word_count = mb_strlen(str_replace(array(" ", "　"), "", $new_password2));

//新しいパスワードが7文字以上か判定
if ($new_password1_word_count>=7&&$new_password2_word_count>=7) {
    $new_password_word_count=true;
} else {
    $new_password_word_count=false;
}
    

//現在のパスワードが一致しているかフラグ(判定)
$now_password_conf = $db->prepare('SELECT * FROM members WHERE username=? AND password=?');
$now_password_conf->execute(array($username,sha1($now_password)));
$now_password_conf_result = $now_password_conf->fetch();

$now_pass_flag="";
if ($now_password_conf_result) {
    $now_pass_flag=true;
} else {
    $now_pass_flag=false;
}


//新しいパスワードと新しい再入力パスワードが一致しているかフラグ&文字数カウント
$new_pass_flag="";
if ($new_password1==$new_password2&&$new_password_word_count) {
    $new_pass_flag=true;
} else {
    $new_pass_flag=false;
}

//上記の3つのフラグがtrueの時にデータベースの書き換えを行う
if ($now_pass_flag&&$new_pass_flag&&$new_password_word_count) {
    $change_sql = $db->prepare('UPDATE members SET password=? WHERE username=? AND password=?');
    $change_sql->execute(array(sha1($new_password1),$username,sha1($now_password)));
}

//ajax
$list= array();
$list[]=array("now_pass_flag"=>$now_pass_flag,//前のパスワードが一致しているか
"new_pass_flag"=>$new_pass_flag);//新しいパスワードが一致しているか
header('Content-type: application/json');
echo json_encode($list, JSON_UNESCAPED_UNICODE);