<?php

if(!defined('SAT_CONTACT_UNINSTALL')) die();

if ($forum_db->field_exists('users', 'discord'))
	$forum_db->drop_field('users', 'discord');
	
if ($forum_db->field_exists('users', 'telegram'))
	$forum_db->drop_field('users', 'telegram');
	
if ($forum_db->field_exists('users', 'vk'))
	$forum_db->drop_field('users', 'vk');
	
if ($forum_db->field_exists('users', 'ok'))
	$forum_db->drop_field('users', 'ok');