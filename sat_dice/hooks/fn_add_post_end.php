<?php
if (!defined('FORUM')) die();

$pid = empty($new_pid) ? $id : $new_pid;
foreach ($GLOBALS['sat_dice_in_current_post'] as $m) {
	for ($i = 0, $res = array(); $i < $m[1]; $i++) {
		$res[] = mt_rand(1, $m[3]);
	}
	$str = strval(time()).', '.$forum_user['id'].', '.$pid
		.= ', '.$m[1].', '.$m[3].', '.$m[10].', '.$m[4]
		.= ', '.$m[5].', '.$m[6].', '.$m[7].', '.$m[8]
		.= ", '".$forum_db->escape($m[9])."', '\[".implode(', ', $res)."\]'";
	$query = array(
		'INSERT'	=> 'thrown, user_id, post_id, num, faces, dice_mod, sum_mod, diff, min, max, sum, descr, res',
		'INTO'		=> 'sat_dice',
		'VALUES'	=> $str
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
}
