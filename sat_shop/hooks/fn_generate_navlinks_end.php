<?php

    if (!defined('FORUM')) die();

    global $forum_db;
    $query = array(
	'SELECT'	=> 'sat_category.id, sat_category.name',
	'FROM'		=> 'sat_category, sat_shop_goods',
	'WHERE'		=> 'sat_shop_goods.cat=sat_category.id'
    );
    $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
    $cat_list = array();
    while ($row = $forum_db->fetch_assoc($result)) {
	$cat_list[] = $row;
    }

    $cat_ul = '';
    for ($c=0; $c<count($cat_list); $c++) {
	$cat_ul .= '<li><a href="'.forum_link('misc.php?action=goods&gid='.$cat_list[$c]['id']).'">'.$cat_list[$c]['name'].'</a></li>';
    }

    if ($sat_page) {
	array_insert($links, 'index', '<li id="produce"'.((FORUM_PAGE == 'sat-category' || FORUM_PAGE == 'sat-goods') ? ' class="isactive"' : '').'>Продукция <ul>'.$cat_ul.'</ul></li>', 'produce');
    }