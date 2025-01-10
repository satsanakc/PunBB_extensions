<?php 
if(!defined('SAT_PAGES_CADD') && !defined('SAT_PAGES_CEDIT')) die();

		$forum_page['crumbs'] = array(
			array($forum_config['o_board_title'], forum_link($forum_url['index'])),
			array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
			array($lang_sat_page['info_pages'], forum_link($forum_url['sat_pages'])),
			(defined('SAT_PAGES_CADD') ? $lang_sat_page['addcat'] : $lang_sat_page['editcat'])
		);

($hook = get_hook('sat_pages_catform_start')) ? eval($hook) : null;

		require FORUM_ROOT.'header.php';

// START SUBST - <!-- forum_main -->
ob_start();
?>

	<div class="main-subhead">
		<h2 class="hn"><span><?php print (defined('SAT_PAGES_CADD') ? $lang_sat_page['addcat'] : $lang_sat_page['editcat']) ?></span></h2>
	</div>
	<div class="main-content main-frm">
		<form class="frm-form" method="post" accept-charset="utf-8" action="options.php">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['sat_pages'])) ?>" />
			</div>
<?php
			if (defined('SAT_PAGES_CEDIT')) echo '<div class="hidden"><input id="editcid" type="hidden" name="cid" value="'.$cat['id'].'" /></div>';
			else echo '<div class="ct-box"><p>'.$lang_sat_page['addcatmess'].'</p></div>';
?>
			<fieldset class="frm-group group<?php echo ++$forum_page['item_count'] ?>">
				<legend class="group-legend"><span><?php print (defined('SAT_PAGES_CADD') ? $lang_sat_page['addcatleg'] : $lang_sat_page['editcatleg']) ?></span></legend>
				<div class="sf-set set<?php echo ++$forum_page['fld_count'] ?>">
					<div class="sf-box text required longtext">
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo $lang_sat_page['catname'] ?></span></label><br>
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="<?php print (defined('SAT_PAGES_CADD') ? 'new' : 'edit') ?>_cat_name" size="35" maxlength="80" required<?php if (defined('SAT_PAGES_CEDIT')) echo ' value="'.$cat['name'].'"' ?>></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['fld_count'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo $forum_page['fld_count'] ?>" name="cat_announce" value="1"<?php if (defined('SAT_PAGES_CADD') || $cat['announce'] == 1) echo 'checked' ?>></span>
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><?php echo $lang_sat_page['cat_announce'] ?></label><br>
					</div>
				</div>
				<fieldset class="mf-set set<?php echo ++$forum_page['fld_count'] ?>">
					<legend><span><?php echo $lang_sat_page['catshowleg'] ?></span></legend>
					<div class="mf-box">
						<div class="mf-item">
							<span class="fld-input"><input type="radio" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="catshow" value="0"<?php if (defined('SAT_PAGES_CADD') || $cat['showcont'] == 0) echo ' checked' ?>></span>
							<label for="fld<?php echo $forum_page['fld_count'] ?>"><?php echo $lang_sat_page['catshowlist'] ?></label>
						</div>
						<div class="mf-item">
							<span class="fld-input"><input type="radio" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="catshow" value="1"<?php if (defined('SAT_PAGES_CEDIT') && $cat['showcont'] == 1) echo ' checked' ?>></span>
							<label for="fld<?php echo $forum_page['fld_count'] ?>"><?php echo $lang_sat_page['catshowcont'] ?></label>
						</div>
					</div>
				</fieldset>
				<fieldset class="mf-set set<?php echo ++$forum_page['fld_count'] ?>">
					<legend><span><?php echo $lang_sat_page['catdenleg'] ?></span></legend>
					<div class="mf-box">
<?php foreach ($sat_groups as $key => $value) { if ($key != 1) { ?>
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="catdengroup[<?php echo $key ?>]" value="<?php echo $key ?>"<?php if (defined('SAT_PAGES_CEDIT') && in_array($key, $cat['denyaccess'])) echo ' checked' ?>></span>
							<label for="fld<?php echo $forum_page['fld_count'] ?>"><?php echo $value ?></label>
						</div>
<?php }} ?>
					</div>
				</fieldset>
			</fieldset>
<?php if (defined('SAT_PAGES_CADD')) { ?>
			<div class="ct-box">
				<p><?php echo $lang_sat_page['catdenmess'] ?></p>
			</div>
			<div class="frm-buttons">
				<span class="submit primary"><input type="submit" name="add_cat" value="<?php echo $lang_sat_page['addcatbut'] ?>"></span>
			</div>
<?php } else { ?>
			<div class="frm-buttons">
				<span class="submit primary"><input type="submit" name="edit_cat" value="<?php echo $lang_sat_page['save'] ?>"></span>
			</div>
<?php } ?>
		</form>
	</div>