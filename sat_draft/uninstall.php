<?php
if(!defined('SAT_DRAFT_UNINSTALL')) die();

if ($forum_db->field_exists('users', 'drafts'))
	$forum_db->drop_field('users', 'drafts');
	
if ($forum_db->table_exists('sat_drafts'))
	$forum_db->drop_table('sat_drafts');
