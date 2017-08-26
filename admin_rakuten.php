<?php
// 楽天ウェブサービス
function lockets_rws() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
check_admin_referer('lockets-options');
update_option('rakuten_search_token', sanitize_text_field($_POST['rakuten_search_token']));
update_option('rakuten_affiliate_id', sanitize_text_field($_POST['rakuten_affiliate_id']));
update_option('lockets_rakuten_travel_template', wp_kses_post($_POST['lockets_rakuten_travel_template']));
?>
<div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
$rakutentoken= get_option('rakuten_search_token');
$rakutenaffid= get_option('rakuten_affiliate_id');
$lockets_rakuten_travel_template= get_option('lockets_rakuten_travel_template');
?>

<div class="wrap">
<h2>Locketsプラグイン楽天ウェブサービス関連設定</h2>
<p>楽天トラベルなど楽天ウェブサービス利用するための設定です</p>
<form name="form" method="post" action="">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('lockets-options'); ?>

<table class="form-table"><tbody>

<tr>
<th><label for="rakuten_affiliate_id"><?php
_e('楽天アフィリエイトID', 'rakuten_affiliate_id'); ?></label></th> <td><input size="36" type="text" name="rakuten_affiliate_id"
id="rakuten_affiliate_id" value="<?php
echo attribute_escape($rakutenaffid); ?>" /></td>
</tr>

<tr>
<th><label for="rakuten_search_token"><?php
_e('楽天デベロッパーID', 'rakuten_search_token'); ?></label></th> <td><input size="36" type="text" name="rakuten_search_token"
id="rakuten_search_token" value="<?php
echo attribute_escape($rakutentoken); ?>" /></td>
</tr>
    
<tr>
<th><label for="lockets_rakuten_travel_template"><?php
_e('表示テンプレート（HTMLで記述）', 'lockets_rakuten_travel_template'); ?></label></th> <td>
<textarea cols="80" rows="20" name="lockets_rakuten_travel_template" id="lockets_rakuten_travel_template">
<?php echo str_replace('\\','',$lockets_rakuten_travel_template); ?>
</textarea>
</td>
</tr>

</tbody></table>

<h4>置き換え用の文字列は下記のとおり</h4>
【施設番号】
【施設名称】
【施設情報ページURL】
【宿泊プラン一覧ページURL】
【ダイナミックパッケージ宿泊プラン一覧ページUR
【お客様の声ページURL】
【施設かな名称】
【施設特色】
【最安料金】
【緯度】
【経度】
【郵便番号】
【住所１】
【住所２】
【施設電話番号】
【施設へのアクセス】
【駐車場情報】
【最寄駅名称】
【施設画像URL】
【施設画像サムネイルURL】
【部屋画像URL】
【部屋画像サムネイルURL】
【施設提供地図画像URL】
【投稿件数】
【★の数（総合）】
【お客さまの声（1件目）】
【Google Maps埋め込み】

    <h5>クレジット</h5>
※クレジットはいずれか必須<br>
【楽天ウェブサービスクレジットA】※画像形式　Sサイズ（スクエア版）49px × 36px<br>
【楽天ウェブサービスクレジットB】※画像形式　Lサイズ（スクエア版）70px × 52px<br>
【楽天ウェブサービスクレジットC】※画像形式　Sサイズ（バナー版）221px × 21px<br>
【楽天ウェブサービスクレジットD】※テキスト形式<br>
<p class="submit">
<input type="submit" name="update_option" class="button- primary" value="<?php _e('Save Changes'); ?>" />
</p>

</form>
</div>

<?php
}
?>