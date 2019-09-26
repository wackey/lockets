=== Lockets ===
Contributors: wackey
Donate link: 
Tags: AD,affiliate,Web API,location
Requires at least: 5.2
Tested up to: 5.2
Stable tag: 0.92
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plug-in that gets information on spots such as shops and inns from various APIs and displays the latest information embedded in the blog.
Use API and output information shops,hotels,etc.

and,ability to convert links to affiliate links with "Valuecommerce LinkSwitch Service(JavaScript)".

== Description ==
<a href="https://plugins.lockets.jp/">日本語の説明を読む</a>

A plug-in that gets information on spots such as shops and inns from various APIs and displays the latest information embedded in the blog.Also, This plugin will assist you such as creating affiliate links.

*Available only with Classic Editor
　現在、クラシックエディターのみで利用出来ます

*HOTEL Search
 -Rakuten Travel Search API,Jalan Web Service(Hotel search)
 　楽天トラベルAPIとじゃらんWebサービスに対応しています

*Restaurant search
 -HOTPEPPER,Gurunavi (Restaurant search)
 　ホットペッパーAPI、ぐるなびWebサービスに対応しています

*Place Search
 -Google Places API,Maps Embed API
  GoogleプレイスAPI、Google Maps Embed APIに対応しています。
  
*Lockets feed
Locketsなど外部に配信可能なfeedを生成します。

*Affiliate Beta
 -Rakuten Affiliate
 　楽天トラベルでの施設へのリンク、楽天市場での商品検索結果などをアフィリエイトリンクとしして出力します
 -Value Commerce LinkSwitch ex.HOT PEPPER ,Jalan's link, etc.
  バリューコマースLinkSwitch機能に対応しています（ホットペッパーやじゃらんなどへのリンクをバリューコマースのアフィリエイトリンクに自動変換します。アフィリエイトの提携作業が必要です）
 -Value Commerce Item search API
　バリューコマースの商品検索APIを使用し、バリューコマースアフィリエイト可能な商品をアフィリエイトリンクで紹介できるようにします。
 -Amazon Product Advertising API
 　Amazon Product Advertising APIによりAmazonアソシエイトリンクの生成を行います。

* ---USE API---

*Value Commerce Auto MyLink ex.HOT PEPPER ,Jalan's link, etc.
　https://www.valuecommerce.ne.jp/topics/auto_mylink
 　バリューコマースのLinkSwitch機能を使ってホットペッパーやじゃらんへのリンクをアフィリエイトリンクに変換する機能もあります。

*Rakuten Travel Search API(Hotel) https://webservice.rakuten.co.jp/api/hoteldetailsearch/
 楽天トラベル施設情報APIを使用して楽天トラベルのホテルや旅館の施設情報を取得し、WordPressで作成したブログ上にその情報を表示します。

*HOTPEPPER Search API(Restaurant search)  https://webservice.recruit.co.jp/hotpepper/reference.html#a1to
　リクルートWebサービスのホットペッパーグルメサーチAPIを使用して飲食店情報を取得し、WordPressで作成したブログ上にその情報を表示します。

*Gurunavi Web Service(Restaurant search)  http://api.gnavi.co.jp/api/manual/restsearch/
　ぐるなびWebサービスのレストラン検索APIを使用して飲食店情報を取得し、WordPressで作成したブログ上にその情報を表示します。

*Jalan Web Service(Hotel search)  http://www.jalan.net/jw/jwp0000/jww0001.do
　じゃらんWebサービスじゃらん宿表示API（アドバンス）を使用してホテルや旅館の施設情報を取得し、WordPressで作成したブログ上にその情報を表示します。

*Google Places API https://developers.google.com/places/?hl=ja
　Gooleプレイス検索APIを使用してスポットを検索し、スポットの地図を表示します。

*with Google Maps embed.(Map) https://www.google.co.jp/maps https://developers.google.com/maps/documentation/embed/?hl=ja
　Google Mapsの地図埋め込み機能を使い、WordPressで作成したブログ上に地図を表示します。
 上記楽天トラベル、ホットペッパー、ぐるなびの情報と連携して地図表示をします。

*Value Commerce Item Search API（Item search） https://www.valuecommerce.ne.jp/feature/webservice.html
 バリューコマースの商品検索APIを使用し、アフィリエイトリンク可能な商品の表示を行います。

*Amazon Product Advertising API（Item search） https://affiliate.amazon.co.jp/home
　Amazon.co.jpにおける商品検索に対応

== Installation ==
1. Unzip the plugin archive and put lockets folder into your plugins directory (wp-content/plugins/) of the server.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the plugin at “Lockets” menu at the WordPress admin panel. Please input API key, Affiliate ID,template.
1. When you write a blog using a short code, information is displayed.[LocketsHotpepper shopid="HOT PEPPER shopid"] -> output HOTPEPPER SHOP infomation,[LocketsRakutenTravel hotelno="RakutenTravel hotel no"] -> output Hotel infomation


== Frequently asked questions ==

none.

= A question that someone might have =

An answer to that question.

== Changelog ==
= 0.91 =
* remove target="_blank"
　target="_blank"をデフォルト出力フォーマットから削除
= 0.90 =
* icon changed.Lockets Plugin oficial page changed
　アイコンを新ロゴのものに変更。管理画面に解説動画など挿入。プラグイン解説のサイト移転
= 0.85 =
* spot feed(Beta), format changed.
　外部配信のフォーマットの不要コードの削除、拠点タグの修正。
= 0.84 =
* spot feed(Beta), logo,format changed.
　外部配信のロゴ設定機能、フォーマットの微修正を行いました。
= 0.81 =
* spot feed(Beta), Multiple distribution start.
　外部配信、1記事で複数拠点情報配信可能に。外部配信ベーシック認証機能廃止。
= 0.81 =
* Jalan Google Maps,spot feed(Beta) is now available.
　じゃらん日本測地系緯度経度を世界測地系へ変換しGoogle Maps表示を可能に
　外部配信βメニュー実装（ベーシック認証、任意URL）、スポット情報（緯度経度、スポット名、ソース元）のFeedへの反映
= 0.70 =
* Gurunaib URL changed.
　ぐるなびAPI URL変更
= 0.62 =
* Improvement of management screen menu, Rakuten developer logo update, on / off function of feed function to deliver to Lockets.
　管理画面メニューの改善、楽天デベロッパーロゴ更新、Locketsへ配信するfeed機能のオンオフ機能
= 0.61 =
* Lockets feeds  is now available.
　Locketsへ配信するfeed機能の実装
= 0.60 =
* Amazon Product Advertising API.
　Amazon Product Advertising API対応版正式リリース。
= 0.54 =
* Amazon Product Advertising API.and Lockets Search Interface is easy to use.
　Amazon Product Advertising APIによる商品検索とAmazonアソシエイトのリンク生成に対応した。また、同時にLockets Searchインターフェースにそれぞれの検索結果へのリンクを設置し使いやすくした。
= 0.53 =
* Valuecommerce Item search is now available.
　バリューコマース商品検索APIと商品表示ならびにアフィリエイトリンク埋め込みに対応した。楽天商品検索結果表示のタグの間違いを修正
= 0.52 =
* Rakuten Item search is now available.Bug fix.
　楽天市場商品検索と埋め込みに対応した。商品検索結果が0の時の処理を追加。
= 0.50 =
* Google Places API is now available.rename Auto Mylink -> LinkSwitch.
　GoogleプレイスAPI検索に対応（プレイス検索とその場所への地図表示のみ）。またバリューコマースのオートMyLinkの名称変更に伴う修正を行った。
= 0.48 =
* 各種クレジットバナーのSSL対応（各社に確認済み）
= 0.47 =
* jalan picture http:// -> https://
　じゃらんのAPIから呼び出した画像が常時SSLのURLではないので、常時SSL対応になるように書き換えを行った（じゃらんに問い合わせ確認済み）
= 0.46 =
* Credits added to search result display
　投稿画面の検索画面において、検索結果表示にクレジットを追加しました
= 0.45 =
* We added a function to search for restaurants and hotels from the Lockets button on the submission screen and insert short codes there.
　投稿画面のLocketsボタンから飲食店やホテルを検索し、そこからショートコードを挿入する機能を追加しました。
= 0.42 =
* Gurunabi access modified.
= 0.31 =
* admin gnavi panel add.
= 0.30 =
* post interface Jalan add,typo modified.
= 0.24 =
* Jalan Hotel Search add.
= 0.22 =
* Gurunavi Web service,Gurunavi shop search.
= 0.21 =
* HOT PEPPER Coupon URL disabled.
= 0.20 =
* Admin panel redesinged.
= 0.19 =
* post interface modified.
= 0.18 =
* Default Template at hotel,HOTPEPPER SHOP.
= 0.15 =
* typo bugfix.
= 0.14 =
* Goole Maps shortcode and settings add.
= 0.13 =
* HTML template sanitize changed.
= 0.12 =
* Function name change.remove_script_tag -> lockets_remove_script_tag


