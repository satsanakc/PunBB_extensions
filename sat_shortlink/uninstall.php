<?php
if(!defined('SAT_SHORTLINK_UNINSTALL')) die();


if ($forum_db->table_exists('sat_shortlinks'))
	$forum_db->drop_table('sat_shortlinks');
