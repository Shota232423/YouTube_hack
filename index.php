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
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/header.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>

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
        <img src="https://cdn.pixabay.com/photo/2017/11/10/05/05/youtube-2935416_1280.png" alt="" style="height:50px">
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
            <img src="https://cdn.pixabay.com/photo/2017/11/10/05/05/youtube-2935416_1280.png" alt=""
                style="height:50px">
            <!--ヘッダーのロゴ-->
        </div>
        <ul class="menu-content">
            <!--横からでてくるメニューの内容-->
            <li><a href="top.html"><i class="fas fa-home"></i> top</a><br></li>
            <li><a href="in_url.php"><i class="fas fa-user-edit"></i> make or search</a></li>
            <li><a href="mypage.php?username=<?php echo $_SESSION['username']; ?>"><i class="fas fa-user"></i>
                    mypage</a>
            </li>
        </ul>
    </nav>
    <div class="menu-background"></div>
    <!--ハンバーガー押されたら、背景をグレーにする-->
</header>

<?php
//表示する動画スレッド情報を取得、サムネ、スレッド作成者etc,,,
    $thread_lists = $db->query('SELECT * FROM thread_list ORDER BY id DESC');
    ?>

<body>

    <?php while ($thread_list = $thread_lists->fetch()): ?>

    <a href="comment.php?url=<?php echo $thread_list['youtube_url'] ?>">

        <div class="box">
            <div class="thumbnail">
                <img src="<?php print($thread_list['thumbnail_url']); ?>" alt="">
            </div>

            <div class="explain">
                <!--動画タイトル-->
                <p class="title"><?php print($thread_list['title']); ?></p>
                <!--スレッド作成者-->
                <p class="creater"><?php print($thread_list['creater']); ?></p>
                <!--作成日時-->
                <p class="date"><?php print($thread_list['date']); ?></p>
            </div>
            　　　
        </div>

    </a>

    <?php endwhile; ?>


</body>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
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
        document.removeEventListener('touchmove', noScroll, {
            passive: false
        });
        // スクロール禁止を解除(PC)
        document.removeEventListener('mousewheel', noScroll, {
            passive: false
        });
    });
});
</script>

</html>