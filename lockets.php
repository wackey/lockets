<?php
/*
Plugin Name: Lockets
Plugin URI: http://lockets.jp/
Description: A plug-in that gets information on spots such as shops and inns from various APIs and displays the latest information embedded in the blog.Also, This plugin will assist you such as creating affiliate links. お店や旅館などスポットに関する情報を各種APIから取得し、ブログ内に最新の情報を埋め込んで表示するプラグイン。また、アフィリエイトリンク作成支援を行います。
Author: wackey
Version: 0.34
Author URI: htp://musilog.net/
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
require_once("lockets_func.php");
require_once("lockets_class.php");


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
    'hotelno' => null,
    'zoom' => null,
    'width' => null,
    'height' => null,), $atts));

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

//デフォルトテンプレートの登録
if ($lockets_rakuten_travel_template=="") {
$lockets_rakuten_travel_template= <<<EOT
<p><strong>【施設名称】</strong></p>
<p><a href="【施設情報ページURL】" rel="nofollow"><img src="【施設画像サムネイルURL】"> <img src="【部屋画像サムネイルURL】"></a></p>
<p>【施設特色】<br>
【郵便番号】<br>
【住所１】【住所２】</p>
<p><a href="【宿泊プラン一覧ページURL】" target="_blank" rel="nofollow">宿泊プランはこちら</a></p>
【Google Maps埋め込み】
<p>【楽天ウェブサービスクレジットA】</p>
EOT;
}
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
$gmap = lockets_gmap_draw(locketsh($hotelBasicInfo->hotelName),locketsh($hotelBasicInfo->latitude),locketsh($hotelBasicInfo->longitude),$zoom,$width,$height);
$lockets_rakuten_travel_template=str_replace('【Google Maps埋め込み】',$gmap,$lockets_rakuten_travel_template);
//メモ　その他の要素後日追加

return $lockets_rakuten_travel_template;

}


/***------------------------------------------
　じゃらんホテル情報表示
------------------------------------------***/
// じゃらんホテル単体表示
function lockets_jalan_func( $atts, $content = null ) {

$jalan_webservice_key= get_option('jalan_webservice_key');
$lockets_jalan_template= get_option('lockets_jalan_template');

// [LocketsJalan]属性情報取得
extract(shortcode_atts(array(
    'hotelno' => null,
    'zoom' => null,
    'width' => null,
    'height' => null,), $atts));

// リクエストURL
$jalanurl="http://jws.jalan.net/APIAdvance/HotelSearch/V1/?key=$jalan_webservice_key&h_id=$hotelno";

// キャッシュ有無確認
$Buff = get_transient($jalanurl);
if ( $Buff === false ) {
    $Buff = file_get_contents($jalanurl);
    set_transient($jalanurl, $Buff, 3600 * 24 );
}

$xml = @simplexml_load_string($Buff);//warning防止
$jalanhotel = $xml->Hotel;

//デフォルトテンプレートの登録
if ($lockets_jalan_template=="") {
$lockets_jalan_template= <<<EOT
<p><strong>【宿名漢字】</strong></p>
<p><a href="【宿詳細ページURL】" rel="nofollow"><img src="【宿画像URL】"></a></p>
<p>【宿画像キャプション】<br>
【郵便番号】<br>
【住所】</p>
<p>【じゃらんクレジットA】</p>
EOT;
}
$lockets_jalan_template=str_replace('\\','',$lockets_jalan_template);
$lockets_jalan_template=str_replace('【宿番号】',locketsh($jalanhotel->HotelID),$lockets_jalan_template);
$lockets_jalan_template=str_replace('【宿名漢字】',locketsh($jalanhotel->HotelName),$lockets_jalan_template);
$lockets_jalan_template=str_replace('【郵便番号】',locketsh($jalanhotel->PostCode	),$lockets_jalan_template);
$lockets_jalan_template=str_replace('【住所】',locketsh($jalanhotel->HotelAddress	),$lockets_jalan_template);
    
$lockets_jalan_template=str_replace('【宿詳細ページURL】',locketsh($jalanhotel->HotelDetailURL),$lockets_jalan_template);

$lockets_jalan_template=str_replace('【キャッチ】',locketsh($jalanhotel->HotelCatchCopy),$lockets_jalan_template);
$lockets_jalan_template=str_replace('【コピー】',locketsh($jalanhotel->HotelCaption),$lockets_jalan_template);

$lockets_jalan_template=str_replace('【宿画像URL】',locketsh($jalanhotel->PictureURL),$lockets_jalan_template);
$lockets_jalan_template=str_replace('【宿画像キャプション】',locketsh($jalanhotel->PictureCaption),$lockets_jalan_template);

$lockets_jalan_template=str_replace('【参考料金】',locketsh($jalanhotel->SampleRateFrom),$lockets_jalan_template);


$lockets_jalan_template=str_replace('【じゃらんクレジットA】','<a href="http://www.jalan.net/jw/jwp0000/jww0001.do"><img src="http://www.jalan.net/jalan/doc/jws/images/jws_88_50_blue.gif" alt="じゃらん Web サービス" title="じゃらん Web サービス" border="0"></a>',$lockets_jalan_template);
$lockets_jalan_template=str_replace('【じゃらんクレジットB】','<a href="http://www.jalan.net/jw/jwp0000/jww0001.do"><img src="http://www.jalan.net/jalan/doc/jws/images/jws_88_50_gray.gif" alt="じゃらん Web サービス" title="じゃらん Web サービス" border="0"></a>',$lockets_jalan_template);
$lockets_jalan_template=str_replace('【じゃらんクレジットC】','<a href="http://www.jalan.net/jw/jwp0000/jww0001.do">じゃらん Web サービス</a>',$lockets_jalan_template);

//Google Mapsは緯度経度変換のロジックを載せてから対応する 
//$gmap = lockets_gmap_draw(locketsh($hotelBasicInfo->hotelName),locketsh($hotelBasicInfo->latitude),locketsh($hotelBasicInfo->longitude),$zoom,$width,$height);
//$lockets_jalan_template=str_replace('【Google Maps埋め込み】',$gmap,$lockets_jalan_template);
//メモ　その他の要素後日追加

return $lockets_jalan_template;

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
'shopid' => null,
'zoom' => null,
'width' => null,
'height' => null,), $atts));

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

//デフォルトテンプレートの登録
if ($lockets_hotpepper_template=="") {
$lockets_hotpepper_template= <<<EOT
<p><strong><a href="【店舗URL(PC)】">【掲載店名】</a></strong><p>
<p>【お店キャッチ】</p>
<p><img src="【写真PC向けLサイズ】 " class="img-responsive"></p>
<p>住所：【住所】</p>
<p>交通アクセス：【交通アクセス】</p>
<p>営業時間：【営業時間】</p>
<p>定休日：【定休日】</p>

【Google Maps埋め込み】
<p>【HOT PEPPERクレジットA】</p>
EOT;
}

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
//$lockets_hotpepper_template=str_replace('【クーポンURL(PC)】',locketsh($shop->coupon_urls->pc),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【クーポンURL(PC)】',locketsh($shop->urls->pc),$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【HOT PEPPERクレジットA】','<a href="http://webservice.recruit.co.jp/"><img src="http://webservice.recruit.co.jp/banner/hotpepper-s.gif" alt="ホットペッパー Webサービス" width="135" height="17" border="0" title="ホットペッパー Webサービス"></a>',$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【HOT PEPPERクレジットB】','<a href="http://webservice.recruit.co.jp/"><img src="http://webservice.recruit.co.jp/banner/hotpepper-m.gif" alt="ホットペッパー Webサービス" width="88" height="35" border="0" title="ホットペッパー Webサービス"></a>',$lockets_hotpepper_template);
$lockets_hotpepper_template=str_replace('【HOT PEPPERクレジットC】','Powered by <a href="http://webservice.recruit.co.jp/">ホットペッパー Webサービス</a>',$lockets_hotpepper_template);
$gmap = lockets_gmap_draw(locketsh($shop->name),locketsh($shop->lat),locketsh($shop->lng),$zoom,$width,$height);
$lockets_hotpepper_template=str_replace('【Google Maps埋め込み】',$gmap,$lockets_hotpepper_template);
//抜けている項目は後日追加する

return $lockets_hotpepper_template;
}

/***------------------------------------------
　ぐるなび店舗情報表示
------------------------------------------***/
// ぐるなび店舗情報単体表示
function lockets_gurunavi_func( $atts, $content = null ) {

$gnavi_webservice_key= get_option('gnavi_webservice_key');
$lockets_gnavi_template= get_option('lockets_gnavi_template');

// [LocketsGurunavi]属性情報取得
extract(shortcode_atts(array(
'shopid' => null,
'zoom' => null,
'width' => null,
'height' => null,), $atts));

// リクエストURL
$gurunaviurl="https://api.gnavi.co.jp/RestSearchAPI/20150630/?keyid=$gnavi_webservice_key&format=xml&id=$shopid&coordinates_mode=2";

// キャッシュ有無確認
$Buff = get_transient( $shopid );
if ( $Buff === false ) {
$Buff = file_get_contents($gurunaviurl);
set_transient( $shopid, $Buff, 3600 * 24 );
}

$xml = simplexml_load_string($Buff);
$shop = $xml->rest;

//デフォルトテンプレートの登録
if ($lockets_gnavi_template=="") {
$lockets_gnavi_template= <<<EOT
<p><strong><a href="【店舗URL(PC)】">【掲載店名】</a></strong><p>
<p>【PR文（短:最大50文字）】</p>
<p><img src="【店舗画像1のURL】" class="img-responsive"></p>
<p>住所：【住所】</p>
<p>交通アクセス：【最寄駅名】</p>
<p>営業時間：【営業時間】</p>
<p>定休日：【休業日】</p>

【Google Maps埋め込み】
<p>【ぐるなびクレジットA】</p>
EOT;
}

$lockets_gnavi_template=str_replace('\\','',$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【お店ID】',locketsh($shop->id),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【情報更新日時】',locketsh($shop->update_date),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【掲載店名】',locketsh($shop->name),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【掲載店名かな】',locketsh($shop->name_kana),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【緯度】',locketsh($shop->latitude),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【経度】',locketsh($shop->longitude),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【フリーワードカテゴリー】',locketsh($shop->category),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【店舗URL(PC)】',locketsh($shop->url),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【クーポンURL(PC)】',locketsh($shop->coupon_url->pc),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【店舗画像1のURL】',locketsh($shop->image_url->shop_image1),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【店舗画像2のURL】',locketsh($shop->image_url->shop_image2),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【QRコード】',locketsh($shop->image_url->qrcode),$lockets_gnavi_template);


$lockets_gnavi_template=str_replace('【住所】',locketsh($shop->address),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【電話番号】',locketsh($shop->tel),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【営業時間】',locketsh($shop->opentime),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【休業日】',locketsh($shop->holiday),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【最寄駅名】',locketsh($shop->access->line).locketsh($shop->access->station),$lockets_gnavi_template);

$lockets_gnavi_template=str_replace('【平均予算】',locketsh($shop->budget),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【宴会・パーティ平均予算】',locketsh($shop->party),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【ランチタイム平均予算】',locketsh($shop->lunch),$lockets_gnavi_template);


$lockets_gnavi_template=str_replace('【PR文（短:最大50文字）】',locketsh($shop->pr->pr_short),$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【PR文（長:最大200文字）】',locketsh($shop->pr->pr_long),$lockets_gnavi_template);


$lockets_gnavi_template=str_replace('【ぐるなびクレジットA】','<a href="http://api.gnavi.co.jp/api/scope/" target="_blank"><img src="http://api.gnavi.co.jp/api/img/credit/api_155_20.gif" width="155" height="20" border="0" alt="グルメ情報検索サイト　ぐるなび"></a>',$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【ぐるなびクレジットB】','<a href="http://api.gnavi.co.jp/api/scope/" target="_blank"><img src="http://api.gnavi.co.jp/api/img/credit/api_90_35.gif" width="90" height="35" border="0" alt="グルメ情報検索サイト　ぐるなび"></a>',$lockets_gnavi_template);
$lockets_gnavi_template=str_replace('【ぐるなびクレジットC】','Supported by <a href="http://api.gnavi.co.jp/api/scope/" target="_blank">ぐるなびWebService</a>',$lockets_gnavi_template);
$gmap = lockets_gmap_draw(locketsh($shop->name),locketsh($shop->latitude),locketsh($shop->longitude),$zoom,$width,$height);
$lockets_gnavi_template=str_replace('【Google Maps埋め込み】',$gmap,$lockets_gnavi_template);
//抜けている項目は後日追加する

return $lockets_gnavi_template;
}

                                    
/***------------------------------------------
　Google Maps表示機能
------------------------------------------***/
// ショートコード呼び出し
function lockets_gmaps_func ( $atts, $content = null ) {

    // [LocketsGMaps]属性情報取得
    extract(shortcode_atts(array(
        'keyword' => null,
        'lat' => null,
        'lng' => null,
        'zoom' => null,
        'width' => null,
        'height' => null,), $atts));

    $ret= lockets_gmap_draw($keyword,$lat,$lng,$zoom,$width,$height);
    return $ret;
}

// 関数呼び出し
function lockets_gmap_draw($keyword,$lat,$lng,$zoom,$width,$height) {
    $lockets_gmap_zoom= get_option('lockets_gmap_zoom');
    $lockets_gmap_width= get_option('lockets_gmap_width');
    $lockets_gmap_height= get_option('lockets_gmap_height');
    if ($zoom == "") {
        if ($lockets_gmap_zoom == "") {$zoom="14";} else {$zoom=$lockets_gmap_zoom;}
        }
    if ($width == "") {
        if ($lockets_gmap_width == "") {$width="100%";} else {$width=$lockets_gmap_width;}
        }
    if ($height == "") {
        if ($lockets_gmap_height == "") {$height="450";} else {$height=$lockets_gmap_height;}
        }
    $ret = '<iframe src="https://maps.google.co.jp/maps?q='.$keyword.'&ll='.$lat.','.$lng.'&output=embed&t=m&z='.$zoom.'&hl=ja" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="'.$width.'" height="'.$height.'"></iframe>';
    return $ret;
}


/***------------------------------------------
　管理画面
------------------------------------------***/

// 管理画面サブメニュー用
require_once("admin_rakuten.php");
require_once("admin_hotpepper.php");
require_once("admin_gnavi.php");
require_once("admin_jalan.php");
require_once("admin_affiliate.php");
require_once("admin_gmap.php");
require_once("gnavi_search.php");


// 管理画面メニュー作成関数
function lockets_menu() {
    add_menu_page('Lockets', 'Lockets', 8,__FILE__, 'lockets_options', WP_PLUGIN_URL.'/lockets/icon16.png');
    add_submenu_page(__FILE__, '楽天ウェブサービス', '楽天ウェブサービス', 8, "admin_rakuten", 'lockets_rws');
    add_submenu_page(__FILE__, 'リクルートWEBサービス', 'リクルートWEBサービス', 8, "admin_recruit_webservice", 'lockets_recruit_webservice');
    add_submenu_page(__FILE__, 'ぐるなびWebサービス', 'ぐるなびWebサービス', 8, "admin_gnavi_webservice", 'lockets_gnavi_webservice');
    add_submenu_page(__FILE__, 'じゃらんWebサービス', 'じゃらんWebサービス', 8, "admin_jalan_webservice", 'lockets_jalan_webservice');
    add_submenu_page(__FILE__, 'その他アフィリエイト', 'その他アフィリエイト', 8, "admin_affiliate", 'lockets_affiliate');
    add_submenu_page(__FILE__, 'Google Maps表示設定', 'Google Maps表示設定', 8, "admin_gmap", 'lockets_gmap');
        add_submenu_page(__FILE__, '暫定ぐるなびID検索', '暫定ぐるなびID検索', 8, "gnavi_search", 'lockets_gnavi_searchbox');
}


// 管理画面描画
function lockets_options() {
    $rakutentoken= get_option('rakuten_search_token');
    $rakutenaffid= get_option('rakuten_affiliate_id');
    $recruit_webservice_key= get_option('recruit_webservice_key');
?>

<h2>Lockets設定画面</h2>

<h3>使い方</h3>
<p>例えばショートコード<strong>[LocketsRakutenTravel hotelno="xxxxxxx"]</strong>のような感じでホテル番号を指定したらホテルの情報を外部から取得しブログ記事内で表示させます。<br>
これらのショートコードは投稿画面の「Lockets」ボタンを押して表示されるパネルにホテル番号やお店IDなどを入れることで簡単に記事中に挿入できます。</p>
<p>こちらのページでは設定状況や使える機能の確認が出来ます。</p>


<h4>楽天ウェブサービス(RAKUTEN WEBSERVICE)</h4>
<p><?php
if ($rakutentoken=="" and $rakutenaffid=="") {echo '<span style="color:red:font-weight:bold;">[NG]</span>楽天ウェブサービス(RAKUTEN WEBSERVICE)の設定がされていません。';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[OK]</span>楽天ウェブサービス(RAKUTEN WEBSERVICE)の設定がされています。';}
?> 
</p>
<p>楽天ウェブサービス(RAKUTEN WEBSERVICE)の設定をすると以下の機能が使用出来ます。</p>
<ul>
    <li>[LocketsRakutenTravel hotelno="xxxxxxx"]<br>
    楽天トラベルの施設（ホテル・旅館等）詳細情報表示ができます。</li>
</ul>


<h4>リクルート WEBサービス</h4>
<p><?php
if ($recruit_webservice_key=="") {echo '<span style="color:red:font-weight:bold;">[NG]</span>リクルート WEBサービスの設定がされていません。';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[OK]</span>リクルート WEBサービスの設定がされています。';}
?> 
</p>
<p>リクルート WEBサービスの設定をすると以下の機能が使用出来ます。</p>
<ul>
    <li>[LocketsHotpepper shopno="xxxxxxx"]<br>
    ホットペッパー（HOT PEPPER）の飲食店詳細情報表示ができます。</li>
</ul>


<h4>じゃらんWebサービス</h4>
<p><?php
if ($jalan_webservice_key=="") {echo '<span style="color:red:font-weight:bold;">[NG]</span>じゃらんWebサービスの設定がされていません。';} else {echo '<span style="color:#00AA00;:font-weight:bold;">[OK]</span>じゃらんWebサービスの設定がされています。';}
?> 
</p>
<p>じゃらんWebサービスの設定をすると以下の機能が使用出来ます。</p>
<ul>
    <li>[LocketsJalan hotelno="xxxxxxx"]<br>
    じゃらんの宿詳細情報表示ができます。</li>
</ul>
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
    delete_option('lockets_rakuten_travel_template');

    delete_option('jalan_webservice_key');
    delete_option('lockets_jalan_template');
    
    delete_option('valuecommerce_pid');
    
    delete_option('recruit_webservice_key');
    delete_option('lockets_hotpepper_template');
    
    delete_option('gnavi_webservice_key');
    delete_option('lockets_gnavi_template');
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
/* ボタン */
//media_buttons_contextフィルターフック
add_filter( "media_buttons_context", "lockets_media_buttons_context");

//ボタン追加
function lockets_media_buttons_context ( $context ) {

 $context .= <<<EOS
    <a title='Lockets' href='media-upload.php?tab=locketsHotpepper&type=locketsType1&TB_iframe=true&width=600&height=550' class='thickbox button'>Lockets</a>
EOS;
 return $context;
}

/* コンテンツ */
// ポップアップウインドウの作成
add_action( 'media_upload_locketsType1',  'lockets1_wp_iframe' );
add_action( "admin_head-media-upload-popup", 'lockets1_head');

function lockets1_wp_iframe() {
        wp_iframe( media_upload_lockets1_form );
}

//検索インタフェース用
function media_upload_lockets2_form() {
	add_filter( "media_upload_tabs", "lockets1_upload_tabs"  ,1000);
	media_upload_header();
    
echo <<< EOS
<div id="test">
			<form action="">
				<h2>検索窓</h2>
				<p>
				<input type="text" id= value="" /><br>
				</p>
				<input type="submit" value="OK" class="button button-primary" /> 
			</form>
</div>
EOS;
}

//コンテンツ
function media_upload_lockets1_form() {
	add_filter( "media_upload_tabs", "lockets1_upload_tabs"  ,1000);
	media_upload_header();
    
echo <<< EOS
<div id="locketsHotpepper">
			<form  action="">
				<h2>HOT PEPPER</h2>
				<p>
				<input type="text" id="locketsHotpepper_editer_insert_content" value="" /><br>
                ホットペッパーのお店URLに含まれている「strJxxxxxxxxx」のうち「Jxxxxxxxxx」（xは数字）を入力してください。
				</p>
				<input type="button" value="OK" id="locketsHotpepper_ei_btn_yes" class="button button-primary" /> 
				<input type="button" value="キャンセル" id="locketsHotpepper_ei_btn_no"  class="button" />
			</form>
</div>
<div id="locketsGurunavi">
			<form  action="">
				<h2>ぐるなび</h2>
				<p>
				<input type="text" id="locketsGurunavi_editer_insert_content" value="" /><br>
                Lockets管理画面「暫定ぐるなび検索」で表示された店舗IDを入力してください。
				</p>
				<input type="button" value="OK" id="locketsGurunavi_ei_btn_yes" class="button button-primary" /> 
				<input type="button" value="キャンセル" id="locketsGurunavi_ei_btn_no"  class="button" />
			</form>
</div>
<div id="locketsRakutenTravel">
			<form  action="">
				<h2>楽天トラベル</h2>
				<p>
				<input type="text" id="locketsRakutenTravel_editer_insert_content" value="" /><br>
                楽天トラベルのホテルURLに含まれている「https://travel.rakuten.co.jp/HOTEL/xxxxx/」のうち「xxxxx」（xは数字）を入力してください。
				</p>
				<input type="button" value="OK" id="locketsRakutenTravel_ei_btn_yes" class="button button-primary" /> 
				<input type="button" value="キャンセル" id="locketsRakutenTravel_ei_btn_no"  class="button" />
			</form>
</div>
<div id="locketsJalan">
			<form  action="">
				<h2>じゃらん</h2>
				<p>
				<input type="text" id="locketsJalan_editer_insert_content" value="" /><br>
                じゃらんのホテルURLに含まれている「http://www.jalan.net/yadxxxxxx/」のうち「xxxxxx」（xは数字）を入力してください。
				</p>
				<input type="button" value="OK" id="locketsJalan_ei_btn_yes" class="button button-primary" /> 
				<input type="button" value="キャンセル" id="locketsJalan_ei_btn_no"  class="button" />
			</form>
</div>
<div id="locketsGMaps">
			<form  action="">
				<h2>Google Maps</h2>
				<p>
				<input type="text" id="locketsGMaps_editer_insert_content" value="" /><br>
                Google Mapsに存在するスポット名称を入れて下さい。
				</p>
				<input type="button" value="OK" id="locketsGMaps_ei_btn_yes" class="button button-primary" /> 
				<input type="button" value="キャンセル" id="locketsGMaps_ei_btn_no"  class="button" />
			</form>
</div>
EOS;
}

// jQuery
function lockets1_head(){
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
			<script type="text/javascript">
			jQuery(function($) {
		
				$(document).ready(function() {
					$('#locketsGurunavi_ei_btn_yes').on('click', function() {
						var str = $('#locketsGurunavi_editer_insert_content').val();
						//inlineのときはwindow
						top.send_to_editor( '[LocketsGurunavi shopid="' + str + '"]');
						top.tb_remove(); 
					});
					$('#lockets_ei_btn_no').on('click', function() {
						top.tb_remove(); 
					});
					
					//Enterキーが入力されたとき
					$('#locketsGurunavi_editer_insert_content').on('keypress',function () {
						if(event.which == 13) {
							$('#locketsGurunavi_ei_btn_yes').trigger("click");
						}
						//Form内のエンター：サブミット回避
						return event.which !== 13;
					});
				});
			})
			</script>
            			<script type="text/javascript">
			jQuery(function($) {
		
				$(document).ready(function() {
					$('#locketsRakutenTravel_ei_btn_yes').on('click', function() {
						var str = $('#locketsRakutenTravel_editer_insert_content').val();
						//inlineのときはwindow
						top.send_to_editor( '[LocketsRakutenTravel hotelno="' + str + '"]');
						top.tb_remove(); 
					});
					$('#locketsRakutenTravel_ei_btn_no').on('click', function() {
						top.tb_remove(); 
					});
					
					//Enterキーが入力されたとき
					$('#locketsRakutenTravel_editer_insert_content').on('keypress',function () {
						if(event.which == 13) {
							$('#locketsRakutenTravel_ei_btn_yes').trigger("click");
						}
						//Form内のエンター：サブミット回避
						return event.which !== 13;
					});
				});
			})
			</script>
            			<script type="text/javascript">
			jQuery(function($) {
		
				$(document).ready(function() {
					$('#locketsGMaps_ei_btn_yes').on('click', function() {
						var str = $('#locketsGMaps_editer_insert_content').val();
						//inlineのときはwindow
						top.send_to_editor( '[LocketsGMaps keyword="' + str + '"]');
						top.tb_remove(); 
					});
					$('#locketsGMaps_ei_btn_no').on('click', function() {
						top.tb_remove(); 
					});
					
					//Enterキーが入力されたとき
					$('#locketsGMaps_editer_insert_content').on('keypress',function () {
						if(event.which == 13) {
							$('#locketsGMaps_ei_btn_yes').trigger("click");
						}
						//Form内のエンター：サブミット回避
						return event.which !== 13;
					});
				});
			})
			</script>
            <script type="text/javascript">
			jQuery(function($) {
		
				$(document).ready(function() {
					$('#locketsJalan_ei_btn_yes').on('click', function() {
						var str = $('#locketsJalan_editer_insert_content').val();
						//inlineのときはwindow
						top.send_to_editor( '[LocketsJalan hotelno="' + str + '"]');
						top.tb_remove(); 
					});
					$('#locketsJalan_ei_btn_no').on('click', function() {
						top.tb_remove(); 
					});
					
					//Enterキーが入力されたとき
					$('#locketsJalan_editer_insert_content').on('keypress',function () {
						if(event.which == 13) {
							$('#locketsJalan_ei_btn_yes').trigger("click");
						}
						//Form内のエンター：サブミット回避
						return event.which !== 13;
					});
				});
			})
			</script>
EOS;
		}


//　タブ
function lockets1_upload_tabs( $tabs )
{
	$tabs=array();
	$tabs[ "locketsHotpepper" ] = "Lockets" ;
	return $tabs;
}

?>