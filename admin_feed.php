<?php
// 外部feed配信
function lockets_feed() {
// ポストされた値の入力チェックと書き込み
if (isset($_POST['update_option'])) {
check_admin_referer('lockets-options');
update_option('locketsfeedswitch', sanitize_text_field($_POST['locketsfeedswitch']));
?>
<div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p>
</div>
<?php }
$locketsfeedswitch = get_option('locketsfeedswitch');
?>

<div class="wrap">
<h2>Locketsプラグイン：外部へのfeed配信</h2>
<p>外部サイトへ配信するfeedを有効にする場合はONとしてください</p>
<form name="form" method="post" action="">
<input type="hidden" name="action" value="update" />
<?php wp_nonce_field('lockets-options'); ?>

<table class="form-table"><tbody>

<tr>
<th><label for="locketsfeedswitch"><?php
_e('外部配信を有効にする', 'locketsfeedswitch'); ?></label></th> <td>
<?php if($locketsfeedswitch == "1"){ ?>
    <input type="checkbox"  name="locketsfeedswitch" id="locketsfeedswitch" checked="checked"  value="1" /> ONにする
<?php }else{ ?>
    <input type="checkbox"  name="locketsfeedswitch" id="locketsfeedswitch" value="1" /> ONにする
<?php } ?>
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