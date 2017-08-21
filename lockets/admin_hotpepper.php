<?php
// ホットペッパー
function lockets_recruit_webservice() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
check_admin_referer('lockets-options');
update_option('recruit_webservice_key', $_POST['recruit_webservice_key']);
update_option('lockets_hotpepper_template', $_POST['lockets_hotpepper_template']);
?>
<div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
$recruit_webservice_key= get_option('recruit_webservice_key');
$lockets_hotpepper_template= get_option('lockets_hotpepper_template');
?>

<div class="wrap">
<h2>LocketsプラグインリクルートWEBサービス関連設定</h2>
<p>HOT PEPPERを利用するための設定です</p>
<form name="form" method="post" action="">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('lockets-options'); ?>

<table class="form-table"><tbody>

<tr>
<th><label for="recruit_webservice_key"><?php
_e('リクルートWEBサービスキー', 'recruit_webservice_key'); ?></label></th> <td><input size="36" type="text" name="recruit_webservice_key"
id="recruit_webservice_key" value="<?php
echo attribute_escape($recruit_webservice_key); ?>" /></td>
</tr>
    
<tr>
<th><label for="lockets_hotpepper_template"><?php
_e('表示テンプレート（HTMLで記述）', 'lockets_hotpepper_template'); ?></label></th> <td>
<textarea cols="80" rows="20" name="lockets_hotpepper_template" id="lockets_hotpepper_template">
<?php echo str_replace('\\','',$lockets_hotpepper_template); ?>
</textarea> <br />
<br />
置き換え用の文字列は下記のとおり<br />
<h5>基本情報</h5>
【お店ID】
【掲載店名】
【掲載店名かな】
【住所】
【最寄駅名】
【料金備考】
【お店キャッチ】
【総席数】
【交通アクセス】
【営業時間】
【定休日】
【最大宴会収容人数】
    
<h5>画像URL</h5>
【ロゴ画像URL】
【写真PC向けLサイズ】
【写真PC向けMサイズ】
【写真PC向けSサイズ】
    
<h5>リンクURL</h5>
【店舗URL(PC)】
【クーポンURL(PC)】
    
<h5>位置情報</h5>
【緯度】
【経度】

<h5>その他</h5>
【WiFi 有無】
【ウェディング･二次会】
【コース】
【飲み放題】
【食べ放題】
【個室】
【掘りごたつ】
【座敷】
【カード可】
【禁煙席】
【貸切可】
【携帯電話OK】
【駐車場】
【バリアフリー】
【その他設備】
【ソムリエ】
【オープンエア】
【ライブ・ショー】
【エンタメ設備】
【カラオケ】
【バンド演奏可】
【TV・プロジェクター】
【英語メニュー】
【ペット可】
【お子様連れ】
【ランチ】
【23時以降も営業】
【備考】
【Google Maps埋め込み】
<h5>クレジット</h5>
※クレジットはいずれか必須<br>
【HOT PEPPERクレジットA】※画像形式　小　135px × 17px<br>
【HOT PEPPERクレジットB】※画像形式　中　88px × 35px<br>
【HOT PEPPERクレジットC】※テキスト形式<br>
    
    <br />
<br />
例：<br />

</td>
</tr>

</tbody></table>

<p class="submit">
<input type="submit" name="update_option" class="button- primary" value="<?php _e('Save Changes'); ?>" />
</p>

</form>
</div>

<?php
}
?>