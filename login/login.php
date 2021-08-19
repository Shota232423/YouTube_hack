<?php
require('../dbconnect.php');
session_start();

if (!empty($_POST)) {//$_POSTが空でない場合
    if ($_POST['username'] !='' && $_POST['password']!='') {//フォームのユーザーネームとパスワードが空じゃない場合
        $login = $db->prepare('SELECT * FROM members WHERE username=? AND password=?');
        $login->execute(array($_POST['username'],sha1($_POST['password'])));
        $member = $login->fetch();

        //ユーザー名とパスワードが空白だけ入力されるのを防ぐ
        /*
        $username_word_count = mb_strlen(str_replace(array(" ", "　"), "", $_POST['username']));
        $password_word_count = mb_strlen(str_replace(array(" ", "　"), "", $_POST['password']));*/


        if ($member) {//メンバーに値が入っていた場合=ログイン成功
            //ログイン成功したら、セッションに「id」と「username」、「time」を保存する
            $_SESSION['id']=$member['id'];
            $_SESSION['username']=$_POST['username'];
            $_SESSION['time']=time();
            //リダイレクト
            header('Location:../index.php');
            exit();
        } else {//ログイン失敗
            $error['login'] = 'failed';
        }
    } else {
        $error['login']='blank';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="../style/login.css">
</head>

<body>
    <div class="page_container">
        <div class="login_container">
            <p class="login_title">ログイン画面</p>
            <p>情報を入力してください</p>
            <form action="login.php" method="POST">
                <input class="name" name="username" type="text" placeholder="ユーザー名"><br>
                <input class="password" name="password" type="password" placeholder="パスワード"><br>
                <?php if (!empty($error)):?>
                <p style="color:red">ユーザー名かパスワードが違います</p>
                <?php endif;?>
                <div class="button_area_container">
                    <div class="link_area">
                        <a href="../join/index.php">アカウントを作成</a>
                    </div>
                    <div class="button_area">
                        <input class="button" type="submit" value="ログイン">
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>