<?php 

if(!defined('PAN_PM_UNINSTALL')) die();

if ($forum_db->table_exists('pan_pm') )
	$forum_db->drop_table('pan_pm');

forum_config_remove(array(
	'o_pan_pm_max_num_msg',//
	'o_pan_pm_max_lenght_msg',//
	
	'o_pan_pm_max_inbox',
	'o_pan_pm_max_outbox',
	'o_pan_pm_global_link',
	'o_pan_pm_active_contacts',
));

if ($forum_db->field_exists('users', 'pan_pm_admin_off'))
	$forum_db->drop_field('users', 'pan_pm_admin_off');

if ($forum_db->field_exists('users', 'pan_pm_admin_disable'))
	$forum_db->drop_field('users', 'pan_pm_admin_disable');

if ($forum_db->field_exists('users', 'enable_pm_email'))
	$forum_db->drop_field('users', 'enable_pm_email');