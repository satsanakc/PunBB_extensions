<?php

if(!defined('SAT_TOPICPOST_INSTALL')) die();

if (!$forum_db->field_exists('topics', 'pinned'))
	$forum_db->add_field('topics', 'pinned', 'INT(10)', false, 0);
	
if (!$forum_db->field_exists('posts', 'hidden_author'))
	$forum_db->add_field('posts', 'hidden_author', 'INT(10)', false, 0);