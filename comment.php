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
    <title>YouTube_Hack</title>
    <link rel="stylesheet" href="style/comment.css">
    <link rel="stylesheet" href="style/header.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>

<?php //埋め込み動画の処理
//動画url(id)と一致するthreadの情報を取得する！
$threads_info = $db->prepare('SELECT * FROM thread_list WHERE youtube_url=?');
$threads_info->execute(array($_GET['url']));
$thread_info = $threads_info->fetch();
$youtube_title = $thread_info['title'];//タイトル取得
$youtube_url = $_GET['url'];//url(id)取得
$embed_code = "<iframe width=\"640\" height=\"360\" src=\"https://www.youtube.com/embed/${youtube_url}\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";

$comments_info = $db->prepare('SELECT * FROM comment,members WHERE comment.video_id=? AND comment.username=members.username ORDER BY comment.comment_id DESC');
$comments_info->execute(array($thread_info['id']));
?>

<?php //いいね数カウント関数
function good_count($count)
{
    global $db;
    $serch =$db->prepare('SELECT COUNT(comment_id=? OR NULL) AS count FROM good');//過去に「いいね」を押したか確認する
    $serch->execute(array($count));
    $row = $serch->fetch(PDO::FETCH_ASSOC);
    return $row['count'];
}
?>
<?php //セッションの人がいいねして「いた」ときの処理
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
 ?>


<body>
    <header>
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
        <!--埋め込みコード-->
        <?php
    echo "<div class=\"youtube\">";
    echo $embed_code;
    echo "</div>";
    //echo $_GET['title'];
    //echo $_SESSION['username'];
    ?>

        <p class="title"><?php echo $youtube_title; ?></p>
        <!--タイトル表示-->
        <!-----------------コメント入力欄---------------------------->
        <input id="comment" type="text" name="comment" placeholder="コメントを入力" maxlength="166"><br>
        <div class="comment_button">
            <button id="ajax">コメント</button>
        </div>



        <div id="result">
            <?php
while ($row = $comments_info->fetch()) {
        echo "<div id=\"comment_container\">";
        echo "<a href=\"mypage.php?username=".$row['username']."\">";
        echo "<img class=\"icon\" src=\"member_picture/".$row['picture'].".jpeg\" style=\"width: 35px; height:35px;\" alt=\"\">";
        echo "</a>";
        echo "<div class=\"comment_box\">";
        echo "<p class=\"username\">".$row['username']."<span class=\"date\">　".$row['date']."</span></p>";
        echo "<p class=\"comment\">".$row['comment']."</p>";
        if (session_conf($row['comment_id'])) {
            //trueの場合はto_redクラスがついている！！！過去に自分が「いいね」したコメント
            echo "<i id=\"good_".$row['comment_id']."\" class=\"good fas fa-thumbs-up\" value=\"".$row['comment_id']."\"></i><span d=\"true\">".good_count($row['comment_id'])."</span>";
        } else {
            echo "<i id=\"good_".$row['comment_id']."\" class=\"good far fa-thumbs-up\" value=\"".$row['comment_id']."\"></i><span d=\"false\">".good_count($row['comment_id'])."</span>";
        }
        echo "</div>";
        echo "</div>";
    }

?>
        </div>



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

    <script>
    //toggleは表示していたら非表示に、非表示の場合は表示にしてくれる！！！
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

    //いいね機能---------------------------------------------------------------------------------------
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
                        break;
                    } else if ("far" == classVals[i]) {
                        $(this).removeClass('far');
                        $(this).addClass('fas');
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

    //コメント機能のajax処理-----------------------------------------------------------------------------
    $(function() {

        $('#ajax').on('click', function() {
            let is_login = <?php echo $is_login; ?>;
            if (is_login) {
                $.ajax({
                        url: 'comment_pro.php', //送信先
                        type: 'POST', //送信方法
                        datatype: 'json', //受け取りデータの種類
                        data: {
                            'id': '<?php echo $thread_info['id'] ?>', //動画id 
                            'username': '<?php echo $_SESSION['username'] ?>', //ログインしているユーザーネーム
                            'comment': $('#comment').val() //コメント
                        }
                    })
                    // Ajax通信が成功した時
                    .done(function(data) {
                        console.log('通信成功');
                        let comment_code = "";
                        let good_code = "";
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].good_conf) {
                                good_code = "<i id=\"good_" + data[i].comment_id +
                                    "\" class=\"good fas fa-thumbs-up\" value=\"" + data[i]
                                    .comment_id + "\"></i><span d=\"true\">" + data[i].good_count +
                                    "</span>";
                            } else {
                                good_code = "<i id=\"good_" + data[i].comment_id +
                                    "\" class=\"good far fa-thumbs-up\" value=\"" + data[i]
                                    .comment_id + "\"></i><span d=\"false\">" + data[i].good_count +
                                    "</span>";
                            }

                            comment_code += "<div id=\"comment_container\">" +
                                "<a href=\"mypage.php?username=" + data[i].username + "\">" +
                                "<img class=\"icon\" src=\"member_picture/" + data[i].pic +
                                ".jpeg\" style=\"width: 35px; height:35px;\" alt=\"\">" +
                                "</a>" +
                                "<div class=\"comment_box\">" +
                                "<p class=\"username\">" + data[i].username +
                                "　<span class=\"date\">" + data[i].date + "</span></p>" +
                                "<p class=\"comment\">" + data[i].comment + "</p>" + good_code +
                                "</div></div>";

                            //data[i].comment_id コメントid
                            $('#result').html(comment_code); //idがresultの部分をaaaの内容に変える
                            console.log('通信成功');
                            console.log(data);
                        }
                    })
                    // Ajax通信が失敗した時
                    .fail(function(data) {
                        $('#result').html(data);
                        console.log('通信失敗');
                        console.log(data[0]);
                    })
                $("#comment").val("");
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
        }); //#ajax click end

    }); //END
    </script>
</body>

</html>