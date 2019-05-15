<?php
// Google Maps表示設定
function lockets_gmap() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
    check_admin_referer('lockets-options');
    update_option('lockets_gmap_apikey', sanitize_text_field($_POST['lockets_gmap_apikey']));
    update_option('lockets_gmap_zoom', sanitize_text_field($_POST['lockets_gmap_zoom']));
    update_option('lockets_gmap_width', sanitize_text_field($_POST['lockets_gmap_width']));
    update_option('lockets_gmap_height', sanitize_text_field($_POST['lockets_gmap_height']));
    update_option('lockets_googleplace_template', wp_kses_post($_POST['lockets_googleplace_template']));
?>
<div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
    $lockets_gmap_apikey= get_option('lockets_gmap_apikey');
    $lockets_gmap_zoom= get_option('lockets_gmap_zoom');
    $lockets_gmap_width= get_option('lockets_gmap_width');
    $lockets_gmap_height= get_option('lockets_gmap_height');
    $lockets_googleplace_template= get_option('lockets_googleplace_template');
?>

<div class="wrap">
<h2>Locketsプラグイン：Google プレイス・Google Maps表示設定</h2>
<p>Google プレイスを使用するための設定を行います。</p>
<p>また、Google Mapsの幅や高さ、ズームのデフォルト値をこちらで設定します。<br>
※投稿ごとに個別に設定することも可能です。<br>
※未入力の場合はデフォルト設定のままでご使用いただけます。</p>
<form name="form" method="post" action="">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('lockets-options'); ?>

<table class="form-table"><tbody>
    
<tr>
<th><label for="lockets_gmap_apikey"><?php
_e('Google Places API Web Service キー', 'loclets_gmap_apikey'); ?></label></th> <td><input size="36" type="text" name="lockets_gmap_apikey"
id="lockets_gmap_apikey" value="<?php
echo attribute_escape($lockets_gmap_apikey); ?>" /><br />※Google プレイス検索を使用する場合は必須</td>
</tr>

<tr>
<th><label for="lockets_gmap_zoom"><?php
_e('Google Mapsのズーム値', 'loclets_gmap_zoom'); ?></label></th> <td><input size="36" type="text" name="lockets_gmap_zoom"
id="lockets_gmap_zoom" value="<?php
echo attribute_escape($lockets_gmap_zoom); ?>" /><br>
    デフォルト：14</td>
</tr>

<tr>
<th><label for="lockets_gmap_width"><?php
_e('Google Mapsの幅', 'lockets_gmap_width'); ?></label></th> <td><input size="36" type="text" name="lockets_gmap_width"
id="lockets_gmap_width" value="<?php
echo attribute_escape($lockets_gmap_width); ?>" /><br>
    デフォルト：100%　※1〜100%もしくはpx指定は数字のみ入力 例：500pxの場合「500」と入力</td>
</tr>
    
<tr>
<th><label for="lockets_gmap_height"><?php
_e('Google Mapsの高さ', 'lockets_gmap_height'); ?></label></th> <td><input size="36" type="text" name="lockets_gmap_height"
id="lockets_gmap_height" value="<?php
echo attribute_escape($lockets_gmap_height); ?>" /><br>
    デフォルト：450（単位はpx）　※px指定は数字のみ入力 例：500pxの場合「500」と入力</td>
</tr>

<tr>
<th><label for="lockets_googleplace_template"><?php
_e('Googleプレイス<br>表示テンプレート<br>（HTMLと置換文字列で記述）<br>※未入力の場合はデフォルトテンプレートで表示', 'lockets_googleplace_template'); ?></label></th> <td>
<textarea cols="80" rows="10" name="lockets_googleplace_template" id="lockets_googleplace_templatee">
<?php echo str_replace('\\','',$lockets_googleplace_template); ?>
</textarea> <br />
<br />
置き換え用の文字列は下記のとおり<br />
<h5>基本情報</h5>
【スポット名】
【住所】
【電話番号】
【Webサイトテキストリンク】
【Google Maps埋め込み】
<h5>クレジット</h5>
※クレジットはいずれか必須<br>
【GoogleクレジットA】※画像形式　144px × 18px・白背景など通常ロゴ<br>
【GoogleクレジットB】※画像形式　144px × 18px・濃い色の背景など白抜き型のロゴ<br>
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