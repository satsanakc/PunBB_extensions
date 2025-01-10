<?php 

if (!defined('FORUM')) die();

if (!$forum_user['is_guest'])
{
	if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
		include $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
	else
		include $ext_info['path'].'/lang/English.php';
	
	$pan_pm_unread_msg = count(pan_pm_get_unread_msg());
	if ($pan_pm_unread_msg == 0) $pan_pm_unread_msg = '';

	if ($pan_pm_unread_msg > 0)
		$visit_links['pan_pm'] = '<span class="pan-pm-link"><a href="'.forum_link($forum_url['pan_pm_inbox'], $forum_user['id']).'" title="'.$lang_pan_pm['new_msg_title'].'"><strong>'.$lang_pan_pm['pm'].'</strong></a></span>';
	else
		$visit_links['pan_pm'] = '<span class="pan-pm-link"><a href="'.forum_link($forum_url['pan_pm_inbox'], $forum_user['id']).'" title="'.$lang_pan_pm['go_to_pm'].'">'.$lang_pan_pm['pm'].'</a> </span>';
		
}
