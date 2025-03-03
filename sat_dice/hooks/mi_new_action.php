<?php
if ($action == 'throwgen') {
	for ($i = 0, $res = array(); $i < $_POST['num']; $i++) {
		$res[] = mt_rand(1, $_POST['faces']);
	}
	$str = strval(time()).', '.$_POST['uid'].', '.$_POST['tid'].', '.$_POST['pid']
		.= ', '.$_POST['num'].', '.$_POST['faces'].', '.$_POST['dice_mod'].', '.$_POST['sum_mod']
		.= ', '.$_POST['diff'].', '.$_POST['min'].', '.$_POST['max'].', '.$_POST['sum']
		.= ", '".$forum_db->escape($_POST['desc'])."', '[".implode(', ', $res)."]'";
	$query = array(
		'INSERT'	=> 'thrown, user_id, topic_id, post_id, num, faces, dice_mod, sum_mod, diff, min, max, sum, desc, res',
		'INTO'		=> 'sat_dice',
		'VALUES'	=> $str
	);
//	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_db->end_transaction();
	$forum_db->close();
	echo $res;
	exit;
} else if ($action == 'throwedit') {
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