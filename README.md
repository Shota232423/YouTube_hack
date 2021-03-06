# YouTube_hack

## サービス概要
YouTubeの重要なコンテンツは動画だけではありません。コメントも重要なコンテンツの内の一つと考えています。<br>
ユーザーにとってコメント欄が開放されていない問題点は<br>
**TwitterといったSNSに感想などをコメントするが、他の人のコメントなどを一気に見ることができない点です。**

また動画に対してコメントを残すために動画を不正アップロードし、コメント欄を開放するという事例も起きています。<br>
この事例は動画元の再生回数を奪っていることに繋がっています。<br>

これら問題を解決するために**ユーザーが「コメント欄を作りたい動画のURL」を入力することで、コメント欄を作りコメントすることができる**Webアプリを作りました。<br>

## 機能
- サインイン
- ログイン
- ログアウト
- 動画検索
- コメント欄作成(スレッド作成)
- コメント欄削除(マイページ)
- コメント(Ajax)
- コメント削除(マイページ)
- いいね(コメントに対して)(Ajax)
- パスワード変更

## 使用した技術

#### 使用した言語
PHP HTML CSS JavaScript(jQuery)

#### 使用したデータベース
MySQL
- youtube_hack_db<br>
  - comment
  - good
  - members
  - thread_list
#### 使用したインフラ
Heroku

#### 使用したAPI
YouTube Data API(https://developers.google.com/youtube/v3/getting-started?hl=ja)

#### その他
Font Awesome Material Icons
