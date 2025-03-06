<?php
if (!defined('FORUM')) die();

function throwgen($m) {
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
		if(!empty($dice['dice_mod']))
			$m[10] = $dice['dice_mod'];
		if(!empty($dice['min']))
			$m[6] = $dice['min'];
		if(!empty($dice['max']))
			$m[7] = $dice['max'];
		if(!empty($dice['sum']))
			$m[8] = $dice['sum'];
		if(!empty($dice['descr']))
			$m[9] = $dice['descr'];
	}
	$post_info['dice'][] = $m;
	return '[dice]';
}

if (empty($post_info))
	$post_info = arrey();
$post_info['dice'] = arrey();
$re = '#\[roll=(\d+),(\d+),(\d+),(-?\d+),(-?\d+),([01]),([01]),([01]),([^\]]*?)]#';
if (empty($post_info['message']))
 	$message = preg_replace_callback($re, 'throwgen', $message);
else
	$post_info['message'] = preg_replace_callback($re, 'throwgen', $post_info['message']);
