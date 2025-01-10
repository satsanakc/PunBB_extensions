<?php

if (!defined('FORUM')) die();

if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
	include $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
else
	include $ext_info['path'].'/lang/English.php';

?>

		<div class="content-head" id="<?php echo $ext_info['id'] ?>">
			<h2 class="hn"><span><?php echo $lang_pan_pm['settings_head'] ?></span></h2>
		</div>

		<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
			<div class="sf-box checkbox">
				<input type="hidden" name="form[pan_pm_global_link]" value="0" />
				<span class="fld-input"><input type="checkbox" id="fld" name="form[pan_pm_global_link]" value="1"<?php if ($forum_config['o_pan_pm_global_link'] == '1') echo 'checked="checked"' ?> /></span>
				<label for="fld "><span><?php echo $lang_pan_pm['pm_global_link'] ?></span><?php echo $lang_pan_pm['pm_global_link_i'] ?></label>
			</div>
		</div>

		<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
			<div class="sf-box text">
				<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_pan_pm['pm_max_inbox'] ?></span><small><?php echo $lang_pan_pm['pm_max_inbox_i'] ?></small></label><br />
				<span class="fld-input"><input type="number" id="fld<?php echo $forum_page['fld_count'] ?>" name="form[pan_pm_max_inbox]" size="5" maxlength="5" value="<?php echo $forum_config['o_pan_pm_max_inbox'] ?>" /></span>
			</div>
		</div>

		<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
			<div class="sf-box text">
				<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_pan_pm['pm_max_outbox'] ?></span><small><?php echo $lang_pan_pm['pm_max_outbox_i'] ?></small></label><br />
				<span class="fld-input"><input type="number" id="fld<?php echo $forum_page['fld_count'] ?>" name="form[pan_pm_max_outbox]" size="5" maxlength="5" value="<?php echo $forum_config['o_pan_pm_max_outbox'] ?>" /></span>
			</div>
		</div>
		
		<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
			<div class="sf-box text">
				<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_pan_pm['active_contacts'] ?></span><small><?php echo $lang_pan_pm['active_contacts_i'] ?></small></label><br />
				<span class="fld-input"><input type="number" id="fld<?php echo $forum_page['fld_count'] ?>" name="form[pan_pm_active_contacts]" size="5" maxlength="5" value="<?php echo $forum_config['o_pan_pm_active_contacts'] ?>" /></span>
			</div>
		</div>

<?php

