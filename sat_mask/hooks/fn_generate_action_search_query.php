<?php
if (!defined('FORUM')) die();

$query['SELECT'] .= ', pl.mask_name, ps.mask_name AS s_mask_name';
$query['JOINS'][]	= array(
	'LEFT JOIN'		=> 'posts AS pl',
	'ON'			=> 'pl.id=t.last_post_id'
);
$query['JOINS'][]	= array(
	'LEFT JOIN'		=> 'posts AS ps',
	'ON'			=> 'ps.id=t.first_post_id'
);