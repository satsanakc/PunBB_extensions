<?php if (!defined('FORUM')) die();

if ($section == 'character') {
	$form = extract_elements(array('ch_name', 'ch_race_id', 'ch_gender', 'ch_metier', 'showdesc'));
	$date = new DateTimeImmutable();
	$form['ch_birth'] = date_timestamp_get($date->setDate($_POST['ch_birth3'], $_POST['ch_birth2'], $_POST['ch_birth1']));
	$form['ch_skills'] = forum_linebreaks(forum_trim($_POST['ch_skills']));
	$form['ch_person'] = forum_linebreaks(forum_trim($_POST['ch_person']));
	$form['ch_bio'] = forum_linebreaks(forum_trim($_POST['ch_bio']));
	$form['ch_extra'] = forum_linebreaks(forum_trim($_POST['ch_extra']));
	$form['showdesc'] = empty($form['showdesc']) ? 0 : 1;
	$form['ch_race_id'] = !empty($form['ch_race_id']) ? $form['ch_race_id'] : $_POST['ch_race_id'];
	$schema = array(
		'DELETE'	=> 'religion',
		'WHERE'		=> 'user_id = '.$user['id']
	);
	$forum_db->query_build($schema);
	foreach ($_POST['god'] as $god) {
		$schema = array(
			'INSERT'	=> 'user_id, god_id',
			'INTO'		=> 'religion',
			'VALUES'	=> strval($user['id']).', '.$god
		);
	$forum_db->query_build($schema);
	}
}