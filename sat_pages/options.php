<?php

if (!defined('FORUM_ROOT'))
	define('FORUM_ROOT', '../../');
	
require FORUM_ROOT.'include/common.php';
require FORUM_ROOT.'include/common_admin.php';

$forum_url['sat_pages'] = 'extensions/sat_pages/options.php';

if ($forum_user['g_id'] != FORUM_ADMIN)
	message($lang_common['No permission']);

($hook = get_hook('sat_pages_options_start')) ? eval($hook) : null;

// Load the admin.php language files
require FORUM_ROOT.'lang/'.$forum_user['language'].'/admin_common.php';
require FORUM_ROOT.'lang/'.$forum_user['language'].'/admin_index.php';

if (file_exists(FORUM_ROOT.'extensions/sat_pages/lang/'.$forum_user['language'].'.php'))
	require FORUM_ROOT.'extensions/sat_pages/lang/'.$forum_user['language'].'.php';
else
	require FORUM_ROOT.'extensions/sat_pages/lang/English.php';

// Set default crumbs
$forum_page['crumbs'] = array(
	array($forum_config['o_board_title'], forum_link($forum_url['index'])),
	array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
	$lang_sat_page['info_pages']
);


if(isset($_POST['new_cat_name'])) {
	$catden = isset($_POST['catdengroup']) ? "'".implode(',', $_POST['catdengroup'])."'" : 'NULL';
	$catann = isset($_POST['cat_announce']) ? $_POST['cat_announce'] : '0';
	$values = 'NULL, \''.$forum_db->escape($_POST['new_cat_name']).'\', '.$catann.', '.$_POST['catshow'].', '.$catden;

	$query = array(
		'INSERT'	=> 'id, name, announce, showcont, denyaccess',
		'INTO'		=> 'sat_category',
		'VALUES'	=> $values
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);


	$message = $lang_sat_page['catadded'];
	$forum_flash->add_info($message);
	redirect(forum_link($forum_url['sat_pages']), $message);
}

if(isset($_POST['edit_cat_name'])) {
	$catden = isset($_POST['catdengroup']) ? "'".implode(',', $_POST['catdengroup'])."'" : 'NULL';
	$catann = isset($_POST['cat_announce']) ? $_POST['cat_announce'] : '0';

	$query = array(
		'UPDATE'	=> 'sat_category',
		'SET'		=> 'name = \''.$forum_db->escape($_POST['edit_cat_name']).'\', announce = '.$catann.', showcont = '.$_POST['catshow'].', denyaccess = '.$catden,
		'WHERE'		=> 'id = '.$_POST['cid']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	$message = $lang_sat_page['saved'];
	$forum_flash->add_info($message);
	redirect(forum_link($forum_url['sat_pages']), $message);
}

if(isset($_POST['conf_del_cat'])) {
	$query = array(
		'UPDATE'	=> 'sat_pages',
		'SET'		=> 'category_id = NULL',
		'WHERE'		=> 'category_id = '.$_POST['cid']
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
	$query = array(
		'DELETE'	=> 'sat_category',
		'WHERE'		=> 'id='.$_POST['cid']
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	$message = $lang_sat_page['catdeleted'];
	$forum_flash->add_info($message);
	redirect(forum_link($forum_url['sat_pages']), $message);
}

if(isset($_POST['new_page_name'])) {
	$pagecat = $_POST['page_category'] ? $_POST['page_category'] : 'NULL';
	$pageden = isset($_POST['pagedengroup']) ? "'".implode(',', $_POST['pagedengroup'])."'" : 'NULL';
	$pageann = isset($_POST['page_announce']) ? $_POST['page_announce'] : '0';
	$pagetxt = "'".$forum_db->escape($_POST['page_txt'])."'";
	$values = 'NULL, \''.$forum_db->escape($_POST['new_page_name']).'\', '.$pagecat.', '.$pageann.', '.$pageden.', '.$pagetxt;

	$query = array(
		'INSERT'	=> 'id, name, category_id, announce, denyaccess, txt',
		'INTO'		=> 'sat_pages',
		'VALUES'	=> $values
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);


	$message = $lang_sat_page['pageadded'];
	$forum_flash->add_info($message);
	redirect(forum_link($forum_url['sat_pages']), $message);
}

if(isset($_POST['edit_page_name'])) {
	$sets = 'name = \''.$_POST['edit_page_name'].'\', ';
	$sets .= 'category_id = '.($_POST['page_category'] ? $_POST['page_category'] : 'NULL').', ';
	$sets .= 'announce = '.(isset($_POST['page_announce']) ? $_POST['page_announce'] : '0').', ';
	$sets .= 'denyaccess = '.(isset($_POST['pagedengroup']) ? "'".implode(',', $_POST['pagedengroup'])."'" : 'NULL').', ';
	$sets .= 'txt = \''.$forum_db->escape($_POST['page_txt']).'\'';

	$query = array(
		'UPDATE'	=> 'sat_pages',
		'SET'		=> $sets,
		'WHERE'		=> 'id = '.$_POST['pid']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	$message = $lang_sat_page['saved'];
	$forum_flash->add_info($message);
	redirect(forum_link($forum_url['sat_pages']), $message);
}

if(isset($_POST['conf_del_page'])) {
	$query = array(
		'DELETE'	=> 'sat_pages',
		'WHERE'		=> 'id='.$_POST['pid']
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);

	$message = $lang_sat_page['pagedeleted'];
	$forum_flash->add_info($message);
	redirect(forum_link($forum_url['sat_pages']), $message);
}


$query = array(
	'SELECT'	=> 'g_id, g_title',
	'FROM'		=> 'groups'
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
$sat_groups = array();
while ($row = $forum_db->fetch_assoc($result)) {
	$sat_groups[$row['g_id']] = $row['g_title'];
}

$query = array(
	'SELECT'	=> 'id, name',
	'FROM'		=> 'sat_category',
	'ORDER BY'	=> 'sat_category.id'
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
$cat_list = array();
while ($row = $forum_db->fetch_assoc($result)) {
	$cat_list[] = $row;
}

$forum_page['item_count'] = $forum_page['fld_count'] = 0;

define('FORUM_PAGE_SECTION', 'sat_pages');


if (isset($_GET['action'])) {
	$action = $_GET['action'];

	($hook = get_hook('sat_pages_add_new_action')) ? eval($hook) : null;

	if ($action == 'cdelete' && isset($_GET['cid'])) {
		define('FORUM_PAGE', 'admin-sat_pages_cdel');
		define('SAT_PAGES_CDELETE', 1);
		require 'incut/delete.php';
	}
	elseif ($action == 'pdelete' && isset($_GET['pid'])) {
		define('FORUM_PAGE', 'admin-sat_pages_pdel');
		define('SAT_PAGES_PDELETE', 1);
		require 'incut/delete.php';
	}
	elseif ($action == 'cedit' && isset($_GET['cid'])) {
		define('FORUM_PAGE', 'admin-sat_pages_cedit');
		define('SAT_PAGES_CEDIT', 1);

		$query = array(
			'SELECT'	=> '*',
			'FROM'		=> 'sat_category',
			'WHERE'		=> 'id='.$_GET['cid']
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$cat = $forum_db->fetch_assoc($result);
		$cat['denyaccess'] = $cat['denyaccess'] ? explode (',', $cat['denyaccess']) : [''];

		require 'incut/catform.php';
	}
	elseif ($action == 'pedit' && isset($_GET['pid'])) {
		define('FORUM_PAGE', 'admin-sat_pages_pedit');
		define('SAT_PAGES_PEDIT', 1);

		$query = array(
			'SELECT'	=> '*',
			'FROM'		=> 'sat_pages',
			'WHERE'		=> 'id='.$_GET['pid']
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$page = $forum_db->fetch_assoc($result);
		$page['denyaccess'] = $page['denyaccess'] ? explode (',', $page['denyaccess']) : [''];

		require 'incut/pageform.php';
	}
	else message($lang_common['Bad request']);
}

elseif (isset($_GET['section'])) {
	$section = $_GET['section'];

	($hook = get_hook('sat_pages_add_new_section')) ? eval($hook) : null;

	if ($section == 'newcat') {
		define('FORUM_PAGE', 'admin-sat_pages_newcat');
		define('SAT_PAGES_CADD', 1);

		require 'incut/catform.php';
	}
	elseif ($section == 'newpage') {
		define('FORUM_PAGE', 'admin-sat_pages_newpage');
		define('SAT_PAGES_PADD', 1);
		require 'incut/pageform.php';
	}
	else message($lang_common['Bad request']);
}

else {
	define('FORUM_PAGE', 'admin-sat_pages');
	define('SAT_PAGES_PLIST', 1);
	require 'incut/plist.php';
}

($hook = get_hook('sat_pages_options_end')) ? eval($hook) : null;

$tpl_temp = forum_trim(ob_get_contents());
$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <!-- forum_main -->

require FORUM_ROOT.'footer.php';