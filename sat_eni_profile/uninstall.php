<?php

if(!defined('SAT_ENI_PROFILE_UNINSTALL')) die();

if ($forum_db->field_exists('users', 'ch_name'))
	$forum_db->drop_field('users', 'ch_name');
if ($forum_db->field_exists('users', 'ch_race_id'))
	$forum_db->drop_field('users', 'ch_race_id');
if ($forum_db->field_exists('users', 'ch_gender'))
	$forum_db->drop_field('users', 'ch_gender');
if ($forum_db->field_exists('users', 'ch_birth'))
	$forum_db->drop_field('users', 'ch_birth');
if ($forum_db->field_exists('users', 'ch_metier'))
	$forum_db->drop_field('users', 'ch_metier');
if ($forum_db->field_exists('users', 'ch_skills'))
	$forum_db->drop_field('users', 'ch_skills');
if ($forum_db->field_exists('users', 'ch_person'))
	$forum_db->drop_field('users', 'ch_person');
if ($forum_db->field_exists('users', 'ch_bio'))
	$forum_db->drop_field('users', 'ch_bio');
if ($forum_db->field_exists('users', 'ch_extra'))
	$forum_db->drop_field('users', 'ch_extra');
if ($forum_db->field_exists('users', 'showdesc'))
	$forum_db->drop_field('users', 'showdesc');

if ($forum_db->table_exists('races'))
	$forum_db->drop_table('races');
if ($forum_db->table_exists('gods'))
	$forum_db->drop_table('gods');
if ($forum_db->table_exists('religion'))
	$forum_db->drop_table('religion');
if ($forum_db->table_exists('fraction'))
	$forum_db->drop_table('fraction');
if ($forum_db->table_exists('char_log'))
	$forum_db->drop_table('char_log');