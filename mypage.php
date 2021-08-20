<?php
require('dbconnect.php');
session_start();

if (!$_SESSION['username']&&!$_GET['username']) {
    $redirect_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';
    header('location:'.$redirect_url);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mypage</title>
    <link rel="stylesheet" href="style/header.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="style/mypage.css">
    <link rel="stylesheet" href="style/header.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<?php //セッションの人がいいねして「いた」ときの処理
function session_conf($count2)
{
    global $db;
    $serch =$db->prepare('SELECT COUNT(comment_id=? AND username=? OR NULL) AS count FROM good');//過去に「いいね」を押したか確認する
    $serch->execute(array($count2,$_SESSION['username']));
    $row2 = $serch->fetch(PDO::FETCH_ASSOC);
    if ($row2['count']>0) {
        return true;
    } else {
        return false;
    }
}
 ?>
<?php //いいね数カウント関数
function good_count($count2)
{
    global $db;
    $serch =$db->prepare('SELECT COUNT(comment_id=? OR NULL) AS count FROM good');//過去に「いいね」を押したか確認する
    $serch->execute(array($count2));
    $row2 = $serch->fetch(PDO::FETCH_ASSOC);
    return $row2['count'];
}

function get_img($username)
{//プロフィール画像取得関数
    global $db;
    $serch =$db->prepare('SELECT * FROM members WHERE username=?');
    $serch->execute(array($username));
    $row = $serch->fetch(PDO::FETCH_ASSOC);
    return $row['picture'];
}
?>


<?php
    $flag="";
    if ($_GET['username']==$_SESSION['username']) {//パラメータとセッションの値が一致していた場合、編集できる
        $flag=true;
    } else {//他のユーザーがプロフィールを見る状態
        $flag=false;
    }

    $comments = $db->prepare('SELECT * FROM comment,thread_list WHERE username=? AND thread_list.id=comment.video_id ORDER BY comment_id DESC');
    $comments->execute(array($_GET['username']));


    $thread_info = $db->prepare('SELECT * FROM thread_list WHERE creater=?');
    $thread_info->execute(array($_GET['username']));

    ?>

<body>
    <header>
        <!--------------------------------------ハンバーガーメニュー--------------------------------------->
        <div class="hamburger_and_logo">
            <div class="hamburger">
                <span></span>
                <!--ハンバーガーの棒たち-->
                <span></span>
                <!--ハンバーガーの棒たち-->
                <span></span>
                <!--ハンバーガーの棒たち-->
            </div>
            <a href="index.php"><img src="https://cdn.pixabay.com/photo/2017/11/10/05/05/youtube-2935416_1280.png"
                    alt="" style="height:50px"></a>
            <!--ヘッダーのロゴ-->
        </div>
        <nav class="menu">
            <!--横からでてくるメニュー-->
            <div class="menu_bottom_border">
                <!--線引くためのクラス-->
                <div class="menu-hamburger">
                    <!--横からでてくるハンバーガー-->
                    <span></span>
                    <!--横からでてくるハンバーガーの棒たち-->
                    <span></span>
                    <!--横からでてくるハンバーガーの棒たち-->
                    <span></span>
                    <!--横からでてくるハンバーガーの棒たち-->
                </div>
                <a href="index.php"><img src="https://cdn.pixabay.com/photo/2017/11/10/05/05/youtube-2935416_1280.png"
                        alt="" style="height:50px"></a>
                <!--ヘッダーのロゴ-->
            </div>
            <ul class="menu-content">
                <!--横からでてくるメニューの内容-->
                <li><a href="top.html"><i class="fas fa-home"></i> top</a><br></li>
                <li><a href="in_url.php"><i class="fas fa-user-edit"></i> make or search</a></li>
                <li><a href="mypage.php?username=<?php echo $_SESSION['username']; ?>"><i class="fas fa-user"></i>
                        mypage</a></li>
            </ul>
        </nav>
        <div class="menu-background"></div>
        <!--ハンバーガー押されたら、背景をグレーにする-->
    </header>

    <div class="top_container">
        <img src="member_picture/<?php echo get_img($_GET['username']); ?>.jpeg" style="border-radius: 50%;" alt="">
        <h1><?php echo $_GET['username'] ?>さんのページです。</h1>
    </div>
    <img src="" alt="">

    <?php if ($flag): ?>
    <!---------------------------マイページのメニュー------------------------------->
    <div class="mypage_button">
        <p id="comment_list">コメント一覧</p>
        <p id="thread_info">スレッド一覧</p>
        <p id="password_change">パスワード変更</p>
        <p id="logout"><a href="logout.php">ログアウト</a></p>
    </div>
    <?php endif; ?>

    <div class="mypage_comments">
        <div id="result">
            <?php
while ($row = $comments->fetch()) {
        echo "<div id=\"ex_comment_container\">";
        echo "<a href=\"comment.php?url=".$row['youtube_url']."\">";
        echo $row['title'];
        echo "</a>";
        echo "<div id=\"comment_container\">";
        echo "<img class=\"icon\" src=\"member_picture/".get_img($_GET['username']).".jpeg\" style=\"width: 35px; height:35px;\" alt=\"\">";
        echo "<div class=\"comment_box\">";
        echo "<p class=\"username\">".$row['username']."<span class=\"date\">　".$row['date']."</span></p>";
        echo "<p class=\"comment\">".$row['comment']."</p>";
        if (session_conf($row['comment_id'])) {
            //trueの場合はto_redクラスがついている！！！過去に自分が「いいね」したコメント
            echo "<i id=\"good_".$row['comment_id']."\" class=\"good fas fa-thumbs-up\" value=\"".$row['comment_id']."\"></i><span d=\"true\">".good_count($row['comment_id'])."</span>";
            if ($flag) {
                echo " <i class=\"gabage fas fa-trash-alt\" value=\"".$row['comment_id']."\"></i>";
            }
        } else {
            echo "<i id=\"good_".$row['comment_id']."\" class=\"good far fa-thumbs-up\" value=\"".$row['comment_id']."\"></i><span d=\"false\">".good_count($row['comment_id'])."</span>";
            if ($flag) {
                echo " <i class=\"gabage fas fa-trash-alt\" value=\"".$row['comment_id']."\"></i>";
            }
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
?>
        </div>
        <!--------------------------------パスワード再設定-------------------------------------------->
        <div id="repassword" class="container2" style="display:none">
            <div class="container">
                <p class="login_title">パスワード変更</p>
                <p>情報を入力してください</p>
                <form>
                    <input class="now_password" name="username" type="password" placeholder="現在のパスワード"><br>
                    <input class="new_password1" name="password" type="password" placeholder="新しいパスワード"><br>
                    <input class="new_password2" name="password" type="password" placeholder="新しいパスワード再入力"><br>
                    <p id="error_message" style="color:red"></p>
                    <p id="scucces_message" style="color:green"></p>
                    <div class="container3">
                        <div class="link_area">
                            <a href="../join/index.php">アカウントを作成</a>
                        </div>
                        <div class="button_area">
                            <div class="button">変更</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--------------------------------スレッドインフォ-------------------------------------------->
    <div id="thread_info_list" style="display:none">
        <?php while ($row = $thread_info->fetch()): ?>
        <div class="<?php echo $row['id'] ?>">
            <a href="comment.php?url=<?php echo $row['youtube_url'] ?>">
                <p><?php echo $row['title'] ?></p>
            </a>
            <?php if ($flag): ?>
            <i class="thread_gabage fas fa-trash-alt"></i>
            <?php endif;?>
        </div>
        <br>
        <?php endwhile; ?>
    </div>

    <?php
$is_login="";
if (!empty($_SESSION['username'])) {
    //ログインなう
    $is_login = "true";
} else {
    //ログインしてないなう
    $is_login = "false";
}
?>
</body>
<script>
//------------------------------ハンバーガーメニューの処理-----------------------------------
$(function() {
    function noScroll(event) {
        event.preventDefault();
    }
    $('.hamburger').click(function() {
        $('.menu').toggleClass('open');
        $('.menu-background').toggleClass('menu-background2');
        // スクロール禁止(SP)
        document.addEventListener('touchmove', noScroll, {
            passive: false
        });
        // スクロール禁止(PC)
        document.addEventListener('mousewheel', noScroll, {
            passive: false
        });
    });
    $('.menu-hamburger').click(function() {
        $('.menu').toggleClass('open');
        $('.menu-background').toggleClass('menu-background2');
        // スクロール禁止を解除(SP)
        document.removeEventListener('touchmove', noScroll, {
            passive: false
        });
        // スクロール禁止を解除(PC)
        document.removeEventListener('mousewheel', noScroll, {
            passive: false
        });
    });
    $('.menu-background').click(function() {
        $('.menu').toggleClass('open');
        $('.menu-background').toggleClass('menu-background2');
        // スクロール禁止を解除(SP)
        document.removeEventListener('touchmove', noScroll, {
            passive: false
        });
        // スクロール禁止を解除(PC)
        document.removeEventListener('mousewheel', noScroll, {
            passive: false
        });
    });
});
//--------------------------------------いいね機能-----------------------------------------------------------
$(function() {
    $(document).on('click', '.good', function() { //goodクラスをクリックしたら
        let is_login = <?php echo $is_login; ?>;
        if (is_login) {
            console.log('goodボタンテストコード');
            let comment_id = $(this).attr('value'); //ユーザー定義のvalueの値(コメントid)を取得して
            let good_count_area = "good_" + comment_id;
            //$('#'+b).toggleClass('to_red');

            var classVal = $(this).attr('class'); // classの値を取得
            var classVals = classVal.split(' '); // 取得した値を分割
            // 配列になっているのでforで一つずつ取得できる
            for (var i = 0; i < classVals.length; i++) {
                console.log(classVals[i]);
                if ("fas" == classVals[i]) {
                    $(this).removeClass('fas');
                    $(this).addClass('far');
                    console.log("A");
                    break;
                } else if ("far" == classVals[i]) {
                    $(this).removeClass('far');
                    $(this).addClass('fas');
                    console.log("B");
                    break;
                }
            }
            $.ajax({
                url: 'comment_good.php',
                type: 'POST',
                datatype: 'json',
                data: {
                    'id': comment_id,
                    'username': '<?php echo $_SESSION['username'] ?>'
                }
            }).done(function(data) {
                $('#' + good_count_area).next().text(data[0].good_count);
            }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
                //エラーコードたち
                console.log("XMLHttpRequest : " + XMLHttpRequest.status);
                console.log("textStatus     : " + textStatus);
                console.log("errorThrown    : " + errorThrown.message);
                console.log('失敗');
            })
        } else {
            console.log("ログインしていません");
            $.ajax({
                url: 'is_login.php',
                type: 'POST',
                datatype: 'json',
                data: {
                    'is_login': is_login
                }
            }).done(function(data) {
                console.log(data[0].redirect_url);
                window.location.href = data[0].redirect_url;
            }).fail(function(data) {

            })
        }
    });
});

//----------------------------------コメント削除----------------------------------------
$(function() {
    $('.gabage').click(function() {
        $(this).parent().parent().parent().remove(); //削除ボタンを押したら画面上でコメントを消す(まだデータベースをいじってないので、本当は消えてない)
        console.log($(this).attr('value'));
        $.ajax({
            url: 'comment_delete.php',
            type: 'POST',
            data: {
                'comment_id': $(this).attr('value')
            }
        })
    });
});
//-----------------------------------スレッド削除----------------------------------------
$(function() {
    $('.thread_gabage').click(function() {
        $(this).parent().remove(); //削除ボタンを押したら画面上でコメントを消す(まだデータベースをいじってないので、本当は消えてない)
        let thread_id = $(this).parent().attr('class');
        console.log(thread_id);
        $.ajax({
            url: 'thread_delete.php',
            type: 'POST',
            data: {
                'thread_id': thread_id
            }
        })
    });

});

//--------------------------------画面切り替え処理-------------------------------------------

$(document).on('click', '#comment_list', function() {
    $('#result').show(1000);
    $('#repassword').fadeOut();
    $('#thread_info_list').fadeOut();
});
$(document).on('click', '#thread_info', function() {
    $('#result').fadeOut();
    $('#repassword').fadeOut();
    $('#thread_info_list').show(1000);
});
$(document).on('click', '#password_change', function() {
    $('#result').fadeOut();
    $('#thread_info_list').fadeOut();
    $('#repassword').show(1000);
});

//---------------------------------パスワード再設定処理-----------------------------------------
$(document).on('click', '.button', function() {
    var now_password = $('.now_password').val();
    var new_password1 = $('.new_password1').val();
    var new_password2 = $('.new_password2').val();

    $('.now_password').val(''); //inputを空白にする
    $('.new_password1').val(''); //inputを空白にする
    $('.new_password2').val(''); //inputを空白にする

    $.ajax({
        url: 're_password.php',
        type: 'POST',
        datatype: 'json',
        data: {
            'username': '<?php echo $_SESSION['username'] ?>', //パスワードを変更するユーザー
            'now_password': now_password,
            'new_password1': new_password1,
            'new_password2': new_password2
        }
    }).done(
        function(data) {
            console.log(data[0].now_pass_flag);
            if (data[0].now_pass_flag && data[0].new_pass_flag) {
                console.log("アイウエオ");
                $('#error_message').fadeOut();
                $('#scucces_message').fadeIn();
                $('#scucces_message').text('パスワードを変更しました！');
                $('#scucces_message').fadeOut(1000);
            }
            if (!data[0].now_pass_flag) {
                $('#error_message').fadeIn();
                $('#error_message').text('現在のパスワードが違います');
            }
            if (!data[0].new_pass_flag) {
                $('#error_message').fadeIn();
                $('#error_message').text('新しいパスワードに入力エラーです');
            }

        }
    ).fail(

    )

});
</script>

</html>