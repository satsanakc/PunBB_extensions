<?php
if ($action == 'createlink') {
	$linkchars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$link = substr(str_shuffle($linkchars), 0, 6);
	$str = time().', '.$forum_user['id'].", '".$forum_db->escape($_POST['url'])."', '".$link."'";
	$query = array(
		'INSERT'	=> 'generated, user_id, url, link',
		'INTO'		=> 'sat_shortlinks',
		'VALUES'	=> $str
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_db->end_transaction();
	$forum_db->close();
	echo $link;
	exit;
} else if ($action == 'gentoken') {
	send_json(array(
		'link' => forum_link('misc.php?action='.$_GET['act']),
		'token' => generate_form_token(forum_link('misc.php?action='.$_GET['act']))
	));
	exit;
}
