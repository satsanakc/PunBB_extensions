<?php

if(!defined('SAT_TOPICPOST_UNINSTALL')) die();

if ($forum_db->field_exists('topics', 'pinned'))
	$forum_db->drop_field('topics', 'pinned');
	
if ($forum_db->field_exists('posts', 'hidden_author'))
	$forum_db->drop_field('posts', 'hidden_author');