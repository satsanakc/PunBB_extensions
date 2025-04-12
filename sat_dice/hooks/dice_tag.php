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
	$forum_page['diceform_row']['faces'] .= '<option'.($dice['name'] == 'd2' ? ' selected' : '').' value="'.$dice['id'].'">'.$dice['name'].'</option>';
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
	global $cur_post, $forum_db, $forum_config, $base_url, $lang_sat_dice;
	if (empty($cur_post)) {
		$cur_post = array();
		$cur_post['id'] = !empty($cur_set) ? intval($cur_set['pid']) : 0;
	}
	if (!array_key_exists('dice', $cur_post)) {
		$cur_post['dice'] = array();
		if (!empty($cur_post['id'])) {
			$query = array(
				'SELECT'	=> 'd.*, u.username, u.avatar',
				'FROM'		=> 'sat_dice AS d',
				'WHERE'		=> 'd.post_id='.$cur_post['id'],
				'ORDER BY'	=> 'd.id',
				'JOINS'		=> array(
					array(
						'INNER JOIN'	=> 'users AS u',
						'ON'		=> 'u.id=d.user_id'
					)
				)
			);
			$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
			while ($dice = $forum_db->fetch_assoc($result)) {
				$cur_post['dice'][] = $dice;
			}
		}
		$cur_post['dice_conter'] = 0;
	}
	$d = array_key_exists($cur_post['dice_conter'], $cur_post['dice']) ? $cur_post['dice'][$cur_post['dice_conter']] : '';
	if (empty($d)) {
		return '</p><div class="quotebox"><blockquote><p>'.$lang_sat_dice['nodice'].'</p></blockquote></div><p>';
	} else {
		$ava = '';
		switch ($d['avatar']) {
			case FORUM_AVATAR_GIF:
				$ava = $base_url.'/'.$forum_config['o_avatars_dir'].'/'.$d['user_id'].'.gif';
				break;
			case FORUM_AVATAR_JPG:
				$ava = $base_url.'/'.$forum_config['o_avatars_dir'].'/'.$d['user_id'].'.jpg';
				break;
			case FORUM_AVATAR_PNG:
				$ava = $base_url.'/'.$forum_config['o_avatars_dir'].'/'.$d['user_id'].'.png';
				break;
			default:
				$ava = empty($forum_config['o_defava']) ? '' : $forum_config['o_defava'];
		}
		if ($d['faces'] == 3 && $d['dice_mod'] == -2)
			$d['type'] = 'fate';
		else if ($d['faces'] == 2 && $d['dice_mod'] == -1)
			$d['type'] = 'd01';
		else
			$d['type'] = 'd'.$d['faces'];
		$strres = '';
		$strdices = $d['username'].' кидает '
			.$d['num'].((($d['num']%100>10 && $d['num']%100<20) || $d['num']%10>=5 || $d['num']%10==0) ? ' костей ' : ($d['num']%10==1 ? ' кость ' : ' кости '))
			.$d['type']
			.($d['dice_mod'] != 0 ? ($d['dice_mod'] > 0 ? '+' : '').(($d['type'][0] == 'd' && $d['type'][1] != '0') ? $d['dice_mod'] : '') : '');
		if ($d['sum_mod'] != 0 && $d['min'] + $d['max'] + $d['sum'] > 0)
			$strdices .= ' c модификатором '.($d['sum_mod'] > 0 ? '+' : '').$d['sum_mod'];
		if ($d['diff'] != 0)
			$strdices .= ' против сложности '.$d['diff'];
		if (!empty($d['descr'])) {
			$strdices .= ', моделируя ситуацию';
			$strres .= '<p><em>'.forum_htmlencode($d['descr']).'</em></p>';
		}
		$strdices .= ':';
		$strres .= '<p class="'.$d['type'].($d['sum'] == 1 ? ' sum' : '').($d['min'] == 1 ? ' min' : '').($d['max'] == 1 ? ' max' : '').'">';
		$d['res'] = json_decode($d['res']);
		foreach ($d['res'] as $id=>$r) {
			$d['res'][$id] = '<span class="diceres'.' res'.$r.'">'.strval($r + $d['dice_mod']).'</span>';
			if(!isset($sum)) {
				$min = $max = $sum = $r + $d['dice_mod'];
			} else {
				$min = ($r + $d['dice_mod']) < $min ? $r + $d['dice_mod'] : $min;
				$max = ($r + $d['dice_mod']) > $max ? $r + $d['dice_mod'] : $max;
				$sum += $r + $d['dice_mod'];
			}
		}
		$strres .= implode('', $d['res']);
		$strres .= '</p>';
		if($d['min'] > 0) {
			$strres .= '<p class="minres">Минимальное значение: ';
			if($d['sum_mod'] != 0)
				$strres .= $min.($d['sum_mod'] > 0 ? '+' : '').$d['sum_mod'].' = ';
			$strres .= '<b>'.($min + $d['sum_mod']).'</b>';
			if($d['diff'] != 0)
				$strres .= ($min + $d['sum_mod'] < $d['diff'] ? ', это меньше установленной сложности ' : ($min + $d['sum_mod'] > $d['diff'] ? ', это больше установленной сложности ' : ', это равно установленной сложности ')).'<b>'.$d['diff'].'</b>';
			$strres .= '</p>';
		}
		if($d['max'] > 0) {
			$strres .= '<p class="minres">Максимальное значение: ';
			if($d['sum_mod'] != 0)
				$strres .= $max.($d['sum_mod'] > 0 ? '+' : '').$d['sum_mod'].' = ';
			$strres .= '<b>'.($max + $d['sum_mod']).'</b>';
			if($d['diff'] != 0)
				$strres .= ($max + $d['sum_mod'] < $d['diff'] ? ', это меньше установленной сложности ' : ($max + $d['sum_mod'] > $d['diff'] ? ', это больше установленной сложности ' : ', это равно установленной сложности ')).'<b>'.$d['diff'].'</b>';
			$strres .= '</p>';
		}
		if($d['sum'] > 0) {
			$strres .= '<p class="minres">Сумма: ';
			if($d['sum_mod'] != 0)
				$strres .= $sum.($d['sum_mod'] > 0 ? '+' : '').$d['sum_mod'].' = ';
			$strres .= '<b>'.($sum + $d['sum_mod']).'</b>';
			if($d['diff'] != 0)
				$strres .= ($sum + $d['sum_mod'] < $d['diff'] ? ', это меньше установленной сложности ' : ($sum + $d['sum_mod'] > $d['diff'] ? ', это больше установленной сложности ' : ', это равно установленной сложности ')).'<b>'.$d['diff'].'</b>';
			$strres .= '</p>';
		}
		$str = '</p><div id="dice'.$d['id'].'" class="quotebox dicebox"><cite>'.(!empty($ava) ? '<img class="citeava" src="'.$ava.'" alt="'.$d['username'].'"> ' : '').'<span class="citetime">'.format_time($d['thrown']).'</span> '.$strdices.'</cite><blockquote>'.$strres.'</blockquote></div><p>';
		($hook = get_hook('sat_dice_tag_end')) ? eval($hook) : null;
		$cur_post['dice_conter']++;
		return $str;
	}
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
