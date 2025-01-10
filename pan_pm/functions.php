<?php

function pan_pm_get_unread_msg()
{
	global $forum_db, $forum_user;
	
	$unread_msg = array();
	$query = array(
		'SELECT'	=> 'id, status',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'receiver_id='.$forum_user['id'].' AND deleted_by_receiver=0'
	);
	
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	while ($rows = $forum_db->fetch_assoc($result)) {
		if ($rows['status'] == 'sent')
			$unread_msg[] = $rows['id'];
	}

	return $unread_msg;
}

global $forum_user;

if (file_exists($ext_info['path'].'/style/'.$forum_user['style'].'/'.$ext_info['id'].'.css'))
	$forum_loader->add_css($ext_info['url'].'/style/'.$forum_user['style'].'/'.$ext_info['id'].'.css', array('type' => 'url', 'media' => 'screen'));
else
	$forum_loader->add_css($ext_info['url'].'/style/Oxygen/'.$ext_info['id'].'.css', array('type' => 'url', 'media' => 'screen'));