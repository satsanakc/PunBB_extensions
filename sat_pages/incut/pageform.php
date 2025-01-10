<?php 
if (defined('SAT_PAGES_PADD')) {
	$lang_sat_page['pagetitle'] = $lang_sat_page['addpage'];
	$piddiv = '';
	$lang_sat_page['pageleg'] = $lang_sat_page['addpageleg'];
	$pagename = 'new_page_name';
	$butname = 'add_page';
	$lang_sat_page['pagebut'] = $lang_sat_page['addpagebut'];
}
elseif (defined('SAT_PAGES_PEDIT')) {
	$lang_sat_page['pagetitle'] = $lang_sat_page['editpage'];
	$piddiv = '<div class="hidden"><input id="editpid" type="hidden" name="pid" value="'.$page['id'].'" /></div>';
	$lang_sat_page['pageleg'] = $lang_sat_page['editpageleg'];
	$pagename = 'edit_page_name';
	$butname = 'edit_page';
	$lang_sat_page['pagebut'] = $lang_sat_page['save'];
}
else die();

		$forum_page['crumbs'] = array(
			array($forum_config['o_board_title'], forum_link($forum_url['index'])),
			array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
			array($lang_sat_page['info_pages'], forum_link($forum_url['sat_pages'])),
			$lang_sat_page['pagetitle']
		);

($hook = get_hook('sat_pages_pageform_start')) ? eval($hook) : null;

require FORUM_ROOT.'header.php';

// START SUBST - <!-- forum_main -->
ob_start();
?>

	<div class="main-subhead">
		<h2 class="hn"><span><?php echo $lang_sat_page['pagetitle'] ?></span></h2>
	</div>
	<div class="main-content main-frm">
		<form class="frm-form" method="post" accept-charset="utf-8" action="options.php">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['sat_pages'])) ?>" />
			</div>
			<?php echo $piddiv ?>
			<?php if (defined('SAT_PAGES_PADD')) echo '<div class="ct-box"><p>'.$lang_sat_page['pagemess'].'</p></div>' ?>
			<fieldset class="frm-group group<?php echo ++$forum_page['item_count'] ?>">
				<legend class="group-legend"><span><?php echo $lang_sat_page['pageleg'] ?></span></legend>

				<div class="sf-set set<?php echo ++$forum_page['fld_count'] ?>">
					<div class="sf-box text required longtext">
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo $lang_sat_page['pagename'] ?></span></label><br>
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="<?php echo $pagename ?>" size="35" maxlength="80" <?php if (defined('SAT_PAGES_PEDIT')) echo 'value="'.$page['name'].'" ' ?>required></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['fld_count'] ?>">
					<div class="sf-box select">
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo $lang_sat_page['pagecat'] ?></span></label><br>
						<span class="fld-input"><select id="fld<?php echo $forum_page['fld_count'] ?>" name="page_category">
								<option value=""<?php if (defined('SAT_PAGES_PADD') || !$page['category_id']) echo ' selected' ?>><?php echo $lang_sat_page['nocat'] ?></option>
<?php
foreach ($cat_list as $value) {
	echo "<option value=\"{$value['id']}\"";
	if (defined('SAT_PAGES_PEDIT') && $value['id'] == $page['category_id']) echo ' selected';
	echo ">{$value['name']}</option>";
} ?>
						</select></span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['fld_count'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo $forum_page['fld_count'] ?>" name="page_announce" value="1"<?php if (defined('SAT_PAGES_PADD') || $page['announce'] == 1) echo ' checked' ?>></span>
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><?php echo $lang_sat_page['page_announce'] ?></label><br>
					</div>
				</div>
				<div class="txt-set set<?php echo ++$forum_page['fld_count'] ?>">
					<div class="txt-box textarea">
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo $lang_sat_page['pagetxt'] ?></span></label>
						<div class="txt-input"><span class="fld-input"><textarea id="fld<?php echo $forum_page['fld_count'] ?>" name="page_txt" rows="15" cols="95" required spellcheck><?php if (defined('SAT_PAGES_PEDIT')) echo $page['txt'] ?></textarea></span></div>
					</div>
				</div>
				<fieldset class="mf-set set<?php echo ++$forum_page['fld_count'] ?>">
					<legend><span><?php echo $lang_sat_page['pagedenleg'] ?></span></legend>
					<div class="mf-box">
<?php foreach ($sat_groups as $key => $value) { if ($key != 1) { ?>
						<div class="mf-item">
							<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="pagedengroup[<?php echo $key.']" value="'.$key.'"';
	if (defined('SAT_PAGES_PEDIT') && in_array($key, $page['denyaccess'])) echo ' checked' ?>></span>
							<label for="fld<?php echo $forum_page['fld_count'] ?>"><?php echo $value ?></label>
						</div>
<?php }} ?>
					</div>
				</fieldset>
			</fieldset>
			<?php if (defined('SAT_PAGES_PADD')) echo '<div class="ct-box"><p>'.$lang_sat_page['pagedenmess'].'</p></div>' ?>
			<div class="frm-buttons">
				<span class="submit primary"><input type="submit" name="<?php echo $butname ?>" value="<?php echo $lang_sat_page['pagebut'] ?>"></span>
			</div>
		</form>
	</div>