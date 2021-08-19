<?php
require('dbconnect.php');
session_start();
?>

<?php
if (!$_SESSION) {
    $redirect_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/login.php';
    header('location:'.$redirect_url);
    exit();
}

$not_found_flag=false;//動画が見つからなかった場合、falseにする。
$youtube_url="";
function toEmbed($url)
{//動画idを抽出する関数
    global $youtube_url;
    if (isset($url) == true) {
        /** ＨＴＭＬコードをエンティ */
        $url = htmlspecialchars($url, ENT_QUOTES);
        /** 入力内容を取得 */
        $youtube_url = $url;
    
        if (strpos($youtube_url, "watch") != false) /* ページURL ? */
    {
      /* strpos関数で"="のインデックスを取得し、+1することにより動画id開始のインデックスを得る。*/
      //そしてsubstrで動画idを取得する。
      $youtube_url = substr($youtube_url, (strpos($youtube_url, "=")+1));
    } else {
        /** 短縮URL用に変換 */
        $youtube_url = substr($youtube_url, (strpos($youtube_url, "youtu.be/")+9));
    }
        return $youtube_url;
    }
}
//同じ動画idがあるかデータベースを確認して、あった場合、そのスレッドにリダイレクトする！
$video_thread = $db->prepare('SELECT COUNT(*) AS cnt FROM thread_list WHERE youtube_url=?');
$video_thread->execute(array(toEmbed($_GET['q'])));
$record = $video_thread->fetch();
if ($record['cnt']>0) {
    //echo "その動画のスレッドすでにあります！";
    header('location:comment.php?url='.toEmbed($_GET['q']));
    exit();
}



if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}

require_once __DIR__ . '/vendor/autoload.php';


if (isset($_GET['q'])) {
    $DEVELOPER_KEY = $_ENV['DEVELOPER_KEY'];

    $client = new Google_Client();
    $client->setDeveloperKey($DEVELOPER_KEY);

    $youtube = new Google_Service_YouTube($client);

    $htmlBody = '';
    try {
        $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => toEmbed($_GET['q']),'maxResults' =>50
    ));

        $videos = '';
        $channels = '';
        $playlists = '';
        $thumbnail = '';

        foreach ($searchResponse['items'] as $searchResult) {
            if ($searchResult['id']['videoId']==toEmbed($_GET['q'])) {
                switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $videos = $searchResult['snippet']['title'];
          $videos2 = $searchResult['snippet']['description'];
          $channelname = $searchResult['snippet']['channelTitle'];
          $thumbnail = $searchResult['snippet']['thumbnails']['medium']['url'];
          break;
        case 'youtube#channel':
          $channels .= sprintf(
              '<li>%s (%s)</li>',
              $searchResult['snippet']['title'],
              $searchResult['id']['channelId']
          );
          break;
        case 'youtube#playlist':
          $playlists .= sprintf(
              '<li>%s (%s)</li>',
              $searchResult['snippet']['title'],
              $searchResult['id']['playlistId']
          );
          break;
        }
            }
            break;
        }
    } catch (Google_Service_Exception $e) {
        $htmlBody .= sprintf(
            '<p>A service error occurred: <code>%s</code></p>',
            htmlspecialchars($e->getMessage())
        );
    } catch (Google_Exception $e) {
        $htmlBody .= sprintf(
            '<p>An client error occurred: <code>%s</code></p>',
            htmlspecialchars($e->getMessage())
        );
    }
}
if (empty($videos)) {
    $not_found_flag=true;
}
?>

<!doctype html>
<html>

<head>
    <title>Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/create.css">
</head>

<body>
    <div id="container">
        <?php if (!$not_found_flag)://動画が見つかった時$not_found_flag=false?>
        <?php
    $youtube_url = toEmbed($_GET['q']);
    echo "<div class=\"youtube\">";
    echo "<div class=\"youtube_container\">";
    echo "<iframe src=\"https://www.youtube.com/embed/${youtube_url}\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
    echo "</div>";
    echo "</div>";
    ?>
        <h2>動画タイトル</h2>
        <?php echo "<p>$videos</p>";?>
        <h2>動画の説明</h2>
        <?php echo "<p>$videos2</p>";?>
        <h2>この動画をアップしているチャンネル</h2>
        <?php echo "<p>$channelname</p>";?>

        <form action="to_threadinfo.php" method="POST">
            <!-hiddenを使って、to_threadinfoに情報を渡す！！！-->
                <input type="hidden" name="title" value="<?php echo $videos ?>">
                <input type="hidden" name="url" value="<?php echo $youtube_url ?>">
                <input type="hidden" name="thumbnail" value="<?php echo $thumbnail ?>">
                <input type="hidden" name="creator" value="<?php echo $_SESSION['username'] ?>">
                <div class="button_area">
                    <input id="button" type="submit" value="スレッド作成">
                </div>
        </form>
        <?php endif; ?>
        <?php if ($not_found_flag): //動画が見つからなかった時$not_found_flag=true?>
        <p id="error_message">指定されたURLの動画は見つかりませんでした。URLが正しく指定されているか確認してください。</p>
        <?php endif; ?>
    </div>




</body>

</html>