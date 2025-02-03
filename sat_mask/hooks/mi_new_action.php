<?php
if ($action == 'setmask' && isset($_GET['pid'])) {
	if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
		require $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
	else
		require $ext_info['path'].'/lang/Russian.php';

	if (isset($_POST['cancel'])) {
		$forum_flash->add_info($lang_sat_mask['cancelMark']);
		redirect(forum_link($forum_url['post'], $_GET['pid']), $lang_sat_mask['cancelMark']);
	} else if (isset($_POST['remove'])) {
		$query = array(
			'UPDATE'	=> 'posts',
			'SET'		=> 'mask_name=NULL, mask_title=NULL, mask_ava=NULL, mask_sig=NULL',
			'WHERE'		=> 'id='.$_GET['pid']
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$forum_flash->add_info($lang_sat_mask['removeMark']);
		redirect(forum_link($forum_url['post'], $_GET['pid']), $lang_sat_mask['removeMark']);
	} else if (isset($_POST['submit'])) {
		$query = array(
			'UPDATE'	=> 'posts',
			'SET'		=> '',
			'WHERE'		=> 'id='.$_GET['pid']
		);
		$query['SET'] .= !$_POST['maskname'] ? 'mask_name=NULL' : 'mask_name=\''.$forum_db->escape($_POST['maskname']).'\'';
		$query['SET'] .= !$_POST['masktitle'] ? ', mask_title=NULL' : ', mask_title=\''.$forum_db->escape($_POST['masktitle']).'\'';
		$query['SET'] .= !$_POST['maskava'] ? ', mask_ava=NULL' : ', mask_ava=\''.$forum_db->escape($_POST['maskava']).'\'';
		$query['SET'] .= !$_POST['masksig'] ? ', mask_sig=NULL' : ', mask_sig=\''.$forum_db->escape(forum_linebreaks(forum_trim($_POST['masksig']))).'\'';
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$forum_flash->add_info($lang_sat_mask['submitMark']);
		redirect(forum_link($forum_url['post'], $_GET['pid']), $lang_sat_mask['submitMark']);
	} else {
		message($lang_common['Bad request']);
	}
}