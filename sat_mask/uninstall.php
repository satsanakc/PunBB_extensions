<?php
if(!defined('SAT_MASK_UNINSTALL')) die();

if ($forum_db->field_exists('users', 'masks'))
	$forum_db->drop_field('users', 'masks');
	
if ($forum_db->field_exists('posts', 'mask_name'))
	$forum_db->drop_field('posts', 'mask_name');
if ($forum_db->field_exists('posts', 'mask_title'))
	$forum_db->drop_field('posts', 'mask_title');
if ($forum_db->field_exists('posts', 'mask_ava'))
	$forum_db->drop_field('posts', 'mask_ava');
if ($forum_db->field_exists('posts', 'mask_sig'))
	$forum_db->drop_field('posts', 'mask_sig');

if ($forum_db->table_exists('sat_masks'))
	$forum_db->drop_table('sat_masks');
