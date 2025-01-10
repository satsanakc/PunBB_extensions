<?php

($hook = get_hook('pf_change_details_identity_pre_discord')) ? eval($hook) : null; ?>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_sat_contact['discord'] ?></span></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page['fld_count'] ?>" type="text" name="form[discord]" value="<?php echo(isset($form['discord']) ? forum_htmlencode($form['discord']) : forum_htmlencode($user['discord'])) ?>" size="35" maxlength="80" /></span>
					</div>
				</div>

<?php ($hook = get_hook('pf_change_details_identity_pre_telegram')) ? eval($hook) : null; ?>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_sat_contact['telegram'] ?></span></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page['fld_count'] ?>" type="text" name="form[telegram]" value="<?php echo(isset($form['telegram']) ? forum_htmlencode($form['telegram']) : forum_htmlencode($user['telegram'])) ?>" size="35" maxlength="80" /></span>
					</div>
				</div>