<?php
/*
Plugin Name: Lockets
Plugin URI: https://lockets.jp/
Description: A plug-in that gets information on spots such as shops and inns from various APIs and displays the latest information embedded in the blog.Also, This plugin will assist you such as creating affiliate links. お店や旅館などスポットに関する情報を各種APIから取得し、ブログ内に最新の情報を埋め込んで表示するプラグイン。また、アフィリエイトリンク作成支援を行います。
Author: wackey
Version: 0.992
Author URI: https://lockets.jp/
License: GPL2
*/
/*  Copyright 2017-2019 wackey (email : takashi.wakimura@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/***------------------------------------------
　各種ライブラリ読み込み
------------------------------------------***/
require_once("lockets_func.php");
require_once("lockets_class.php");


/***------------------------------------------
　管理画面
------------------------------------------***/

// 管理画面サブメニュー用
require_once("admin_rakuten.php");
require_once("admin_hotpepper.php");
require_once("admin_gnavi.php");
require_once("admin_jalan.php");
require_once("admin_affiliate.php");
require_once("admin_feed.php");
require_once("admin_gmap.php");



// 管理画面メニュー作成関数
function lockets_menu() {
add_menu_page('Lockets', 'Lockets', 8,__FILE__, 'lockets_options','
dashicons-location-alt');
add_submenu_page(__FILE__, '楽天ウェブサービス', '楽天ウェブサービス', 8, "admin_rakuten", 'lockets_rws');
add_submenu_page(__FILE__, 'リクルートWEBサービス', 'リクルートWEBサービス', 8, "admin_recruit_webservice", 'lockets_recruit_webservice');
add_submenu_page(__FILE__, 'ぐるなびWebサービス', 'ぐるなびWebサービス', 8, "admin_gnavi_webservice", 'lockets_gnavi_webservice');
add_submenu_page(__FILE__, 'じゃらんWebサービス', 'じゃらんWebサービス', 8, "admin_jalan_webservice", 'lockets_jalan_webservice');
add_submenu_page(__FILE__, 'Google プレイス', 'Google プレイス', 8, "admin_gmap", 'lockets_gmap');
add_submenu_page(__FILE__, '外部配信β', '外部配信β', 8, "admin_feed", 'lockets_feed');
add_submenu_page(__FILE__, 'その他アフィリエイト', 'その他アフィリエイト', 8, "admin_affiliate", 'lockets_affiliate');
}

// 管理画面描画
function lockets_options() {
    $rakutentoken= get_option('rakuten_search_token');
    $rakutenaffid= get_option('rakuten_affiliate_id');
    $jalan_webservice_key= get_option('jalan_webservice_key');
    $recruit_webservice_key= get_option('recruit_webservice_key');
    $gnavi_webservice_key= get_option('gnavi_webservice_key');
    $valuecommerce_pid= get_option('valuecommerce_pid');
    $lockets_amzacckey=get_option('lockets_amzacckey');
    $lockets_amzseckey=get_option('lockets_amzseckey');
    $lockets_amzassid=get_option('lockets_amzassid');
    $lockets_gmap_apikey= get_option('lockets_gmap_apikey');
    $locketsfeedswitch= get_option('locketsfeedswitch');
?>

<div class="wrap">

<h2>Lockets設定画面</h2>

<h2>使い方</h2>
    <p>投稿画面で“Lockets”ボタンを押して、表示させたいコンテンツをキーワード検索し“挿入”ボタンを押すことで記事中にそのコンテンツを表示するショートコードを挿入します。</p>

<p>ショートコードとは次のような形で、ブログ記事中にその情報を呼び出して表示する印のようなものです。<br>
    <strong>[LocketsRakutenTravel hotelno="xxxxxxx"]</strong></p>

<p>ブログ記事を表示する度に最新の情報をそれぞれのサービスから取得し、最新の情報を表示します。<br>
    つまり、それぞれのサービス上で価格や営業時間などの情報が更新されればその最新の情報を元にコンテンツを表示します。</p>

    <p>また、表示するテンプレートはHTMLで自由にカスタマイズすることが可能です。そのカスタマイズで表示させたい項目を自由にアレンジ出来ます</p>
    
    <p>プラグイン設定解説サイトはこちら→<a href="https://plugins.lockets.jp/">Locketsプラグイン公式サイト</a></p>
    
    <p>使い方のイメージは下記動画をご確認ください。</p>
    
    <iframe width="560" height="315" src="https://www.youtube.com/embed/e8_WJ65xDWA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<h2>埋め込める情報一覧</h2>

<h3>旅行・ホテル情報系</h3>
<ul>
<li><?php
if ($rakutentoken=="" and $rakutenaffid=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "楽天トラベル（楽天アフィリエイト使用）"
?><br>
    楽天トラベルのAPIからホテル・旅館など宿泊施設の検索をして、その詳細情報を表示するショートコードを埋め込みます。</li>
<li><?php
if ($jalan_webservice_key=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "じゃらん　※LinkSwitchでバリューコマースアフィリエイト使用可"
?><br>
    じゃらんのAPIからホテル・旅館など宿泊施設の検索をして、その詳細情報を表示するショートコードを埋め込みます。</li> 
</ul>

<h3>飲食店情報系</h3>
<ul>
<li><?php
if ($recruit_webservice_key=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "HOT PEPPER　※LinkSwitchでバリューコマースアフィリエイト使用可"
?><br>
    ホットペッパーのAPIからレストラン・居酒屋など飲食店情報を検索して、その詳細情報を表示するショートコードを埋め込みます。</li> 
<li><?php
if ($gnavi_webservice_key=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "ぐるなび"
?><br>
    ぐるなびのAPIからレストラン・居酒屋など飲食店情報を検索して、その詳細情報を表示するショートコードを埋め込みます。</li> 
</ul>


<h3>その他スポット</h3>
<ul>
<li><?php
if ($lockets_gmap_apikey=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "Google プレイス"
?><br>
    Googleプレイスに登録されているスポットを検索し、その詳細情報を表示するショートコードを埋め込みます。詳細情報で出力する内容は管理画面のテンプレートで編集できます。</li>
</ul>
    
<h3>その他アフィリエイト機能</h3>
<ul>
<li><?php
if ($valuecommerce_pid=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "バリューコマース LinkSwitch<br>LinkSwitchに必要なJavaScriptを自動的に挿入します。<br>HOTPEPPERやじゃらんなど対応ECサイトと提携していると上記リンクが自動的にバリューマースのアフィリエイトリンクに過去記事も含めて置き換わります。"
?></li>
    
</ul>
    
<h3>商品情報アフィリエイト</h3>
<ul>
<li><?php
if ($rakutentoken=="" and $rakutenaffid=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "楽天市場（楽天アフィリエイト）"
?><br>
    楽天市場の商品を検索してアフィリエイトリンク付きで商品表示します。</li>
<li><?php
if ($lockets_amzacckey=="" and $lockets_amzseckey=="" and $lockets_amzassid=="") {echo '<span style="color:#AA0000;font-weight:bold;">[未設定]</span>';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[設定済]</span>';}
echo "Amazonアソシエイト"
?><br>
    Amazon.co.jpの商品を検索してアソシエイトリンク付きで商品表示します。</li>
    </ul>
    
<p>※[設定済][未設定]はAPIキーの入力のみのチェックです。HTMLテンプレートは編集しなくても動作します。<br>
    Google Maps表示は各スポット検索と連携して使用出来ますが、Googleプレイスは設定が必要です。</p>

    

</div>

<?php
}



/***------------------------------------------
　各種登録
------------------------------------------***/
// ショートコード登録
add_shortcode( 'LocketsRakutenTravel', 'lockets_rakuten_travel_func' );
add_shortcode( 'LocketsJalan', 'lockets_jalan_func' );
add_shortcode( 'LocketsHotpepper', 'lockets_hotpepper_func' );
add_shortcode( 'LocketsGurunavi', 'lockets_gurunavi_func' );
add_shortcode( 'LocketsGMaps', 'lockets_gmaps_func' );
add_shortcode( 'LocketsRakutenItem', 'lockets_rekuten_item_func' );
add_shortcode( 'LocketsValuecommerceItem', 'lockets_valuecommerce_item_func' );
add_shortcode( 'LocketsAmazonItem', 'lockets_amazon_item_func' );

//管理画面登録
add_action('admin_menu', 'lockets_menu');

// ヘッダーに追加
add_action('wp_head','add_vc_automylink');

/***------------------------------------------
　メディアボタン設置
------------------------------------------***/
/* ボタン */
//media_buttons_contextフィルターフック
add_filter( "media_buttons_context", "lockets_media_buttons_context2");


//ボタン追加

function lockets_media_buttons_context2 ( $context ) {

 $context .= <<<EOS
    <a title='Lockets' href='media-upload.php?tab=locketsSearch&type=locketsSearch&TB_iframe=true&width=600&height=550' class='thickbox button'>Lockets</a>
EOS;
 return $context;
}


/* コンテンツ */
// ポップアップウインドウの作成
add_action( 'media_upload_locketsSearch',  'locketsSearch_wp_iframe' );
add_action( "admin_head-media-upload-popup", 'lockets_head');


// 外部配信
$lockets_feedswitch = get_option('lockets_feedswitch');
$lockets_feedurl = "do_feed_".get_option('lockets_feedurl');


if($lockets_feedswitch == "1"){
    function do_feed_lctfmt() {
        $feed_template = WP_PLUGIN_DIR . '/lockets/feeds/lctfmt.php';
        load_template( $feed_template );
    }
    add_action( $lockets_feedurl, 'do_feed_lctfmt');

}

?>