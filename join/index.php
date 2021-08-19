<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="../style/join.css">
</head>

<body>

    <?php
require('../dbconnect.php');
session_start();

if (!empty($_POST)) {//$_POSTが空でない場合
    //ユーザー名とパスワードが空白だけ入力されるのを防ぐ！
    //半角スペース、全角スペースを無くした際に、文字数が0だった場合はエラーとなる！
    $username_word_count = mb_strlen(str_replace(array(" ", "　"), "", $_POST['username']));
    $password_word_count = mb_strlen(str_replace(array(" ", "　"), "", $_POST['password']));
    
    if ($username_word_count==0||$password_word_count==0) {//ユーザー名または、パスワードに文字数0があった場合、$error配列にemptyを代入！
        $error['empty']='empty';
    }
    if ($username_word_count>0 && $password_word_count>0) {//両方文字数が0以上で、パスワードの文字数が　7より少なかった場合、$error配列にerrorを代入!
        if (mb_strlen($_POST['password'])<7) {
            $error['password_count']='error';
        }
    }
    
    //重複アカウントチェック
    $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE username=?');
    $member->execute(array($_POST['username']));
    $record = $member->fetch();
    //データベースに同じusernameがあった場合、cntが１となる！
    //cntが0より多かった場合、$error配列にduplicateを代入!
    if ($record['cnt'] > 0) {
        $error['username']='duplicate';
    }


    if (empty($error)) {
        //画像ファイルの処理
        $_SESSION['join'] = $_POST;
        header('Location:check.php');
        exit();//プログラムを終了
    }
}
//書き直し
if ($_REQUEST['action']=='rewrite') {
    $_POST = $_SESSION['join'];
    $error['rewrite']=true;
}
?>

    <div class="container2">
        <div class="container">
            <p class="join_title">YouTube Hackアカウント登録</p>

            <form action="" method="post" enctype="multipart/form-data">
                <input class="name" name="username" type="text" placeholder="ユーザー名"><br>
                <input class="password" name="password" type="password" placeholder="パスワード"><br>
                <?php if ($error['empty']=='empty'): ?>
                <p style="color:red">ユーザー名とパスワードは必ず入力してください。</p>
                <?php endif; ?>
                <?php if ($error['username']=='duplicate'):?>
                <p style="color:red">指定されたユーザー名はすでに登録されています。</p>
                <?php endif; ?>
                <?php if ($error['password_count']=='error'):?>
                <p style="color:red">パスワードは7文字以上で設定してください。</p>
                <?php endif; ?>
                <div class="container3">
                    <div class="link_area">
                        <a href="../login/login.php">ログインページへ</a>
                    </div>
                    <div class="button_area">
                        <input class="button" type="submit" value="登録">
                    </div>
                </div>
            </form>

        </div>
    </div>
</body>

</html>