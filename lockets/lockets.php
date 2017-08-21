<?php
/*
Plugin Name: Lockets
Plugin URI: http://lockets.jp/
Description: A plug-in that gets information on spots such as shops and inns from various APIs and displays the latest information embedded in the blog.Also, This plugin will assist you such as creating affiliate links. お店や旅館などスポットに関する情報を各種APIから取得し、ブログ内に最新の情報を埋め込んで表示するプラグイン。また、アフィリエイトリンク作成支援を行います。
Author: wackey
Version: 0.07
Author URI: http://musilog.net/
License: GPL2
*/
/*  Copyright 2017 wackey (email : takashi.wakimura@gmail.com)

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
//各種class、関数の読み込み※現在は関数のみ
require_once("lockets_func.php");


/***------------------------------------------
　楽天トラベルホテル情報表示
------------------------------------------***/
// 楽天トラベルホテル単体表示
function lockets_rakuten_travel_func( $atts, $content = null ) {

$rakutentoken= get_option('rakuten_search_token');
$rakutenaffid= get_option('rakuten_affiliate_id');
$lockets_rakuten_travel_template=get_option('lockets_rakuten_travel_template');

// [LocketsRakutenTravel]属性情報取得
extract(shortcode_atts(array(
'hotelno' => null, ), $atts));

// リクエストURL
$rwsurl="https://app.rakuten.co.jp/services/api/Travel/HotelDetailSearch/20170426?applicationId=$rakutentoken&affiliateId=$rakutenaffid&format=xml&hotelNo=$hotelno&datumType=1";
//echo $rwsurl;//■テスト用
// キャッシュ有無確認
$Buff = get_transient( $hotelno );
if ( $Buff === false ) {
$options['ssl']['verify_peer']=false;
$options['ssl']['verify_peer_name']=false;
$Buff = file_get_contents($rwsurl,false, stream_context_create($options));
set_transient( $hotelno, $Buff, 3600 * 24 );
}

$xml = simplexml_load_string($Buff);
$hotelBasicInfo = $xml->hotels->hotel->hotelBasicInfo;
$hotelRatingInfo = $xml->hotels->hotel->hotelRatingInfo;


$lockets_rakuten_travel_template=str_replace('\\','',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設番号】',locketsh($hotelBasicInfo->hotelNo),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設名称】',locketsh($hotelBasicInfo->hotelName),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設情報ページURL】',locketsh($hotelBasicInfo->hotelInformationUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【宿泊プラン一覧ページURL】',locketsh($hotelBasicInfo->planListUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【ダイナミックパッケージ宿泊プラン一覧ページUR】',locketsh($hotelBasicInfo->dpPlanListUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【お客様の声ページURL】',locketsh($hotelBasicInfo->reviewUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設かな名称】',locketsh($hotelBasicInfo->hotelKanaName),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設特色】',locketsh($hotelBasicInfo->hotelSpecial),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【最安料金】', number_format(locketsh($hotelBasicInfo->hotelMinCharge)),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【緯度】',locketsh($hotelBasicInfo->latitude),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【経度】',locketsh($hotelBasicInfo->longitude),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【郵便番号】',locketsh($hotelBasicInfo->postalCode),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【住所１】',locketsh($hotelBasicInfo->address1),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【住所２】',locketsh($hotelBasicInfo->address2),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設電話番号】',locketsh($hotelBasicInfo->telephoneNo),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設へのアクセス】',locketsh($hotelBasicInfo->access),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【駐車場情報】',locketsh($hotelBasicInfo->parkingInformation),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【最寄駅名称】',locketsh($hotelBasicInfo->nearestStation),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設画像URL】',locketsh($hotelBasicInfo->hotelImageUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設画像サムネイルURL】',locketsh($hotelBasicInfo->hotelThumbnailUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【部屋画像URL】',locketsh($hotelBasicInfo->roomImageUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【部屋画像サムネイルURL】',locketsh($hotelBasicInfo->roomThumbnailUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【施設提供地図画像URL】',locketsh($hotelBasicInfo->hotelMapImageUrl),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【投稿件数】',locketsh($hotelBasicInfo->reviewCount),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【★の数（総合）】',locketsh($hotelBasicInfo->reviewAverage),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【お客さまの声（1件目）】',locketsh($hotelBasicInfo->userReview),$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【楽天ウェブサービスクレジットA】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_4936.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="49" height="36"/></a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【楽天ウェブサービスクレジットB】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_7052.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="70" height="52"/></a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【楽天ウェブサービスクレジットC】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_22121.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="221" height="21"/></a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【楽天ウェブサービスクレジットD】','<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank">Supported by 楽天ウェブサービス</a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->',$lockets_rakuten_travel_template);
$lockets_rakuten_travel_template=str_replace('【Google Maps埋め込み】','<iframe src="https://maps.google.co.jp/maps?q='.locketsh($hotelBasicInfo->hotelName).'&ll='.locketsh($hotelBasicInfo->latitude).','.locketsh($hotelBasicInfo->longitude).'&output=embed&t=m&z=14&hl=ja" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="100%" height="450"></iframe>',$lockets_rakuten_travel_template);
//メモ　その他の要素後日追加

/*   
$ret = '<img src="'.locketsh($hotelBasicInfo->hotelImageUrl).'" class="img-responsive"><br>';
$ret .= '<a href="'.locketsh($hotelBasicInfo->hotelInformationUrl).'">'.locketsh($hotelBasicInfo->hotelName).'</a><br>';
$ret .= '評価：<br>'.locketsh($hotelBasicInfo->reviewAverage).'<br>';
$ret .= '<a href="'.locketsh($hotelBasicInfo->planListUrl).'">プランはこちら</a><br>';
$ret .= '<a href="'.locketsh($hotelBasicInfo->reviewUrl).'">お客様の声はこちら</a><br>';
$ret .= '最安料金：'.locketsh($hotelBasicInfo->hotelMinCharge).'円〜<br>';
$ret .= 'アクセス方法：'.locketsh($hotelBasicInfo->access).'<br>';
$ret .= '最寄り駅：'.locketsh($hotelBasicInfo->nearestStation).'<br>';
$ret .= '駐車場情報：'.locketsh($hotelBasicInfo->parkingInformation).'<br>';
$ret .= '郵便番号：'.locketsh($hotelBasicInfo->postalCode).'<br>';
$ret .= '住所：<br>'.locketsh($hotelBasicInfo->address1).'<br>';
$ret .= locketsh($hotelBasicInfo->address2).'<br>';
$ret .= '<img src="'.locketsh($hotelBasicInfo->hotelMapImageUrl).'" class="img-responsive"><br>';
*/
return $lockets_rakuten_travel_template;

}


/***------------------------------------------
　ホットペッパー店舗情報表示
------------------------------------------***/
// ホットペッパー店舗情報単体表示
function lockets_hotpepper_func( $atts, $content = null ) {

$recruit_webservice_key= get_option('recruit_webservice_key');
$lockets_hotpepper_template= get_option('lockets_hotpepper_template');

// [LocketsHotpepper]属性情報取得
extract(shortcode_atts(array(
'shopid' => null, ), $atts));

// リクエストURL
$recruiturl="http://webservice.recruit.co.jp/hotpepper/gourmet/v1/?key=$recruit_webservice_key&id=$shopid&datum=world";
//echo $recruiturl;//■テスト用

// キャッシュ有無確認
$Buff = get_transient( $shopid );
if ( $Buff === false ) {
$Buff = file_get_contents($recruiturl);
set_transient( $shopid, $Buff, 3600 * 24 );
}

$xml = simplexml_load_string($Buff);
$shop = $xml->shop;

$lockets_hotpepper_template=str_replace('\\','',$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【お店ID】',locketsh($shop->id),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【掲載店名】',locketsh($shop->name),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ロゴ画像URL】',locketsh($shop->logo_image),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【掲載店名かな】',locketsh($shop->name_kana),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【住所】',locketsh($shop->address),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【最寄駅名】',locketsh($shop->station_name),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【緯度】',locketsh($shop->lat),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【経度】',locketsh($shop->lng),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【料金備考】',locketsh($shop->budget_memo),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【お店キャッチ】',locketsh($shop->catch),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【総席数】',locketsh($shop->capacity),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【交通アクセス】',locketsh($shop->access),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【店舗URL(PC)】',locketsh($shop->urls->pc),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【写真PC向けLサイズ】',locketsh($shop->photo->pc->l),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【写真PC向けMサイズ】',locketsh($shop->photo->pc->m),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【写真PC向けSサイズ】',locketsh($shop->photo->pc->s),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【営業時間】',locketsh($shop->open),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【定休日】',locketsh($shop->close),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【最大宴会収容人数】',locketsh($shop->party_capacity),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【WiFi 有無】',locketsh($shop->wifi),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ウェディング･二次会】',locketsh($shop->wedding),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【コース】',locketsh($shop->course),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【飲み放題】',locketsh($shop->free_drink),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【食べ放題】',locketsh($shop->free_food),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【個室】',locketsh($shop->private_room),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【掘りごたつ】',locketsh($shop->horigotatsu),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【座敷】',locketsh($shop->tatami),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【カード可】',locketsh($shop->card),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【禁煙席】',locketsh($shop->non_smoking),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【貸切可】',locketsh($shop->charter),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【携帯電話OK】',locketsh($shop->ktai),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【駐車場】',locketsh($shop->parking),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【バリアフリー】',locketsh($shop->barrier_free),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【その他設備】',locketsh($shop->other_memo),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ソムリエ】',locketsh($shop->sommelier),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【オープンエア】',locketsh($shop->open_air),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ライブ・ショー】',locketsh($shop->show),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【エンタメ設備】',locketsh($shop->equipment),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【カラオケ】',locketsh($shop->karaoke),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【バンド演奏可】',locketsh($shop->band),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【TV・プロジェクター】',locketsh($shop->tv),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【英語メニュー】',locketsh($shop->english),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ペット可】',locketsh($shop->pet),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【お子様連れ】',locketsh($shop->child),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【ランチ】',locketsh($shop->lunch),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【23時以降も営業】',locketsh($shop->midnight),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【備考】',locketsh($shop->shop_detail_memo),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【クーポンURL(PC)】',locketsh($shop->coupon_urls->pc),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【HOT PEPPERクレジットA】','<a href="http://webservice.recruit.co.jp/"><img src="http://webservice.recruit.co.jp/banner/hotpepper-s.gif" alt="ホットペッパー Webサービス" width="135" height="17" border="0" title="ホットペッパー Webサービス"></a>',$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【HOT PEPPERクレジットB】','<a href="http://webservice.recruit.co.jp/"><img src="http://webservice.recruit.co.jp/banner/hotpepper-m.gif" alt="ホットペッパー Webサービス" width="88" height="35" border="0" title="ホットペッパー Webサービス"></a>',$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【HOT PEPPERクレジットC】','Powered by <a href="http://webservice.recruit.co.jp/">ホットペッパー Webサービス</a>',$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【Google Maps埋め込み】','<iframe src="https://maps.google.co.jp/maps?q='.locketsh($shop->name).'&ll='.locketsh($shop->lat).','.locketsh($shop->lng).'&output=embed&t=m&z=16&hl=ja" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="100%" height="450"></iframe>',$lockets_hotpepper_template);
//抜けている項目は後日追加する

return $lockets_hotpepper_template;
}


/***------------------------------------------
　管理画面
------------------------------------------***/

// 管理画面サブメニュー用
require_once("admin_rakuten.php");
require_once("admin_hotpepper.php");
require_once("admin_affiliate.php");


// 管理画面メニュー作成関数
function lockets_menu() {
    add_menu_page('Lockets', 'Lockets', 8,__FILE__, 'lockets_options', WP_PLUGIN_URL.'/lockets/icon16.png');
    add_submenu_page(__FILE__, '楽天ウェブサービス', '楽天ウェブサービス', 8, "admin_rakuten", 'lockets_rws');
    add_submenu_page(__FILE__, 'リクルートWEBサービス', 'リクルートWEBサービス', 8, "admin_recruit_webservice", 'lockets_recruit_webservice');
    add_submenu_page(__FILE__, 'その他アフィリエイト', 'その他アフィリエイト', 8, "admin_affiliate", 'lockets_affiliate');
}


// 管理画面描画
function lockets_options() {
?>

<h2>Lockets設定画面</h2>

<h3>使い方</h3>
<p>まだシンプルな使い方しか出来ません。<br>
例えばショートコード<strong>[LocketsRakutenTravel hotelno="xxxxxxx"]</strong>みたいな感じでホテル番号を指定したらホテルの情報を外部から取得しブログ記事内で表示させます。</p>
<p>こちらはで動作環境の確認が出来ます。</p>
<?php
// simplexml_load_fileが存在するか否か
if (function_exists('simplexml_load_file')) {
    echo "simpleXML functions are available.<br />\n";
} else {
    echo "simpleXML functions are not available.<br />\n";
}
if (function_exists('get_transient')) {
    echo "get_transient functions are available.<br />\n";
} else {
    echo "get_transient functions are not available.<br />\n";
}
}



/***------------------------------------------
　各種登録
------------------------------------------***/
// ショートコード登録
add_shortcode( 'LocketsRakutenTravel', 'lockets_rakuten_travel_func' );
add_shortcode( 'LocketsHotpepper', 'lockets_hotpepper_func' );

//管理画面登録
add_action('admin_menu', 'lockets_menu');

// ヘッダーに追加
add_action('wp_head','add_vc_automylink');


// アンインストール
register_deactivation_hook(__FILE__, 'remove_lockets');


/***------------------------------------------
　プラグインアンインストール
------------------------------------------***/
function remove_lockets()
{
	delete_option('rakuten_affiliate_id');
	delete_option('rakuten_search_token');
    delete_option('smartformaturl');
    delete_option('valuecommerce_pid');
}

/***------------------------------------------
　オートMylink追加
------------------------------------------***/
function add_vc_automylink() {
    $vc_pid= get_option('valuecommerce_pid');
    if ($vc_pid!=="") {
        echo '<script type="text/javascript" language="javascript">';
        echo '    var vc_pid = "'.locketsh(stripslashes($vc_pid)).'";';
        echo '</script><script type="text/javascript" src="//aml.valuecommerce.com/vcdal.js" async></script>';
    }
}



/***------------------------------------------
　メディアボタン設置
------------------------------------------***/
$locketsmediabutton = new locketsEditerMediaButton();
class locketsEditerMediaButton
{
	public function __construct(){
		add_filter( "media_buttons_context" , array( &$this, "lockets_media_buttons_context" ) );
		//ポップアップウィンドウ
		//media_upload_{ $type }
		add_action('media_upload_locketsType', array( &$this,'lockets_wp_iframe' ) );
		add_action( "admin_head-media-upload-popup", array( &$this, "lockets_head" ) );
	}
	public function lockets_head(){
		global $type;
		if( $type == "locketsType" ){
		echo <<< EOS
			<script type="text/javascript">
			jQuery(function($) {
		
				$(document).ready(function() {
					$('#locketsHotpepper_ei_btn_yes').on('click', function() {
						var str = $('#locketsHotpepper_editer_insert_content').val();
						//inlineのときはwindow
						top.send_to_editor( '[LocketsHotpepper shopid="' + str + '"]');
						top.tb_remove(); 
					});
					$('#lockets_ei_btn_no').on('click', function() {
						top.tb_remove(); 
					});
					
					//Enterキーが入力されたとき
					$('#locketsHotpepper_editer_insert_content').on('keypress',function () {
						if(event.which == 13) {
							$('#locketsHotpepper_ei_btn_yes').trigger("click");
						}
						//Form内のエンター：サブミット回避
						return event.which !== 13;
					});
				});
			})
			</script>
EOS;
		}
	}
	//##########################
	//メディアボタンの表示
	//##########################
	public function lockets_media_buttons_context ( $context ) {
		$img = plugin_dir_url( __FILE__ ) ."icon16.png";
		$link = "media-upload.php?tab=locketsTabHotpepper&type=locketsType&TB_iframe=true&width=600&height=550";
		$context .= <<<EOS
    <a href='{$link}'
    class='thickbox' title='Lockets'>
      <img src='{$img}' /></a>
EOS;
		return $context;
	}
	//##########################
	//ポップアップウィンドウ
	//##########################
	function lockets_wp_iframe() {
		wp_iframe(array( $this , 'media_upload_lockets_form' ) );
	}

	function media_upload_lockets_form() {
		add_filter( "media_upload_tabs", array( &$this, "lockets_upload_tabs" ) ,1000);
		media_upload_header();
		echo <<< EOS
			<div id="locketsHotpepper_popup_window" >
			<form  action="">
				<h2>HOT PEPPER</h2>
				<p>
				<input type="text" id="locketsHotpepper_editer_insert_content" value="" />
				</p>
				<input type="button" value="OK" id="locketsHotpepper_ei_btn_yes" class="button button-primary" /> 
				<input type="button" value="キャンセル" id="locketsHotpepper_ei_btn_no"  class="button" />
			</form>
			</div>
EOS;
	}
	//##########################
	//ポップアップウィンドウのタブ
	//##########################
	function lockets_upload_tabs( $tabs )
	{
		$tabs = array();
		$tabs[ "locketsTabHotpepper" ] = "HOT PEPPER" ;
        //$tabs[ "locketsTabRakutenTravel" ] = "楽天トラベル" ;
		return $tabs;
	}
}



?>