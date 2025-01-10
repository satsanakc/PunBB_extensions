<?php

    if (!defined('FORUM')) die();

    if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
	include $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
    else
	include $ext_info['path'].'/lang/English.php';

    global $forum_db;
    $query = array(
	'SELECT'	=> '*',
	'FROM'		=> 'sat_pages',
	'WHERE'		=> 'name=\'Index\''
    );
    $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
    $sat_page = $forum_db->fetch_assoc($result);

    if ($sat_page) {
	$links['index'] = '<li id="navindex"'.((FORUM_PAGE == 'index') ? ' class="isactive"' : '').'><a href="'.forum_link($forum_url['index']).'?forum=1">'.$lang_common['Index'].'</a></li>';
	array_insert($links, 0, '<li id="navhome"'.((FORUM_PAGE == 'home') ? ' class="isactive"' : '').'><a href="'.forum_link($forum_url['index']).'">'.$lang_sat_page['home'].'</a></li>', 'home');
    }