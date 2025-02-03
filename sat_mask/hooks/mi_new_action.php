<?php
if ($action == 'setmask' && isset($_GET['pid'])) {
	if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
		require $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
	else
		require $ext_info['path'].'/lang/Russian.php';

	if (isset($_POST['cancel'])) {
		$forum_flash->add_info($lang_sat_mask['cancelMark']);
		redirect(forum_link($forum_url['post'], $_GET['pid']), $lang_sat_mask['cancelMark']);
	}
}