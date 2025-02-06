<?php
if ($action == 'getsavedmess') {
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'sat_drafts',
		'WHERE'		=> 'user_id='.$_POST['uid']
	);
	if (!empty($_POST['tid']))
		$query['WHERE'] .= ' AND topic_id='.$_POST['tid'];
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$mess = array();
	while ($cur_mess = $forum_db->fetch_assoc($result))
	{
		$mess[] = $cur_mess;
	}
	send_json($mess);
	exit;
} else if ($action == 'savemess') {
	$query = array(
		'DELETE'	=> 'sat_drafts',
		'WHERE'		=> 'user_id='.$_POST['uid'].' AND '
	);
	if (!empty($_POST['tid']))
		$query['WHERE'] .= 'topic_id='.$_POST['tid'];
	if (!empty($_POST['name']))
		$query['WHERE'] .= 'save_name=\''.$forum_db->escape($_POST['name']).'\'';
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	$query = array(
		'INSERT'	=> 'save_name, user_id, topic_id, message',
		'INTO'		=> 'sat_drafts',
		'VALUES'	=> (empty($_POST['name']) ? 'NULL' : '\''.$forum_db->escape($_POST['name']).'\'').', '.$_POST['uid'].', '.(empty($_POST['tid']) ? 'NULL' : $_POST['tid']).', \''.$forum_db->escape($_POST['mess']).'\''
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_db->end_transaction();
	$forum_db->close();
	echo $result;
	exit;
} else if ($action == 'gentoken') {
	send_json(array(
		'link' => forum_link('misc.php?action='.$_GET['act']),
		'token' => generate_form_token(forum_link('misc.php?action='.$_GET['act']))
	));
	exit;
}
