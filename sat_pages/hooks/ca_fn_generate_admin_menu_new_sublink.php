<?php
if (!defined('FORUM')) die();

if (FORUM_PAGE_SECTION == 'sat_pages' && $forum_user['g_id'] == FORUM_ADMIN) {

	if (file_exists(FORUM_ROOT.'extensions/sat_pages/lang/'.$forum_user['language'].'.php'))
		require FORUM_ROOT.'extensions/sat_pages/lang/'.$forum_user['language'].'.php';
	else
		require FORUM_ROOT.'extensions/sat_pages/lang/English.php';
	
	$forum_page['admin_submenu']['sat_pages'] = '<li class="'.((FORUM_PAGE == 'admin-sat_pages') ? 'active' : 'normal').((empty($forum_page['admin_submenu'])) ? ' first-item' : '').'"><a href="'.forum_link($forum_url['sat_pages']).'">'.$lang_sat_page['options'].'</a></li>';

	$forum_page['admin_submenu']['sat_pages_newcat'] = '<li class="'.((FORUM_PAGE == 'admin-sat_pages_newcat') ? 'active' : 'normal').((empty($forum_page['admin_submenu'])) ? ' first-item' : '').'"><a href="'.forum_link($forum_url['sat_pages']).'?section=newcat">'.$lang_sat_page['newcat'].'</a></li>';

	$forum_page['admin_submenu']['sat_pages_newpage'] = '<li class="'.((FORUM_PAGE == 'admin-sat_pages_newpage') ? 'active' : 'normal').((empty($forum_page['admin_submenu'])) ? ' first-item' : '').'"><a href="'.forum_link($forum_url['sat_pages']).'?section=newpage">'.$lang_sat_page['newpage'].'</a></li>';

	
	($hook = get_hook('sat_pages_ca_fn_generate_admin_menu_new_sublink')) ? eval($hook) : null;
}

