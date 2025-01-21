<?php if (!defined('FORUM_ROOT')) die();

if ($section == 'character') {
		// Setup breadcrumbs
		$forum_page['crumbs'] = array(
			array($forum_config['o_board_title'], forum_link($forum_url['index'])),
			array(sprintf($lang_profile['Users profile'], $user['username']), forum_link($forum_url['user'], $id)),
			$lang_eni_profile['character']
		);

		// Setup the form
		$forum_page['group_count'] = $forum_page['item_count'] = $forum_page['fld_count'] = 0;
		$forum_page['form_action'] = forum_link($forum_url['profile_'.$section], $id);
		$forum_page['hidden_fields'] = array(
			'form_sent'		=> '<input type="hidden" name="form_sent" value="1" />',
			'csrf_token'	=> '<input type="hidden" name="csrf_token" value="'.generate_form_token($forum_page['form_action']).'" />'
		);

		define('FORUM_PAGE', 'profile-character');
		require FORUM_ROOT.'header.php';
//require $ext_info['path'].'/extra/races.php';
<<<<<<< HEAD

=======
>>>>>>> parent of cf9f726 (Обновление списка рас, добавление фракций.)
		// START SUBST - <!-- forum_main -->
		ob_start();

?>
	<div class="main-subhead">
		<h2 class="hn"><span><?php printf($lang_eni_profile['charChange']) ?></span></h2>
	</div>
	<div class="main-content main-frm">
		<form class="frm-form<?php echo (!empty($form['showdesc']) || !empty($user['showdesc']) ? ' visible' : '') ?>" method="post" accept-charset="utf-8" action="<?php echo $forum_page['form_action'] ?>">
			<div class="hidden">
				<?php echo implode("\n\t\t\t\t", $forum_page['hidden_fields'])."\n" ?>
			</div>

			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_profile['Display settings'] ?></strong></legend>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box checkbox">
						<span class="fld-input"><input type="checkbox" id="fld<?php echo ++$forum_page['fld_count'] ?>" name="form[showdesc]" value="1"<?php echo (!empty($form['showdesc']) || !empty($user['showdesc']) ? ' checked="checked"' : '') ?> onchange="this.checked ? $('.frm-form').addClass('visible') : $('.frm-form').removeClass('visible')"></span>
						<label for="fld<?php echo $forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['desc'] ?></span><?php echo $lang_eni_profile['showdesc'] ?></label><br />
					</div>
				</div>
			</fieldset>

			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_eni_profile['fields'][0] ?></strong></legend>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['name'] ?></span></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="form[ch_name]" value="<?php echo(isset($form['ch_name']) ? forum_htmlencode($form['ch_name']) : forum_htmlencode($user['ch_name'])) ?>" size="35" maxlength="255" /></span>
						<div class="description"><?php echo $lang_eni_profile['namedesc'] ?></div>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['race'] ?></span></label><br />
						<span class="fld-input">
<?php
$races_list = array();
$race_list = array();

$query = array(
	'SELECT'	=> '*',
	'FROM'		=> 'races',
	'WHERE'		=> 'basis IS NULL',
	'ORDER BY'	=> 'id'
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
while ($cur_race = $forum_db->fetch_assoc($result)) {
	$races_list[] = $cur_race;
	if ($user['ch_race_id'] == $cur_race['id'])
		$race1 = $cur_race['id'];
}
$race1 = isset($_POST['ch_race_id']) ? $_POST['ch_race_id'] : $race1;

$query = array(
	'SELECT'	=> '*',
	'FROM'		=> 'races',
	'WHERE'		=> 'basis IS NOT NULL',
	'ORDER BY'	=> 'id'
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
while ($cur_race = $forum_db->fetch_assoc($result)) {
	$race_list[] = $cur_race;
	if ($user['ch_race_id'] == $cur_race['id'])
		$race2 = $cur_race['id'];
		if (empty($race1) && $user['ch_race_id'] == $cur_race['id'])
			$race1 = $cur_race['basis'];
}
$race2 = isset($form['ch_race_id']) ? $form['ch_race_id'] : $race2;
?>
							<select id="fld<?php echo $forum_page['fld_count'] ?>" name="ch_race_id" onchange="racechange(this)">
								<option value="">--</option>
<?php
for ($i = 0; $i < count($races_list); $i++) {
	echo "\t\t\t\t\t\t\t\t".'<option value="'.$races_list[$i]['id'].'"'.($race1 == $races_list[$i]['id'] ? ' selected="selected"' : '').'>'.$races_list[$i]['name'].'</option>'."\n";
}
?>
							</select>
							<select id="fld<?php echo ++$forum_page['fld_count'] ?>" name="form[ch_race_id]" onchange="racechange(this)">
								<option value="">--</option>
<?php
for ($i = 0; $i < count($race_list); $i++) {
	echo "\t\t\t\t\t\t\t\t".'<option class="gr'.$race_list[$i]['basis'].($race_list[$i]['basis'] != $race1 ? ' hidden' : '').'" value="'.$race_list[$i]['id'].'"'.($race2 == $race_list[$i]['id'] ? ' selected="selected"' : '').'>'.$race_list[$i]['name'].'</option>'."\n";
}
?>
							</select>
							<div class="description">
<?php
echo "\t\t\t\t\t\t\t\t".'<div class="racedesc item"'.(empty($race2) && empty($race1) ? ' style="display: block;"' : '').'>'.$lang_eni_profile['emptyrace'].'</div>';
for ($i = 0; $i < count($races_list); $i++) {
	echo "\t\t\t\t\t\t\t\t".'<div class="racedesc item'.$races_list[$i]['id'].'"'.(empty($race2) && $race1 == $races_list[$i]['id'] ? ' style="display: block;"' : '').'>'.$races_list[$i]['description'].'</div>';
}
for ($i = 0; $i < count($race_list); $i++) {
	echo "\t\t\t\t\t\t\t\t".'<div class="racedesc item'.$race_list[$i]['id'].'"'.($race2 == $race_list[$i]['id'] ? ' style="display: block;"' : '').'>'.$race_list[$i]['description'].'</div>';
}
?>
							</div>
						</span>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box select">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['gender'] ?></span></label><br />
						<span class="fld-input"><select id="fld<?php echo $forum_page['fld_count'] ?>" name="form[ch_gender]">
<?php
			for ($i = 0; $i < count($lang_eni_profile['genders']); $i++) {
				if ($i == (isset($form['ch_gender']) ? $form['ch_gender'] : $user['ch_gender']))
					echo "\t\t\t\t\t\t\t".'<option value="'.$i.'" selected="selected">'.$lang_eni_profile['genders'][$i].'</option>'."\n";
				else
					echo "\t\t\t\t\t\t\t".'<option value="'.$i.'">'.$lang_eni_profile['genders'][$i].'</option>'."\n";
			}
?>
						</select></span>
						<div class="description"><?php echo $lang_eni_profile['gendesc'] ?></div>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['birth'] ?></span></label><br />
<?php
$birth = isset($form['ch_birth']) ? forum_htmlencode($form['ch_birth']) : forum_htmlencode($user['ch_birth']);

?>
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="ch_birth1" value="<?php echo date("d", $birth) ?>" size="2" maxlength="2" oninput="this.value = this.value.replace(/\D/g, '')" style="width: 2em; text-align: center;"/>.<input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="ch_birth2" value="<?php echo date("m", $birth) ?>" size="2" maxlength="2" oninput="this.value = this.value.replace(/\D/g, '')" style="width: 2em; text-align: center;"/>.<input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="ch_birth3" value="<?php echo date("Y", $birth) ?>" size="2" maxlength="4" oninput="this.value = this.value.replace(/\D/g, '')" style="width: 4em; text-align: center;"/></span>
						<div class="description"><?php echo $lang_eni_profile['birthdesc'] ?></div>
					</div>
				</div>
			</fieldset>

			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_eni_profile['fields'][1] ?></strong></legend>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['metier'] ?></span></label><br />
						<span class="fld-input"><input type="text" id="fld<?php echo $forum_page['fld_count'] ?>" name="form[ch_metier]" value="<?php echo(isset($form['ch_metier']) ? forum_htmlencode($form['ch_metier']) : forum_htmlencode($user['ch_metier'])) ?>" size="35" maxlength="40" /></span>
						<div class="description"><?php echo $lang_eni_profile['metdesc'] ?></div>
					</div>
				</div>
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['fraction'] ?></span></label><br />
						<span class="fld-input">
<?php
$fract_list = array();

$query = array(
	'SELECT'	=> '*',
	'FROM'		=> 'fractions',
	'ORDER BY'	=> 'id'
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
while ($cur_fract = $forum_db->fetch_assoc($result)) {
	$fract_list[] = $cur_fract;
	if ($user['ch_fract_id'] == $cur_fract['id'])
		$fract = $cur_fract['id'];
}
$fract = isset($_POST['ch_fract_id']) ? $_POST['ch_fract_id'] : $fract;
?>
							<select id="fld<?php echo ++$forum_page['fld_count'] ?>" name="form[ch_fract_id]" onchange="fractchange(this)">
								<option value="">--</option>
<?php
for ($i = 0; $i < count($fract_list); $i++) {
	echo "\t\t\t\t\t\t\t\t".'<option value="'.$fract_list[$i]['id'].'"'.($fract == $fract_list[$i]['id'] ? ' selected="selected"' : '').'>'.$fract_list[$i]['name'].'</option>'."\n";
}
?>
							</select>
							<div class="description">
<?php
echo "\t\t\t\t\t\t\t\t".'<div class="fractdesc item"'.(empty($fract) ? ' style="display: block;"' : '').'>'.$lang_eni_profile['emptyfract'].'</div>';
for ($i = 0; $i < count($fract_list); $i++) {
	echo "\t\t\t\t\t\t\t\t".'<div class="fractdesc item'.$fract_list[$i]['id'].'"'.($fract == $fract_list[$i]['id'] ? ' style="display: block;"' : '').'>'.$fract_list[$i]['description'].'</div>';
}
?>
							</div>
						</span>
					</div>
				</div>
				
				<fieldset class="mf-set set<?php echo ++$forum_page['item_count'] ?>">
					<legend>
						<span><?php echo $lang_eni_profile['religion'] ?></span>
					</legend>
<?php
	$query = array(
			'SELECT'	=> 'god_id',
			'FROM'		=> 'religion',
			'WHERE'		=> 'user_id = '.$user['id']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$user['gods'] = array();
	while ($cur_god = $forum_db->fetch_assoc($result)) {
			$user['gods'][] = $cur_god['god_id'];
	}

	for ($i = 1; $i >= 0; $i--) {
		$query = array(
			'SELECT'	=> '*',
			'FROM'		=> 'gods',
			'WHERE'		=> 'primacy = '.$i,
			'ORDER BY'	=> 'id'
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

		$god_list = array();
		while ($cur_god = $forum_db->fetch_assoc($result)) {
			$god_list[] = $cur_god;
		}
		echo '<small>'.$lang_eni_profile['primacy'][$i].'</small>';    
		for ($k = 0; $k < count($god_list); $k++) {
			if (!empty($god_list[$k]['name'])) {
				echo '<div class="mf-item"><span class="fld-input"><input type="checkbox" id="fld'.++$forum_page['item_count'].'" name="god['.$god_list[$k]['id'].']" value="'.$god_list[$k]['id'].'"'.(!empty($form['ch_gods'][$k]) || in_array($god_list[$k]['id'], $user['gods']) ? ' checked="checked"' : '').'></span><label for="fld'.$forum_page['item_count'].'">'.$god_list[$k]['name'].'</label><div class="description">'.$god_list[$k]['description'].'</div></div>';
			}
		}
	}
?>
				</fieldset>
			</fieldset>

			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong><?php echo $lang_eni_profile['fields'][2] ?></strong></legend>
				<div class="txt-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="txt-box textarea">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['skills'] ?></span> <small><?php echo $lang_eni_profile['skilDesc'] ?></small></label>
						<div class="txt-input"><span class="fld-input"><textarea id="fld<?php echo $forum_page['fld_count'] ?>" name="ch_skills" rows="10" cols="65"><?php echo(isset($_POST['ch_skills']) ? forum_htmlencode($_POST['ch_skills']) : forum_htmlencode($user['ch_skills'])) ?></textarea></span></div>
					</div>
				</div>
				<div class="txt-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="txt-box textarea">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['person'] ?></span> <small><?php echo $lang_eni_profile['persDesc'] ?></small></label>
						<div class="txt-input"><span class="fld-input"><textarea id="fld<?php echo $forum_page['fld_count'] ?>" name="ch_person" rows="10" cols="65"><?php echo(isset($_POST['ch_person']) ? forum_htmlencode($_POST['ch_person']) : forum_htmlencode($user['ch_person'])) ?></textarea></span></div>
					</div>
				</div>
				<div class="txt-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="txt-box textarea">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['bio'] ?></span> <small><?php echo $lang_eni_profile['bioDesc'] ?></small></label>
						<div class="txt-input"><span class="fld-input"><textarea id="fld<?php echo $forum_page['fld_count'] ?>" name="ch_bio" rows="10" cols="65"><?php echo(isset($_POST['ch_bio']) ? forum_htmlencode($_POST['ch_bio']) : forum_htmlencode($user['ch_bio'])) ?></textarea></span></div>
					</div>
				</div>
				<div class="txt-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="txt-box textarea">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_eni_profile['extra'] ?></span> <small><?php echo $lang_eni_profile['extraDesc'] ?></small></label>
						<div class="txt-input"><span class="fld-input"><textarea id="fld<?php echo $forum_page['fld_count'] ?>" name="ch_extra" rows="10" cols="65"><?php echo(isset($_POST['ch_extra']) ? forum_htmlencode($_POST['ch_extra']) : forum_htmlencode($user['ch_extra'])) ?></textarea></span></div>
					</div>
				</div>
			</fieldset>

<?php ($hook = get_hook('pf_character_fieldset_end')) ? eval($hook) : null; ?>
			<div class="frm-buttons">
				<span class="submit primary"><input type="submit" name="update" value="<?php echo $lang_profile['Update profile'] ?>" /></span>
			</div>
		</form>
	</div>
<?php
		($hook = get_hook('pf_change_details_identity_end')) ? eval($hook) : null;

		$tpl_temp = forum_trim(ob_get_contents());
		$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
		ob_end_clean();
		// END SUBST - <!-- forum_main -->

		require FORUM_ROOT.'footer.php';
}