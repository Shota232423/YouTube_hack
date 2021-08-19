<?php
require('dbconnect.php');
session_start();

if (!$_SESSION) {
    $redirect_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';
    header('location:'.$redirect_url);
    exit();
}

$id =$_POST['id'];
$username =$_POST['username'];
$comment=htmlspecialchars($_POST['comment'], ENT_QUOTES);

$comment_word_count = mb_strlen(str_replace(array(" ", "　"), "", $_POST['comment']));
    
if ($comment_word_count==0) {//文字数0があったらcomment_proのプログラムを止める
    exit();
}

$statement = $db->prepare('INSERT INTO comment SET video_id=?, username=?, comment=?');
$ret = $statement->execute(array(
$id,
$username,
$comment));//idとusernameとcommentをデータベースに入れる

//video_idと一致するコメント取得
$comments = $db->prepare('SELECT * FROM comment,members WHERE comment.video_id=? AND comment.username=members.username ORDER BY comment.comment_id DESC');
$comments->execute(array($id));

//いいね数カウント
function good_count($count)
{
    global $db;
    $serch =$db->prepare('SELECT COUNT(comment_id=? OR NULL) AS count FROM good');//過去に「いいね」を押したか確認する
    $serch->execute(array($count));
    $row = $serch->fetch(PDO::FETCH_ASSOC);
    return $row['count'];
}

  //ログインしている人が過去に投稿に対していいねを押しているか確認する
  function session_conf($count)
  {
      global $db;
      $serch =$db->prepare('SELECT COUNT(comment_id=? AND username=? OR NULL) AS count FROM good');//過去に「いいね」を押したか確認する
      $serch->execute(array($count,$_SESSION['username']));
      $row = $serch->fetch(PDO::FETCH_ASSOC);
      if ($row['count']>0) {
          return true;
      } else {
          return false;
      }
  }

$memberList = array();
while ($row = $comments->fetch(PDO::FETCH_ASSOC)) {
    $memberList[]=array(
     'pic' =>$row['picture'],
     'username' =>$row['username'],
     'date' =>$row['date'],
     'comment' =>$row['comment'],
     'comment_id'=>$row['comment_id'],
     'good_count'=>good_count($row['comment_id']),
     'good_conf'=>session_conf($row['comment_id'])

 );
}

//jsonとして出力
header('Content-type: application/json');
echo json_encode($memberList, JSON_UNESCAPED_UNICODE);