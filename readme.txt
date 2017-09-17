=== Lockets ===
Contributors: wackey
Donate link: 
Tags: AD,affiliate,Web API,location
Requires at least: 4.8
Tested up to: 4.8.1
Stable tag: 0.34
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plug-in that gets information on spots such as shops and inns from various APIs and displays the latest information embedded in the blog.
Use API and output information shops,hotels,etc.

and,ability to convert links to affiliate links with "valuecommerce Auto MyLink Service(JavaScript)".

== Description ==
<a href="http://lockets.jp/plugin/">日本語の説明を読む</a>

A plug-in that gets information on spots such as shops and inns from various APIs and displays the latest information embedded in the blog.Also, This plugin will assist you such as creating affiliate links.

*HOTEL Search
 -Rakuten Travel Search API,Jalan Web Service(Hotel search)
 　楽天トラベルAPIとじゃらんWebサービスに対応しています

*Restaurant search
 -HOTPEPPER,Gurunavi (Restaurant search)
 　ホットペッパーAPI、ぐるなびWebサービスに対応しています

*Affiliate
 -Rakuten Affiliate
 　楽天トラベルでの施設へのリンクなどをアフィリエイトリンクとしして出力します
 -Value Commerce Auto MyLink ex.HOT PEPPER ,Jalan's link, etc.
  バリューコマースオートMyLink機能に対応しています（ホットペッパーやじゃらんへのリンクをバリューコマースのアフィリエイトリンクに自動変換します。アフィリエイトの提携作業が必要です）

* ---USE API---

*Value Commerce Auto MyLink ex.HOT PEPPER ,Jaran's link, etc.
　https://www.valuecommerce.ne.jp/topics/auto_mylink
 　バリューコマースのオートMyLink機能を使ってホットペッパーやじゃらんのリンクをアフィリエイトリンクに変換する機能もあります。

*Rakuten Travel Search API(Hotel) https://webservice.rakuten.co.jp/api/hoteldetailsearch/
 楽天トラベル施設情報APIを使用して楽天トラベルのホテルや旅館の施設情報を取得し、WordPressで作成したブログ上にその情報を表示します。

*HOTPEPPER Search API(Restaurant search)  https://webservice.recruit.co.jp/hotpepper/reference.html#a1to
　リクルートWebサービスのホットペッパーグルメサーチAPIを使用して飲食店情報を取得し、WordPressで作成したブログ上にその情報を表示します。

*Gurunavi Web Service(Restaurant search)  http://api.gnavi.co.jp/api/manual/restsearch/
　ぐるなびWebサービスのレストラン検索APIを使用して飲食店情報を取得し、WordPressで作成したブログ上にその情報を表示します。

*Jalan Web Service(Hotel search)  http://www.jalan.net/jw/jwp0000/jww0001.do
　じゃらんWebサービスじゃらん宿表示API（アドバンス）を使用してホテルや旅館の施設情報を取得し、WordPressで作成したブログ上にその情報を表示します。

*with Google Maps embed.(Map) https://www.google.co.jp/maps
　Google Mapsの地図埋め込み機能を使い、WordPressで作成したブログ上に地図を表示します。


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
= 0.08 =
* Rakuten Travel Search API,and HOTPEPPER Search API.
= 0.02 =
* Delete Yahoo! kanren search.
= 0.01 =
* Rakuten Travel Search,hotelno.



