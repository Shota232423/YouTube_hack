<?php
require('dbconnect.php');
session_start();

if (!$_SESSION) {
    $redirect_url='https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';
    header('location:'.$redirect_url);
    exit();
}

$comment_id = $_POST['id']; //コメントid取得
$username = $_POST['username'];//いいねを押したユーザー名を取得

$serch =$db->prepare('SELECT *,count(*)AS count FROM good WHERE username=? AND comment_id=?');//過去に「いいね」を押したか確認する
$serch->execute(array($username,$comment_id));
$row = $serch->fetch(PDO::FETCH_ASSOC);

if ($row['count']<=0) {//過去に「いいね」を押していない場合
$statement = $db->prepare('INSERT INTO good SET username=?, comment_id=?');//idとusernameとcommentをデータベースに入れる
$ret = $statement->execute(array(
$username,
$comment_id));
} else {//過去に「いいね」を押していた場合
$statement = $db->prepare('DELETE FROM good WHERE username=? AND comment_id=?');//idとusernameとcommentをデータベースに入れる
$ret = $statement->execute(array(
$username,
$comment_id));
}

$count_goods =$db->prepare('SELECT COUNT(comment_id=? OR NULL) AS count FROM good');//過去に「いいね」を押したか確認する
$count_goods->execute(array($comment_id));
$count_good = $count_goods->fetch(PDO::FETCH_ASSOC);

$list= array();
$list[]=array("good_count"=>$count_good['count']);

header('Content-type: application/json');
echo json_encode($list, JSON_UNESCAPED_UNICODE);