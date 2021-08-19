<?php
require('dbconnect.php');
session_start();

if (!$_SESSION) {
    $redirect_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';
    header('location:'.$redirect_url);
    exit();
}
?>

<?php
if (!empty($_POST)) {
    //データベース登録処理
    $statement = $db->prepare('INSERT INTO thread_list SET title=?, youtube_url=?,thumbnail_url=?, creater=?');
    echo $ret = $statement->execute(array(
        htmlspecialchars($_POST['title']),
        htmlspecialchars($_POST['url']),
        htmlspecialchars($_POST['thumbnail']),
        $_SESSION['username']
       ));

    header('Location:comment.php?url='.$_POST['url']);
    exit();
}

?>