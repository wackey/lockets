<?php
// じゃらん
function lockets_jalan_webservice() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
check_admin_referer('lockets-options');
update_option('jalan_webservice_key', sanitize_text_field($_POST['jalan_webservice_key']));
update_option('lockets_jalan_template', wp_kses_post($_POST['lockets_jalan_template']));
?>
<div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
$jalan_webservice_key= get_option('jalan_webservice_key');
$lockets_jalan_template= get_option('lockets_jalan_template');
?>

<div class="wrap">
<h2>LocketsプラグインじゃらんWebサービス関連設定</h2>
<p>じゃらんを利用するための設定です</p>
<form name="form" method="post" action="">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('lockets-options'); ?>

<table class="form-table"><tbody>

<tr>
<th><label for="jalan_webservice_key"><?php
_e('じゃらんAPIキー', 'jalan_webservice_key'); ?></label></th> <td><input size="36" type="text" name="jalan_webservice_key"
id="jalan_webservice_key" value="<?php
echo attribute_escape($jalan_webservice_key); ?>" /></td>
</tr>
    
<tr>
<th><label for="lockets_jalan_template"><?php
_e('表示テンプレート（HTMLで記述）', 'lockets_jalan_template'); ?></label></th> <td>
<textarea cols="80" rows="20" name="lockets_jalan_template" id="lockets_jalan_template">
<?php echo str_replace('\\','',$lockets_jalan_template); ?>
</textarea> <br />
<br />
置き換え用の文字列は下記のとおり<br />
<h5>基本情報</h5>
【宿番号】
【宿名漢字】
【郵便番号】
【住所】
【キャッチ】
【コピー】
【参考料金】
    
<h5>画像URL</h5>
【宿画像URL】
【宿画像キャプション】
    
<h5>リンクURL</h5>
【宿詳細ページURL】
    

<h5>クレジット</h5>
※クレジットはいずれか必須<br>
【じゃらんクレジットA】※画像形式　ブルー　88px × 50px<br>
【じゃらんクレジットB】※画像形式　グレー　88px × 50px<br>
【じゃらんクレジットC】※テキスト形式<br>
    

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