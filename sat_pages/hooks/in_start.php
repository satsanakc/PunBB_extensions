<?php

    if (!defined('FORUM')) die();

    if (!isset($_GET['forum'])) {
	$query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'sat_pages',
		'WHERE'		=> 'name=\'Index\''
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$sat_page = $forum_db->fetch_assoc($result);

	if ($sat_page) {
		$action = 'page';
		$sat_pid = $sat_page['id'];
		$url = FORUM_ROOT.'misc.php';

		$sat_load = file_get_contents($url);
		$sat_load = preg_replace('/\<\?php/', '', $sat_load, 1);
		$sat_load = preg_replace('/require FORUM_ROOT\.\'include\/common\.php\';/', '', $sat_load, 1);
		eval ($sat_load);
		die ();
	}
    }