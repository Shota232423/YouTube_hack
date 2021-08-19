<?php
session_start();
require('../dbconnect.php');

if (!empty($_POST)) {//$_POSTが空でない場合
    //データベース登録処理
    $statement = $db->prepare('INSERT INTO members SET username=?, password=?, picture=?, created=NOW()');
    echo $ret = $statement->execute(array(
        $_SESSION['join']['username'],
        sha1($_SESSION['join']['password']),$_POST['select_img']));

    

    header('Location:thanks.php');//リダイレクト
    exit();//プログラムを終了
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チェック&画像選択</title>
    <link rel="stylesheet" href="../style/check.css">
</head>

<body>
    <form action="" method="post">
        <input type="hidden" name="action" value="submit">
        <h3>ユーザーネーム</h3>
        <?php echo htmlspecialchars($_SESSION['join']['username'], ENT_QUOTES); ?>
        <h3>パスワード</h3>
        <p>表示しません</p>
        <h3>ユーザー画像を選択してください</h3>
        <!--画像選択ラジオボタン設置予定-->
        <div class="select_container">
            <div class="select_object">
                <img src="../member_picture/1.jpeg" alt="">
                <input type="radio" name="select_img" value="1" checked>
            </div>
            <div class="select_object">
                <img src="../member_picture/2.jpeg" alt="">
                <input type="radio" name="select_img" value="2">
            </div>
            <div class="select_object">
                <img src="../member_picture/3.jpeg" alt="">
                <input type="radio" name="select_img" value="3">
            </div>
            <div class="select_object">
                <img src="../member_picture/4.jpeg" alt="">
                <input type="radio" name="select_img" value="4">
            </div>
            <div class="select_object">
                <img src="../member_picture/5.jpeg" alt="">
                <input type="radio" name="select_img" value="5">
            </div>
            <div class="select_object">
                <img src="../member_picture/6.jpeg" alt="">
                <input type="radio" name="select_img" value="6">
            </div>
        </div>
        <div class="button_area">
            <input class="button" type="submit" value="登録する">
        </div>
    </form>
</body>

</html>