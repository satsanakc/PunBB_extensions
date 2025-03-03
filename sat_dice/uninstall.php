<?php
if(!defined('SAT_DICE_UNINSTALL')) die();


if ($forum_db->table_exists('sat_dice'))
	$forum_db->drop_table('sat_dice');
if ($forum_db->table_exists('sat_dice_templ'))
	$forum_db->drop_table('sat_dice_templ');
