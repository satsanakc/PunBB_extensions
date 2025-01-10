<?php

if(!defined('SAT_CONTACT_INSTALL')) die();

if (!$forum_db->field_exists('users', 'vk'))
	$forum_db->add_field('users', 'vk', 'VARCHAR(100)', true, NULL);
	
if (!$forum_db->field_exists('users', 'ok'))
	$forum_db->add_field('users', 'ok', 'VARCHAR(100)', true, NULL);
	
if (!$forum_db->field_exists('users', 'discord'))
	$forum_db->add_field('users', 'discord', 'VARCHAR(100)', true, NULL);
	
if (!$forum_db->field_exists('users', 'telegram'))
	$forum_db->add_field('users', 'telegram', 'VARCHAR(100)', true, NULL);