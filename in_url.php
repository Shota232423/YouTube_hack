<?php
require('dbconnect.php');
session_start();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="style/in_url.css">
</head>

<body>
    <?php
  
  if (isset($_SESSION['id'])&&$_SESSION['time']+3600>time()) {
      //ログイン条件を満たしている
      $_SESSION['time'] = time();

      $members = $db->prepare('SELECT * FROM members WHERE id=?');
      $members->execute(array($_SESSION['id']));
      $member = $members->fetch();
  } else {
      //ログインしていない場合、ログイン画面にリダイレクトさせる
      $redirect_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';
      header('location:'.$redirect_url);
      exit();
  }

  ?>
    <div class="a">
        <div class="container">
            <p class="in_url_title">コメント欄を作る動画を<br>設定 or 検索</p>
            <form method="GET" action="create.php">
                <div>
                    <input type="search" id="q" name="q" placeholder="YouTubeのurlを入力してください。">
                </div>
                <div class="button_right">
                    <input type="submit" value="作成する" class="button_blue">
                </div>
            </form>
        </div>

    </div>
</body>

</html>