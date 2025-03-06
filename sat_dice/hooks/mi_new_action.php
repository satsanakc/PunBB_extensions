<?php
if (!defined('FORUM')) die();

if ($action == 'throwedit') {
	$query = array(
		'UPDATE'	=> 'sat_dice',
		'SET'		=> '',
		'WHERE'		=> 'id='.$_POST['id']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_db->end_transaction();
	$forum_db->close();
	echo $res;
	exit;
}
