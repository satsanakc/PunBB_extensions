<?php
if (!defined('FORUM')) die();

function throwgen($m) {
	global $forum_db;
	$m[10] = 0;
	if ($m[2] != '0') {
		$query = array(
			'SELECT'	=> '*',
			'FROM'		=> 'sat_dice_templ',
			'WHERE'		=> 'id='.$m[2]
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$dice = $forum_db->fetch_assoc($result);
		if(!empty($dice['num']))
			$m[1] = $dice['num'];
		if(!empty($dice['faces']))
			$m[3] = $dice['faces'];
		if(isset($dice['dice_mod']))
			$m[10] = $dice['dice_mod'];
		if(isset($dice['min']))
			$m[6] = $dice['min'];
		if(isset($dice['max']))
			$m[7] = $dice['max'];
		if(isset($dice['sum']))
			$m[8] = $dice['sum'];
		if(isset($dice['descr']))
			$m[9] = $dice['descr'];
	}
	$search = array('&#91;', '&#93;');
	$replace = array('[', ']');
	$m[9] = str_replace($search, $replace, $m[9]);
	$GLOBALS['sat_dice_in_current_post'][] = $m;
	return '[dice]';
}

$GLOBALS['sat_dice_in_current_post'] = array();
$re = '#\[roll=(\d+),(\d+),(\d+),(-?\d+),(-?\d+),([01]),([01]),([01]),([^\]]*?)]#';
if (empty($post_info['message'])) {
 	$message = preg_replace_callback($re, 'throwgen', $message);
} else {
	$post_info['message'] = preg_replace_callback($re, 'throwgen', $post_info['message']);
}

if(empty($post_info['message'])) {
	$query = array(
		'SELECT'	=> 'COUNT(id)',
		'FROM'		=> 'sat_dice',
		'WHERE'		=> 'post_id='.$id
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$count_dice = $forum_db->result($result);
	$num_dice = preg_match_all('#\[dice]#', $message);
	if($count_dice > $num_dice) {
		for($i = 0; $i < ($count_dice - $num_dice); $i++) {
			$message .= "\n[dice]";
		}
	}
}
