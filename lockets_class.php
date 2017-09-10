<?php
/* class */

//ここにclass化して書いて行く（まだ準備中）
//例えばテンプレートの処理などまとめてみる



/* VC Search by wackey */

/***----------------------------------------------------
　APIアクセス時の参照データ、リクエストURL組み立て補助
----------------------------------------------------***/

// ■バリューコマース商品検索API

class lockets_valuecommerceapi {

// ソート順番を配列に代入
public $arr_sorts = array(
	"0" => "スコア順", 
	"1" => "価格の高い順", 
	"2" => "価格の安い順" ,
	"3" => "今日の売れ筋順", 
	"4" => "週間の売れ筋順" ,
	"5" => "月間の売れ筋順" 
    );

// カテゴリーを配列に代入
public $arr_categories = array(
	""         => "すべてのカテゴリー", 
	"computers"         => "コンピュータ", 
	"electronics"       => "家電、AV機器", 
	"music"             => "音楽、CD", 
	"books"             => "本、コミック", 
	"dvd"               => "DVD", 
	"fooddrink"         => "フード、ドリンク", 
	"fashion"           => "ファッション、アクセサリー", 
	"beautys"           => "美容、健康", 
	"toysgameshobbies"  => "おもちゃ、ホビー", 
	"recreationoutdoor" => "レジャー、アウトドア", 
	"homeinterior"      => "生活、インテリア", 
	"babymaternity"     => "ベビー、キッズ、マタニティ", 
	"officesupplies"    => "ビジネス、ステーショナリー"
    );

function vcsort ($v_sort_type,&$v_sort_by,&$v_sort_order,&$v_sort_rank) {
    // 並べ順の選択肢からパラメータを設定
    switch ($v_sort_type) {
    case 0:
	    $v_sort_by = "score";
	    $v_sort_order = "asc";
	    break;
    case 1: //価格安い順
	    $v_sort_by = "price";
	    $v_sort_order = "desc";
	    break;
    case 2: //価格高い順
	    $v_sort_by = "price";
	    $v_sort_order = "asc";
	    break;
    case 3: //今日の売れ筋順
	    $v_sort_rank = "dayly";
	    break;
    case 4: //週間の売れ筋順
	    $v_sort_rank = "weekly";
	    break;
    case 5: //月間の売れ筋順
	    $v_sort_rank = "monthly";
	    break;
    default:
	break;
    }
}

} //class lockets_valuecommerceapi end


class lockets_linkshareitemsearchapi {

// ソート順番を配列に代入
public $arr_sorts = array(
	"0" => "参考価格の安い順", 
	"1" => "参考価格の高い順"
    );

function linksheresort ($ls_sort_type,&$ls_sort_order) {
    // 並べ順の選択肢からパラメータを設定
	switch ($ls_sort_type) {
	case 0:
	$ls_sort_order = "asc";
	break;
	case 1: //価格安い順
	$ls_sort_order = "dsc";
	break;
	default:
	break;
	}
}

} //class lockets_linkshareitemsearchapi end

// ■楽天市場商品検索API

class lockets_rakutenichibaapi {

// ソート順番を配列に代入
public $arr_sorts = array(
	"0" => "楽天標準ソート順", 
	"3" => "レビュー件数順（昇順）", 
	"4" => "レビュー件数順（降順）" ,
	"5" => "価格順（昇順）", 
	"6" => "価格順（降順）" ,
	"7" => "商品更新日時順（昇順）", 
	"8" => "商品更新日時順（降順）" 
    );

// 管理画面用ソート順番を配列に代入
public $arr_sorts_admin = array(
	"0" => "楽天標準ソート順", 
	"1" => "アフィリエイト料率順（昇順）", 
	"2" => "アフィリエイト料率順（降順）", 
	"3" => "レビュー件数順（昇順）", 
	"4" => "レビュー件数順（降順）" ,
	"5" => "価格順（昇順）", 
	"6" => "価格順（降順）" ,
	"7" => "商品更新日時順（昇順）", 
	"8" => "商品更新日時順（降順）" 
    );

// カテゴリーを配列に代入
public $arr_categories = array(
	"0"=>"すべての商品",
	"101240"=>"CD・DVD・楽器",
	"100804"=>"インテリア・寝具・収納",
	"101164"=>"おもちゃ・ホビー・ゲーム",
	"100533"=>"キッズ・ベビー・マタニティ",
	"215783"=>"キッチン・日用品雑貨・文具",
	"216129"=>"ジュエリー・腕時計",
	"101070"=>"スポーツ・アウトドア",
	"100938"=>"ダイエット・健康",
	"100316"=>"水・ソフトドリンク",
	"100026"=>"水・ソフトドリンク",
	"100026"=>"パソコン・周辺機器",
	"216131"=>"バッグ・小物・ブランド雑貨",
	"100371"=>"レディースファッション・靴",
	"100005"=>"花・ガーデン・DIY",
	"101213"=>"ペット・ペットグッズ",
	"211742"=>"家電・AV・カメラ",
	"101114"=>"車・バイク",
	"100227"=>"食品",
	"100939"=>"美容・コスメ・香水",
	"200162"=>"本・雑誌・コミック",
	"101381"=>"旅行・出張・チケット",
	"200163"=>"不動産・住まい",
	"101438"=>"学び・サービス・保険",
	"100000"=>"百貨店・総合通販・ギフト",
	"402853"=>"デジタルコンテンツ",
	"503190"=>"車用品・バイク用品",
	"100433"=>"インナー・下着・ナイトウエア",
	"510901"=>"日本酒・焼酎",
	"510915"=>"ビール・洋酒",
	"551167"=>"スイーツ",
	"551169"=>"医薬品・コンタクト・介護",
	"551177"=>"メンズファッション・靴",
    );

function rakutensort ($rakuten_sort_type,&$rakuten_sort_order) {
    // 並べ順の選択肢からパラメータを設定
    switch ($rakuten_sort_type) {
    case 0: //楽天標準ソート順
	    $rakuten_sort_order  = "standard";
	    break;
    case 1: //アフィリエイト料率順（昇順）
	    $rakuten_sort_order = "+affiliateRate";
	    break;
    case 2: //アフィリエイト料率順（降順）
	    $rakuten_sort_order  = "-affiliateRate";
	    break;
    case 3: //レビュー件数順（昇順）
	    $rakuten_sort_order  = "+reviewCount";
	    break;
    case 4: //レビュー件数順（降順）
	    $rakuten_sort_order  = "-reviewCount";
	    break;
    case 5: //価格順（昇順）
	    $rakuten_sort_order  = "+itemPrice";
	    break;
    case 6: //価格順（降順）
	    $rakuten_sort_order  = "-itemPrice";
	    break;
    case 7: //商品更新日時順（昇順）
	    $rakuten_sort_order  = "+updateTimestamp";
	    break;
    case 8: //商品更新日時順（降順）
	    $rakuten_sort_order  = "-updateTimestamp";
	    break;
    default:
	    break;
    }
}



} //class lockets_rakutenichibaapi end


// ■ヤフーショッピング商品検索API

class lockets_yahooshoppingapi {

// ソート順を配列に代入
public $arr_sorts = array(
	"-score" => "おすすめ順",
	"%2Bprice" => "商品価格が安い順",
	"-price" => "商品価格が高い順",
	"%2Bname" => "ストア名昇順",
	"-name" => "ストア名降順",
	"-sold" => "売れ筋順"
);

// カテゴリーを配列に代入
public $arr_categories = array(
	 "1" => "すべてのカテゴリから",
	 "13457" => "ファッション",
	 "2498" => "食品",
	 "2500" => "ダイエット、健康",
	 "2501" => "コスメ、香水",
	 "2502" => "パソコン、周辺機器",
	 "2504" => "AV機器、カメラ",
	 "2505" => "家電",
	 "2506" => "家具、インテリア",
	 "2507" => "花、ガーデニング",
	 "2508" => "キッチン、生活雑貨、日用品",
	 "2503" => "DIY、工具、文具",
	 "2509" => "ペット用品、生き物",
	 "2510" => "楽器、趣味、学習",
	 "2511" => "ゲーム、おもちゃ",
	 "2497" => "ベビー、キッズ、マタニティ",
	 "2512" => "スポーツ",
	 "2513" => "レジャー、アウトドア",
	 "2514" => "自転車、車、バイク用品",
	 "2516" => "CD、音楽ソフト",
	 "2517" => "DVD、映像ソフト",
	 "10002" => "本、雑誌、コミック"
);

function yshsort ($v_sort_type,&$v_sort_order) {
    // 並べ順の選択肢からパラメータを設定
    switch ($v_sort_type) {
    case 0://商品価格安い順
	    $v_sort_order = "%2Bprice";
	    break;
    case 1: //商品価格高い順
	    $v_sort_order = "-price";
	    break;
    case 2: //おすすめ順
	    $v_sort_order = "-score";
	    break;
    case 3: //売れ筋順
	    $v_sort_order = "-sold";
	    break;
    case 4: //アフィリエイト料率が高い順
	    $v_sort_order = "-affiliate";
	    break;
    case 5: //レビュー件数が多い順
	    $v_sort_order = "-review_count";
	    break;
    default:
	    break;
    }
}// function yshsort


}


// ■ヤフオクオークション検索API

class lockets_yahooauctionapi {

// ソート順を配列に代入
public $arr_sorts = array(
	"0" => "終了間際順",
	"1" => "入札数が多い順",
	"2" => "現在価格安い順",
	"3" => "現在価格高い順",
	"4" => "即決価格安い順"
);

function yaucsort ($v_sort_type,&$v_sort_by,&$v_sort_order) {
    switch ($v_sort_type) {
    case 0://終了間際順
	    $v_sort_by = "end";
	    $v_sort_order = "a";//昇順
	    break;
    case 1: //入札数が多い順
	    $v_sort_by = "bids";
	    $v_sort_order = "d";
	    break;
    case 2: //現在価格安い順
	    $v_sort_by = "cbids";
	    $v_sort_order = "a";
	    break;
    case 3: //現在価格高い順
	    $v_sort_by = "cbids";
	    $v_sort_order = "d";
	    break;
    case 4: //即決価格安い順
	    $v_sort_by = "bidorbuy";
	    $v_sort_order = "a";
	    break;
    default:
	    break;
    }
}

} // class lockets_yahooaucapi


// ■Amazon.co.jp商品検索API

class lockets_Amazonitemsearchtapi {

// カテゴリーを配列に代入
public $arr_categories = array(
	"All"         => "全て", 
	"Apparel"       => "アパレル", 
	"Baby"             => "ベビー＆マタニティ", 
	"Beauty"             => "コスメ", 
	"Books"               => "本(和書)", 
	"Classical"         => "クラシック音楽", 
	"DVD"           => "DVD", 
	"Electronics"           => "エレクトロニクス", 
	"ForeignBooks"  => "洋書", 
	"Grocery" => "食品", 
	"HealthPersonalCare"      => "ヘルスケア", 
	"Hobbies"     => "ホビー", 
	"Jewelry"    => "ジュエリー",
	"Kitchen"    => "ホーム＆キッチン",
	"Music"    => "ミュージック",
	"MusicTracks"    => "曲名",
	"Software"    => "ソフトウェア",
	"SportingGoods"    => "スポーツ＆アウトドア",
	"Toys"    => "おもちゃ",
	"VHS"    => "VHS",
	"Video"    => "ビデオ",
	"VideoGames"    => "ゲーム",
	"Watches"    => "時計"
);

// ソート順番を配列に代入
public $arr_sorts = array(
	"0" => "価格の安い順番", 
	"1" => "価格の高い順番", 
	"2" => "売れ筋ランキング順",
    );


// Amazonソート順
function amazonsort ($v_sort_type,&$v_sort_order,$v_category) {
    // 並べ順の選択肢からパラメータを設定
    switch ($v_sort_type) {
    case 0://商品価格安い順
	    $v_sort_order = "price";
	    if ($v_category=="Books"|$v_category=="Classical"|$v_category=="DVD"|$v_category=="ForeignBooks"|$v_category=="Music"|$v_category=="VHS"|$v_category=="Video") {$v_sort_order = "pricerank";}
	    break;
    case 1: //商品価格高い順
	    $v_sort_order = "-price";
	    if ($v_category=="Books"|$v_category=="ForeignBooks") {$v_sort_order = "inverse-pricerank";}
	    if ($v_category=="Classical"|$v_category=="DVD"|$v_category=="Music"|$v_category=="VHS"|$v_category=="Video") {$v_sort_order = "-pricerank";}
	    break;
    case 2: //売れ筋ランキング順
	    $v_sort_order = "salesrank";
	    if ($v_category=="Beauty") {$v_sort_order = "reviewrank";}
	    break;
    default:
	    break;
    }// stich
}// function amazonsort


// ■Amazon用セレクトメニュー作成：<select>タグによるメニュー描画、パラメータから選択されているものを選択状態にする。
// select name、select option配列、選択、オプション
function DrawSelectMenu2($name, $source_arr, $select_value) {
	$menu = "<select name=\"$name\">";
	foreach($source_arr as $key => $value) {
		$menu .= "<option value=\"$key\"";
		if ($key == $select_value) {
			$menu .= " selected";
		}
		$menu .= ">$value</option>";
	}
	$menu .= "</select>";
	return $menu;
}



// 商品キーワード検索リクエストURL組み立て関数ItemSearch
function awsrequesturl($v_category,$v_keyword,$v_sort_order,$v_page) {
$amzacckey=get_option('amzacckey');
if ($amzacckey==="") {$amzacckey==AMZACCKEY;}
$amzseckey=get_option('amzseckey');
if ($amzacckey==="") {$amzacckey==AMZSECKEY;}
$amzassid=get_option('amzassid');
if ($amzassid==="") {$amzassid==AMZASSID;}

$baseurl = 'http://ecs.amazonaws.jp/onca/xml';
$params = array();
$params['Service']        = 'AWSECommerceService';
$params['AWSAccessKeyId'] = $amzacckey;
$params['Version']        = '2009-10-01';
$params['Operation']      = 'ItemSearch';
$params['SearchIndex']    = $v_category;
//$params['asin']       = $asin;
$params['Keywords']       = $v_keyword;
if (!$v_category=="All") {
$params['Sort']       = $v_sort_order;
}
$params['AssociateTag']   = $amzassid;
$params['ContentType']   = 'text/xml';
$params['ResponseGroup']   = 'Medium,Reviews,OfferSummary';
$params['ItemPage']   = $v_page;
$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
// パラメータの順序を昇順に並び替えます
ksort($params);
$canonical_string = '';
foreach ($params as $k => $v) {
    $canonical_string .= '&'.urlencode_rfc3986($k).'='.urlencode_rfc3986($v);
}
$canonical_string = substr($canonical_string, 1);

$parsed_url = parse_url($baseurl);
$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
$signature = base64_encode(hash_hmac('sha256', $string_to_sign, $amzseckey, true));
$awsurl = $baseurl.'?'.$canonical_string.'&Signature='.urlencode_rfc3986($signature);
return $awsurl;
}


// 商品詳細情報リクエストURL組み立て関数ItemLookup
function Awsitemlookupurl($asinno) {

$amzacckey=get_option('amzacckey');
if ($amzacckey==="") {$amzacckey==AMZACCKEY;}
$amzseckey=get_option('amzseckey');
if ($amzacckey==="") {$amzacckey==AMZSECKEY;}
$amzassid=get_option('amzassid');
if ($amzassid==="") {$amzassid==AMZASSID;}

$baseurl = 'http://ecs.amazonaws.jp/onca/xml';
$params = array();
$params['Service']        = 'AWSECommerceService';
$params['AWSAccessKeyId'] = $amzacckey;
$params['Version']        = '2009-10-01';
$params['Operation']      = 'ItemLookup';
$params['ItemId']       = $asinno;
$params['AssociateTag']   = $amzassid;
$params['Condition']   = 'New';
$params['ContentType']   = 'text/xml';
$params['ResponseGroup']   = 'Large,Tracks';
$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
// パラメータの順序を昇順に並び替えます
ksort($params);
$canonical_string = '';
foreach ($params as $k => $v) {
    $canonical_string .= '&'.urlencode_rfc3986($k).'='.urlencode_rfc3986($v);
}
$canonical_string = substr($canonical_string, 1);
$parsed_url = parse_url($baseurl);
$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
$signature = base64_encode(hash_hmac('sha256', $string_to_sign, $amzseckey, true));
$awsurl = $baseurl.'?'.$canonical_string.'&Signature='.urlencode_rfc3986($signature);
return $awsurl;
}

} // class lockets_Amazonproductapi


?>
