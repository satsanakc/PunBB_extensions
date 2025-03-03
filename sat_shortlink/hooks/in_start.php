<?php
if (!defined('FORUM')) die();

if(!empty($_GET['link'])) {
	$query = array(
		'SELECT'	=> 'id, url',
		'FROM'		=> 'sat_shortlinks',
		'WHERE'		=> 'link=\''.$_GET['link'].'\''
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$link = $forum_db->fetch_assoc($result);
	if(empty($link)) 
		redirect(forum_link('misc.php', ''), $lang_common['Redirecting']);
	else
		$query = array(
			'INSERT'	=> 'link_id, serv',
			'INTO'		=> 'sat_linksvisit',
			'VALUES'	=> strval($link['id']).", '".$forum_db->escape(json_encode($_SERVER))."'"
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$forum_db->end_transaction();
		$forum_db->close();
		redirect($link['url'], $lang_common['Redirecting']);
}
