<?php
if (!defined('FORUM')) die();

global $forum_db;

$query = array(
	'SELECT'	=> 'id, name',
	'FROM'		=> 'sat_dice_templ',
	'ORDER BY'	=> 'id'
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
$dices = array();
while ($cur_dice = $forum_db->fetch_assoc($result)) {
	$dices[] = $cur_dice;
}

$forum_page['diceform_row'] = array();
$forum_page['diceform_row']['num'] = '<p id="sat_dice_num_row">'.$lang_sat_dice['num'].' <input name="num" type="number" min="1" max="1000" value="1"></p>';
$forum_page['diceform_row']['faces'] = '<p id="sat_dice_faces_row">'.$lang_sat_dice['faces'].' <select size="1" name="faces">';
foreach ($dices as $dice) {
	$forum_page['diceform_row']['faces'] .= '<option value="'.$dice['id'].'">'.$dice['name'].'</option>';
}
$forum_page['diceform_row']['faces'] .= '<option value="0">--</option></select></p>';
$forum_page['diceform_row']['afaces'] = '<p id="sat_dice_afaces_row">'.$lang_sat_dice['afaces'].' <input name="afaces" type="number" min="1" max="10000" value="1"></p>';
$forum_page['diceform_row']['mod'] = '<p id="sat_dice_mod_row">'.$lang_sat_dice['mod'].' <input name="mod" type="number" value="0"></p>';
$forum_page['diceform_row']['diff'] = '<p id="sat_dice_diff_row" class="extra">'.$lang_sat_dice['diff'].' <input name="diff" type="number" value="0"></p>';
$forum_page['diceform_row']['desc'] = '<p id="sat_dice_desc_row" class="extra">'.$lang_sat_dice['desc'].' <input name="desc" type="text" value="" placeholder="'.$lang_sat_dice['descleg'].'"></p>';
$forum_page['diceform_row']['show'] = '<p id="sat_dice_show_row" class="extra">'.$lang_sat_dice['show'].' <input name="min" type="checkbox"> '.$lang_sat_dice['min'].' <input name="max" type="checkbox"> '.$lang_sat_dice['max'].' <input name="sum" type="checkbox" checked=""> '.$lang_sat_dice['sum'].'</p>';

$forum_page['diceform_button'] = array();
$forum_page['diceform_button']['roll'] = '<span id="sat_dice_throw_but" class="submit primary"><input type="button" value="'.$lang_sat_dice['throw'].'" onclick="diceRoll()"></span>';
$forum_page['diceform_button']['extra'] = '<span id="sat_dice_extra_but" class="button"><input type="button" value="'.$lang_sat_dice['extra'].'" onclick="$(\'#bbcode_dice .extra\').toggle()"></span>';

($hook = get_hook('sat_dice_form_modify')) ? eval($hook) : null;

function handle_dice_tag() {
	($hook = get_hook('sat_dice_tag_end')) ? eval($hook) : null;
	return '</p><div class="quotebox"><blockquote><p>[dice]</p></blockquote></div><p>';
}

$sat_bbcodes['dice'] = array(
	'title'		=>	$lang_sat_dice['title'],
	'value'		=>	'&#xf522',
	'group'		=>	'04media',
	'weight'	=>	10,
	'block'		=>	true,
	'endtag'	=>	false,

	'box'		=>	'<fieldset class="frm-group">'.(implode('', $forum_page['diceform_row'])).'</fieldset><div class="frm-buttons">'.(implode(' ', $forum_page['diceform_button'])).'</div>',
	'pattern'	=>	array('#\[dice\]#ms'),
	'replace'	=>	array('".handle_dice_tag()."')
);

($hook = get_hook('sat_dice_bb_modify')) ? eval($hook) : null;
