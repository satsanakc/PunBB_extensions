<?php if (!defined('FORUM')) die();

if ($action == 'satimgrec') {if (isset($_POST['image_id'])) {
	$query = array(
		'DELETE'	=> 'sat_images',
		'WHERE'		=> 'uid='.$forum_user['id']." AND image_id='".$_POST['image_id']."'"
	);
print_r($forum_db->query_build($query, true));
	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	$query = array(
		'INSERT'	=> 'uid, image_id, url, permalink_url, thumb_url, type',
		'INTO'		=> 'sat_images',
		'VALUES'	=> $forum_user['id'].", '".$_POST['image_id']."', '".$_POST['url']."', '".$_POST['permalink_url']."', '".$_POST['thumb_url']."', '".$_POST['type']."'"
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_db->end_transaction();
	$forum_db->close();
	echo $result;
	exit;
}}