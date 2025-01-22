<?php

$disp = array(
	'<strong class="warn" data-section="user">'.$lang_eni_profile['identity'].'</strong>',
	'<strong data-section="char">'.$lang_eni_profile['character'].'</strong>'
);

($hook = get_hook('sat_eni_profile_display')) ? eval($hook) : null;

if (!defined('FORUM_PARSER_LOADED'))
	require FORUM_ROOT.'include/parser.php';

$forum_page['user_info'] = array('display' => '<li id="display-profile"><span>'.$lang_eni_profile['display'].': '.implode(' | ', $disp).'</span></li>') + $forum_page['user_info'];

if ($user['ch_name'] != '')
	$forum_page['user_info']['ch_name'] = '<li class="char"><span>'.$lang_eni_profile['ch_name'].': <strong>'.$user['ch_name'].'</strong></span></li>';

if ($user['ch_race_id'] != '') {
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'races',
		'WHERE'		=> 'id = '.$user['ch_race_id']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$ch_race = $forum_db->fetch_assoc($result);
	if ($ch_race['basis'] != '') {
		$query = array(
			'SELECT'	=> '*',
			'FROM'		=> 'races',
			'WHERE'		=> 'id = '.$ch_race['basis']
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$race = $forum_db->fetch_assoc($result)['name'].'/'.$ch_race['name'];
	} else {
		$race = $ch_race['name'];
	}

	$forum_page['user_info']['race'] = '<li class="char"><span>'.$lang_eni_profile['ch_race'].': <strong>'.$race.'</strong></span></li>';
}

if ($user['ch_gender'] != 0)
	$forum_page['user_info']['gender'] = '<li class="char"><span>'.$lang_eni_profile['gender'].': <strong>'.$lang_eni_profile['genders'][$user['ch_gender']].'</strong></span></li>';

if ($user['ch_birth'] != -62167219200)
	$forum_page['user_info']['birth'] = '<li class="char"><span>'.$lang_eni_profile['birth'].': <strong>'.format_time($user['ch_birth'], 1).'</strong></span></li>';

if ($user['ch_fract_id'] != '') {
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'fractions',
		'WHERE'		=> 'id = '.$user['ch_fract_id']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$fraction = $forum_db->fetch_assoc($result);

	$forum_page['society']['fraction'] = '<li class="char"><span>'.$lang_eni_profile['fraction'].': <strong>'.$fraction['name'].'</strong></span></li>';
}

if ($user['ch_metier'] != '') {
	$forum_page['society']['metier'] = '<li class="char"><span>'.$lang_eni_profile['metier'].': <strong>'.$user['ch_metier'].'</strong></span></li>';
}

$user['gods'] = array();
$query = array(
	'SELECT'	=> 'r.god_id, g.name',
	'FROM'		=> 'religion AS r',
	'JOINS'		=> array(
		array(
			'LEFT JOIN'	=> 'gods AS g',
			'ON'		=> 'g.id = r.god_id'
		)
	),
	'WHERE'		=> 'r.user_id = '.$user['id']
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
while ($god = $forum_db->fetch_assoc($result))
	$user['gods'][] = $god['name'];
if (!empty($user['gods']))
	$forum_page['society']['gods'] = '<li class="char"><span>'.$lang_eni_profile['religion'].': <strong>'.implode(', ', $user['gods']).'</strong></span></li>';

if ($user['ch_skills'] != '')
	$forum_page['skills'] = parse_message($user['ch_skills'], 0);

if ($user['ch_person'] != '')
	$forum_page['person'] = parse_message($user['ch_person'], 0);

if ($user['ch_bio'] != '')
	$forum_page['bio'] = parse_message($user['ch_bio'], 0);

if ($user['ch_extra'] != '')
	$forum_page['extra'] = parse_message($user['ch_extra'], 0);
