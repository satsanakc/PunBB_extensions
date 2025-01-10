<?php

($hook = get_hook('pf_change_details_identity_pre_vk')) ? eval($hook) : null; ?>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_sat_contact['vk'] ?></span></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page['fld_count'] ?>" type="text" name="form[vk]" placeholder="<?php echo $lang_profile['Name or Url'] ?>" value="<?php echo(isset($form['vk']) ? forum_htmlencode($form['vk']) : forum_htmlencode($user['vk'])) ?>" size="35" maxlength="80" /></span>
					</div>
				</div>

<?php ($hook = get_hook('pf_change_details_identity_pre_ok')) ? eval($hook) : null; ?>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_sat_contact['ok'] ?></span></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page['fld_count'] ?>" type="text" name="form[ok]" placeholder="<?php echo $lang_profile['Name or Url'] ?>" value="<?php echo(isset($form['ok']) ? forum_htmlencode($form['ok']) : forum_htmlencode($user['ok'])) ?>" size="35" maxlength="80" /></span>
					</div>
				</div>