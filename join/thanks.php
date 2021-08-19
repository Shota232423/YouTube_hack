<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons%7CMaterial+Icons+Outlined" rel="stylesheet">
    <link href="../style/thanks.css" rel="stylesheet">
    <title>thanks!!!</title>
</head>
<?php
if (!empty($_SESSION['join'])) {
    unset($_SESSION['join']);
} else {
    header('location:../login/login.php');
    exit();
}
?>

<body>
    <div id="ex_container">
        <div id="ex2_container">
            <div id="container">

                <span class="material-icons-outlined">
                    fastfood
                </span>
                <p>登録ありがとうございます！！！</p><br>

            </div>

            <div id="link_area">
                <a href="../login/login.php">ログイン画面へ</a>
            </div>
        </div>
    </div>

</body>

</html>