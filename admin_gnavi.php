<?php
// ぐるなび
function lockets_gnavi_webservice() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
check_admin_referer('lockets-options');
update_option('gnavi_webservice_key', sanitize_text_field($_POST['gnavi_webservice_key']));
update_option('lockets_gnavi_template', wp_kses_post($_POST['lockets_gnavi_template']));
?>
<div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
$gnavi_webservice_key= get_option('gnavi_webservice_key');
$lockets_gnavi_template= get_option('lockets_gnavi_template');
?>

<div class="wrap">
<h2>LocketsプラグインぐるなびWebサービス関連設定</h2>
<p>ぐるなびを利用するための設定です</p>
<form name="form" method="post" action="">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('lockets-options'); ?>

<table class="form-table"><tbody>

<tr>
<th><label for="gnavi_webservice_key"><?php
_e('ぐるなびアクセスキー', 'gnavi_webservice_key'); ?></label></th> <td><input size="36" type="text" name="gnavi_webservice_key"
id="gnavi_webservice_key" value="<?php
echo attribute_escape($gnavi_webservice_key); ?>" /></td>
</tr>
    
<tr>
<th><label for="lockets_gnavi_template"><?php
_e('表示テンプレート（HTMLで記述）', 'lockets_gnavi_template'); ?></label></th> <td>
<textarea cols="80" rows="20" name="lockets_gnavi_template" id="lockets_gnavi_template">
<?php echo str_replace('\\','',$lockets_gnavi_template); ?>
</textarea> <br />
<br />
置き換え用の文字列は下記のとおり<br />
<h5>基本情報</h5>
【お店ID】
【掲載店名】
【掲載店名かな】
【住所】
【最寄駅名】
【電話番号】

【平均予算】
【宴会・パーティ平均予算】
【ランチタイム平均予算】
    
【PR文（短:最大50文字）】
【PR文（長:最大200文字）】
    
【交通アクセス】
【営業時間】
【休業日】
    
<h5>画像URL</h5>
【店舗画像1のURL】
【店舗画像2のURL】
【QRコード】
    
<h5>リンクURL</h5>
【店舗URL(PC)】
【クーポンURL(PC)】
    
<h5>位置情報</h5>
【緯度】
【経度】

<h5>その他</h5>

【Google Maps埋め込み】
<h5>クレジット</h5>
※クレジットはいずれか必須<br>
【ぐるなびクレジットA】※画像形式　155px × 20px<br>
【ぐるなびクレジットB】※画像形式　90px × 35px<br>
【ぐるなびクレジットC】※テキスト形式<br>
    
<br />
<br />
例：<br />

</td>
</tr>

</tbody></table>

<p class="submit">
<input type="submit" name="update_option" class="button-primary" value="<?php _e('Save Changes'); ?>" />
</p>

</form>
</div>

<?php
}
?>