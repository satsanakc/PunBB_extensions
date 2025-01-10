<?php

if (!defined('FORUM')) die();

$section = isset($_GET['section']) ? $_GET['section'] : null;
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
$date = isset($_GET['d']) ? intval($_GET['d']) : 0;//????

// Load the post.php language file
include FORUM_ROOT.'lang/'.$forum_user['language'].'/post.php';

if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
	include $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
else
	include $ext_info['path'].'/lang/English.php';

if (isset($_POST['pan_pm_sent']))
{
	$req_uid = intval($_POST['req_uid']);
	$req_tid = isset($_POST['req_tid']) ? intval($_POST['req_tid']) : 0;
	$req_sticky = isset($_POST['req_sticky']) ? intval($_POST['req_sticky']) : 0;
	$uid = ($req_uid > 0) ? $req_uid : $uid;
	
	$query = array(
		'SELECT'	=> 'username',
		'FROM'		=> 'users',
		'WHERE'		=> 'id='.$req_uid,
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$receiver = $forum_db->fetch_assoc($result);
	
	$subject = ($_POST['req_subject'] != '') ? forum_trim($_POST['req_subject']) : $lang_pan_pm['no_subject'];
	
	if (utf8_strlen($subject) > FORUM_SUBJECT_MAXIMUM_LENGTH)
		$errors[] = sprintf($lang_post['Too long subject'], FORUM_SUBJECT_MAXIMUM_LENGTH);
	
	// Clean up message from POST
	$message = forum_linebreaks(forum_trim($_POST['req_message']));

	if (strlen($message) > FORUM_MAX_POSTSIZE_BYTES)
		$errors[] = sprintf($lang_post['Too long message'], forum_number_format(strlen($message)), forum_number_format(FORUM_MAX_POSTSIZE_BYTES));
	else if ($forum_config['p_message_all_caps'] == '0' && check_is_all_caps($message) && !$forum_page['is_admmod'])
		$errors[] = $lang_post['All caps message'];

	// Validate BBCode syntax
	if ($forum_config['p_message_bbcode'] == '1' || $forum_config['o_make_links'] == '1')
	{
		if (!defined('FORUM_PARSER_LOADED'))
			require FORUM_ROOT.'include/parser.php';

		$message = preparse_bbcode($message, $errors);
	}

	if ($message == '')
		$errors[] = $lang_post['No message'];
	
	// Did everything go according to plan?
	if (empty($errors) && !isset($_POST['preview']))
	{
		$query = array(
			'INSERT'	=> 'topic_id, sender, sender_id, receiver, receiver_id, subject, message, posted, status, sticky_for_sender',
			'INTO'		=> 'pan_pm',
			'VALUES'	=> ''.$req_tid.', \''.$forum_db->escape($forum_user['username']).'\', '.$forum_user['id'].', \''.$forum_db->escape($receiver['username']).'\', '.$req_uid.', \''.$forum_db->escape($subject).'\', \''.$forum_db->escape($message).'\', '.time().', \'sent\', '.$req_sticky
		);

		($hook = get_hook('pan_pm_mi_new_action_insert_new')) ? eval($hook) : null;
		$forum_db->query_build($query) or error(__FILE__, __LINE__);
		$new_id = $forum_db->insert_id();
		
		if ($req_tid == 0)
		{
			$req_tid = $new_id;
			$query = array(
				'UPDATE'	=> 'pan_pm',
				'SET'		=> 'topic_id='.$req_tid,
				'WHERE'		=> 'id='.$new_id
			);
			($hook = get_hook('pan_pm_mi_new_action_update_new')) ? eval($hook) : null;
			$forum_db->query_build($query) or error(__FILE__, __LINE__);
		}

		($hook = get_hook('pan_pm_mi_new_action_insert_pre_redirect')) ? eval($hook) : null;
		$forum_flash->add_info($lang_pan_pm['msg_sent']);
		redirect(forum_link($forum_url['pan_pm_outbox'], $forum_user['id']), $lang_pan_pm['msg_sent']);
	}
}
else if (isset($_POST['save_edited']))
{
	$req_pid = isset($_POST['req_pid']) ? intval($_POST['req_pid']) : 0;
	$req_sticky = isset($_POST['req_sticky']) ? intval($_POST['req_sticky']) : 0;
	
	// Clean up message from POST
	$message = forum_linebreaks(forum_trim($_POST['req_message']));

	if (strlen($message) > FORUM_MAX_POSTSIZE_BYTES)
		$errors[] = sprintf($lang_post['Too long message'], forum_number_format(strlen($message)), forum_number_format(FORUM_MAX_POSTSIZE_BYTES));
	else if ($forum_config['p_message_all_caps'] == '0' && check_is_all_caps($message) && !$forum_page['is_admmod'])
		$errors[] = $lang_post['All caps message'];

	// Validate BBCode syntax
	if ($forum_config['p_message_bbcode'] == '1' || $forum_config['o_make_links'] == '1')
	{
		if (!defined('FORUM_PARSER_LOADED'))
			require FORUM_ROOT.'include/parser.php';

		$message = preparse_bbcode($message, $errors);
	}

	if ($message == '')
		$errors[] = $lang_post['No message'];
	
	if (empty($errors) && !isset($_POST['preview']))
	{
		$query = array(
			'UPDATE'	=> 'pan_pm',
			'SET'		=> 'message=\''.$forum_db->escape($message).'\', sticky_for_sender='.$req_sticky,
			'WHERE'		=> 'id='.$req_pid
		);
		($hook = get_hook('pan_pm_mi_new_action_update_edited')) ? eval($hook) : null;
		$forum_db->query_build($query) or error(__FILE__, __LINE__);
		
		$forum_flash->add_info($lang_pan_pm['msg_sent']);
		redirect(forum_link($forum_url['pan_pm_outbox']), $lang_pan_pm['msg_sent']);
	}
}
else if (isset($_POST['delete']))
{
	$pms = array_map('trim', $_POST['pms']);
	
	foreach ($pms as $key => $val)
	{
		$query = array(
			'UPDATE'	=> 'pan_pm',
			'SET'		=> 'deleted_by_sender=1',
			'WHERE'		=> 'id='.$val.' AND sticky_for_sender!=1 AND sender_id='.$forum_user['id']
		);
		$forum_db->query_build($query) or error(__FILE__, __LINE__);
		
		$query = array(
			'UPDATE'	=> 'pan_pm',
			'SET'		=> 'deleted_by_receiver=1',
			'WHERE'		=> 'id='.$val.' AND sticky_for_receiver!=1 AND receiver_id='.$forum_user['id']
		);
		$forum_db->query_build($query) or error(__FILE__, __LINE__);
	}
	
	$query = array(
		'DELETE'	=> 'pan_pm',
		'WHERE'		=> 'deleted_by_sender=1 AND deleted_by_receiver=1'
	);
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	$forum_flash->add_info($lang_pan_pm['deleted']);
	
	if ($action == 'outbox')
		redirect(forum_link($forum_url['pan_pm_outbox'], $forum_user['id']), $lang_pan_pm['deleted']);
	else
		redirect(forum_link($forum_url['pan_pm_inbox'], $forum_user['id']), $lang_pan_pm['deleted']);
}

$forum_page['group_count'] = $forum_page['item_count'] = $forum_page['fld_count'] = 0;

$pan_check_all_js = '
function checkOne(obj)
{
	var items=obj.form.getElementsByTagName("input"), len, i;
	for(i=0, len=items.length; i<len; i+=1){
		if(items.item(i).type && items.item(i).type==="checkbox"){
			if(items.item(i).checked){
				$("#pms"+i).addClass("selected");
			}else{
				$("#pms"+i).removeClass("selected");
			}
		}
	}
}
function checkAll(obj){
	var items=obj.form.getElementsByTagName("input"), len, i; 
	for(i=0, len=items.length; i<len; i+=1){
		if(items.item(i).type && items.item(i).type==="checkbox"){
			if(obj.checked){
				items.item(i).checked=true;
			}else{
				items.item(i).checked=false;
			}
		}
	}
	checkOne(obj);
}';


$pan_sorting_js = 'function panGetUrl(){var v={};var parts=window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value){v[key]=value;}); return v;} function panSortByDate(sel){var s=sel.value; var g=panGetUrl()["d"]; var h=window.location.href; h=h.replace(g,s); window.location.replace(h);} function panSortByUser(sel){var s=sel.value; var g=panGetUrl()["u"]; var h=window.location.href; h=h.replace(g,s); window.location.replace(h);}';

$pan_js_mark_sticky = 'function panPmMarkSticky(id){var csrf_token="'.generate_form_token(forum_link($forum_url["pan_pm_sticky"])).'"; jQuery.ajax({url:PUNBB.env.base_url+"misc.php?action=pan_pm&section=sticky", type:"POST", dataType:"json", cache:false, data:({csrf_token:csrf_token,id:id}), success: function(res){document.getElementById("sticky"+id).innerHTML=res.content;}, error: function(res){}});}';

// Setup navigation menu
$forum_page['main_menu'] = array(
	'inbox' => '<li class="first-item'.(($section == 'inbox') ? ' active' : '').'"><a href="'.forum_link($forum_url['pan_pm_inbox']).'"><span>'.$lang_pan_pm['item_inbox'].'</span></a></li>',
	'outbox' => '<li'.(($section == 'outbox') ? ' class="active"' : '').'><a href="'.forum_link($forum_url['pan_pm_outbox']).'"><span>'.$lang_pan_pm['item_outbox'].'</span></a></li>',
	'new' => '<li'.(($section == 'new' || $section == 'read' ||  $section == 'edit') ? ' class="active"' : '').'><a href="'.forum_link($forum_url['pan_pm_new']).'"><span>'.$lang_pan_pm['item_new'].'</span></a></li>',
);


if ($section == 'sticky' && isset($_POST['id']))
{
	$id = intval($_POST['id']);
	if ($id > 0)
	{
		$query = array(
			'SELECT'	=> 'sender_id, sticky_for_sender, sticky_for_receiver',
			'FROM'		=> 'pan_pm',
			'WHERE'		=> 'id='.$id
		);
		($hook = get_hook('pan_pm_mi_new_action_select_sticky')) ? eval($hook) : null;
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$pm_info = $forum_db->fetch_assoc($result);
		
		$cur_sticky = ($pm_info['sender_id'] == $forum_user['id']) ? $pm_info['sticky_for_sender'] : $pm_info['sticky_for_receiver'];
		$sticky = ($cur_sticky == 0) ? 1 : 0;
		
		$query = array(
			'UPDATE'	=> 'pan_pm',
			'WHERE'		=> 'id='.$id
		);
		
		if ($pm_info['sender_id'] == $forum_user['id']) {
			$query['SET'] = 'sticky_for_sender='.$sticky;
		} else {
			$query['SET'] = 'sticky_for_receiver='.$sticky;
		}
		
		($hook = get_hook('pan_pm_mi_new_action_update_sticky')) ? eval($hook) : null;
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		
		$content = ($sticky == 1) ? '<img src="'.$base_url.'/extensions/pan_pm/img/star-1.png" onclick="panPmMarkSticky('.$id.')">' : '<img src="'.$base_url.'/extensions/pan_pm/img/star-0.png" onclick="panPmMarkSticky('.$id.')">';
		
		echo json_encode(array(
			'answer' => 'success',
			'sticky' => $sticky,
			'content'=> $content
		));
	}
	$forum_db->end_transaction();
	$forum_db->close();
	exit();
}
else if ($section == 'inbox')
{
	
	$forum_loader->add_js($pan_sorting_js, array('type' => 'inline', 'weight' => 100, 'group' => FORUM_JS_GROUP_SYSTEM));
	$forum_loader->add_js($pan_check_all_js, array('type' => 'inline', 'weight' => 100, 'group' => FORUM_JS_GROUP_SYSTEM));
		$forum_loader->add_js($pan_js_mark_sticky, array('type' => 'inline', 'weight' => 100, 'group' => FORUM_JS_GROUP_SYSTEM));

	//Get Count Inbox
	$pms_inbox = array();
	$query = array(
		'SELECT'	=> 'id',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'receiver_id='.$forum_user['id'].' AND deleted_by_receiver=0'
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	while ($rows = $forum_db->fetch_assoc($result))
		$pms_inbox[] = $rows['id'];
	
	$num_inbox = count($pms_inbox);


	$search_user = (isset($_GET['u']) && $_GET['u'] != '*') ? $_GET['u'] : '';
	
	$pm = array();
	$query = array(
		'SELECT'	=> 'id',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'sender '.($db_type == 'pgsql' ? 'ILIKE' : 'LIKE').' \''.$forum_db->escape('%'.$search_user.'%').'\' AND receiver_id='.$forum_user['id'].' AND deleted_by_receiver=0'
	);
	($hook = get_hook('pan_pm_mi_new_action_get_inbox_ids')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	while ($rows = $forum_db->fetch_assoc($result))
		$pm[] = $rows['id'];

	$num_pm = count($pm);

	//Page Navigation
	$forum_page['num_pages'] = ceil($num_pm / $forum_user['disp_topics']);
	$forum_page['page'] = (!isset($_GET['p']) || !is_numeric($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $forum_page['num_pages']) ? 1 : $_GET['p'];
	$forum_page['start_from'] = $forum_user['disp_topics'] * ($forum_page['page'] - 1);
	$forum_page['finish_at'] = min(($forum_page['start_from'] + $forum_user['disp_topics']), ($num_pm));
	$forum_page['items_info'] = generate_items_info($lang_common['Pages'], ($forum_page['start_from'] + 1), $num_pm);

	// Generate paging/posting links
	if ($forum_page['num_pages'] > 1)
		$forum_page['paging'] = '<p class="paging"><span class="pages">'.$lang_common['Pages'].'</span> '.paginate($forum_page['num_pages'], $forum_page['page'], $forum_url['pan_pm_inbox'], $lang_common['Paging separator'], '', 1).'</p>';
	
	// Setup breadcrumbs
	$forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		array($lang_pan_pm['private_message'], forum_link($forum_url['pan_pm_inbox'])),
		$lang_pan_pm['item_inbox']
	);
	
	define('FORUM_PAGE', 'pan-pm-inbox');
	require FORUM_ROOT.'header.php';
	
	// START SUBST - <!-- forum_main -->
	ob_start();

?>

	<div id="page-pm-inbox" class="main-content main-frm mail-list">

		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['pan_pm_inbox']) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['pan_pm_inbox'])) ?>" />
			</div>

			<table>
				<thead>
					<tr>
						<th class="tc0"><input type="checkbox" name="all" value="all" onclick="checkAll(this)" title="<?php echo $lang_pan_pm['mark_for_delete_all'] ?>" /></th>
						<th class="tc1"><img src="<?php echo $ext_info['url'].'/img/star-1.png' ?>" title="<?php echo $lang_pan_pm['msg_sticky_i'] ?>"></th>
						<th class="tc2"><img src="<?php echo $ext_info['url'].'/style/icons/sent.png' ?>" title="<?php echo $lang_pan_pm['msg_status'] ?>"></th>
						<th class="tc3" style="width:20%;"><?php echo $lang_pan_pm['sender'] ?></th>
						<th class="tc4" style="width:50%;">
							<?php echo $lang_pan_pm['list_topics'] ?>
							<?php echo ($num_inbox > $forum_config['o_pan_pm_max_inbox']) ? '<span class="msg-full">('.$num_inbox.')</span>' : '<span>('.$num_inbox.')</span>' ?>
						</th>
						<th class="tc5" style="width:20%;"><?php echo $lang_pan_pm['posted'] ?></th>
					</tr>
				</thead>
				<tbody>
<?php
	if ($num_pm > 0)
	{
		$sort_by_date = ($date == 'DESC') ? 'posted DESC' : '';
		$pm_info = array();
		
		$query = array(
			'SELECT'	=> 'id, topic_id, sender, sender_id, receiver, receiver_id, subject, message, posted, status, sticky_for_sender, sticky_for_receiver',
			'FROM'		=> 'pan_pm',
			'WHERE'		=> 'id IN ('.implode(',', $pm).')',
			'ORDER BY'	=> ''.$sort_by_date,
			'LIMIT'		=> $forum_page['start_from'].', '.$forum_user['disp_topics']
		);
		($hook = get_hook('pan_pm_mi_new_action_get_inbox_info')) ? eval($hook) : null;
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		
		while ($rows = $forum_db->fetch_assoc($result))
			$pm_info[] = $rows;
		
		$i =2;
		foreach ($pm_info as $key => $pminfo)
		{
			if ($pminfo['status'] == 'sent')
			{
				$link_on_msg = '<strong>'.forum_htmlencode($pminfo['subject']).'</strong>';
				$link_on_sender = '<strong>'.forum_htmlencode($pminfo['sender']).'</strong>';
			} else {
				$link_on_msg = forum_htmlencode($pminfo['subject']);
				$link_on_sender = forum_htmlencode($pminfo['sender']);
			}
			
			$sticky = ($pminfo['sender_id'] == $forum_user['id']) ? $pminfo['sticky_for_sender'] : $pminfo['sticky_for_receiver'];
			
			if ($sticky == 1)
			{
				$sticky_img = '<img src="'.$ext_info['url'].'/img/star-1.png" onclick="panPmMarkSticky('.$pminfo['id'].')" title="'.$lang_pan_pm['msg_mark_unsticky'].'">';
			} else {
				$sticky_img = '<img src="'.$ext_info['url'].'/img/star-0.png" onclick="panPmMarkSticky('.$pminfo['id'].')" title="'.$lang_pan_pm['msg_mark_sticky'].'">';
			}
			
			if ($pminfo['status'] == 'read')
			{
				$status_img = '<img src="'.$ext_info['url'].'/style/icons/read.png" title="'.$lang_pan_pm['msg_read'].'">';
			} else {
				$status_img = '<img src="'.$ext_info['url'].'/style/icons/sent.png" title="'.$lang_pan_pm['msg_unred'].'">';
			}
			
			if ($num_inbox > $forum_config['o_pan_pm_max_inbox'] && ($pminfo['status'] == 'sent'))
			{
				$inbox_subject = '<span stile="inbox-full" title="'.$lang_pan_pm['inbox_full_title'].'">'.$link_on_msg.'</span>';
				$tr_class = ' inbox-full';
			} else {
				$inbox_subject = '<a href="'.forum_link($forum_url['pan_pm_read'], $pminfo['id']).'#msg'.$pminfo['id'].'" title="'.$lang_pan_pm['go_to_read'].'">'.$link_on_msg.'</a>';
				$tr_class = '';
			}
?>
					<tr id="pms<?php echo $i ?>" class="<?php echo $tr_class ?>">
						<td class="tc0"><input type="checkbox" name="pms[<?php echo $pminfo['id'] ?>]" value="<?php echo $pminfo['id'] ?>" onclick="checkOne(this)" title="<?php echo $lang_pan_pm['mark_for_delete_one'] ?>"></td>
						<td id="sticky<?php echo $pminfo['id'] ?>" class="tc1"><?php echo $sticky_img ?></td>
						<td class="tc2 status"><?php echo $status_img ?></td>
						<td class="tc3"><a href="<?php echo forum_link($forum_url['user'], $pminfo['sender_id']) ?>" target="_blank"><?php echo $link_on_sender ?></a></td>
						<td class="tc4"><?php echo $inbox_subject ?></td>
						<td class="tc5"><?php echo format_time($pminfo['posted'], 0) ?></td>
					</tr>
<?php
			$i++;
		}
	}
?>
				</tbody>
			</table>
<?php
	if ($num_pm > 0)
	{
?>
			<div class="frm-buttons">
				<span class="submit primary"><input type="submit" name="delete" value="<?php echo $lang_pan_pm['delete'] ?>" /></span>
			</div>
<?php
	}
?>
		</form>
<?php
	if ($num_pm > 0)
	{
?>
		<div id="brd-pagepost-top" class="main-pagepost gen-content">
			<?php if (isset($forum_page['paging'])) echo $forum_page['paging'] ?>
		</div>
<?php
	} else {
?>
		<div class="ct-box info-box">
			<p><?php echo $lang_pan_pm['no_msg_inbox'] ?></p>
		</div>
<?php
	}
?>
	</div>
<?php
	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->
	
	require FORUM_ROOT.'footer.php';
}
else if ($section == 'outbox')
{
	$forum_loader->add_js($pan_sorting_js, array('type' => 'inline', 'weight' => 100, 'group' => FORUM_JS_GROUP_SYSTEM));
	$forum_loader->add_js($pan_check_all_js, array('type' => 'inline', 'weight' => 100, 'group' => FORUM_JS_GROUP_SYSTEM));
	$forum_loader->add_js($pan_js_mark_sticky, array('type' => 'inline', 'weight' => 100, 'group' => FORUM_JS_GROUP_SYSTEM));
	
	//Get Count Outbox
	$pms_outbox = array();
	$query = array(
		'SELECT'	=> 'id',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'sender_id='.$forum_user['id'].' AND deleted_by_sender=0'
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	while ($rows = $forum_db->fetch_assoc($result))
		$pms_outbox[] = $rows['id'];
	
	$num_outbox = count($pms_outbox);
	
	$search_user = (isset($_GET['u']) && $_GET['u'] != '*') ? $_GET['u'] : '';
	
	$pm = array();
	$query = array(
		'SELECT'	=> 'id',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'receiver '.($db_type == 'pgsql' ? 'ILIKE' : 'LIKE').' \''.$forum_db->escape('%'.$search_user.'%').'\' AND sender_id='.$forum_user['id'].' AND deleted_by_sender=0'
	);
	($hook = get_hook('pan_pm_mi_new_action_get_outbox_ids')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	while ($rows = $forum_db->fetch_assoc($result))
		$pm[] = $rows['id'];

	$num_pm = count($pm);

	//Page Navigation
	$forum_page['num_pages'] = ceil($num_pm / $forum_user['disp_topics']);
	$forum_page['page'] = (!isset($_GET['p']) || !is_numeric($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $forum_page['num_pages']) ? 1 : $_GET['p'];
	$forum_page['start_from'] = $forum_user['disp_topics'] * ($forum_page['page'] - 1);
	$forum_page['finish_at'] = min(($forum_page['start_from'] + $forum_user['disp_topics']), ($num_pm));
	$forum_page['items_info'] = generate_items_info($lang_common['Pages'], ($forum_page['start_from'] + 1), $num_pm);

	// Generate paging/posting links
	if ($forum_page['num_pages'] > 1)
		$forum_page['paging'] = '<p class="paging"><span class="pages">'.$lang_common['Pages'].'</span> '.paginate($forum_page['num_pages'], $forum_page['page'], $forum_url['pan_pm_outbox'], $lang_common['Paging separator'], '', 1).'</p>';
	
	// Setup breadcrumbs
	$forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		array($lang_pan_pm['private_message'], forum_link($forum_url['pan_pm_inbox'])),
		$lang_pan_pm['item_outbox']
	);
	
	define('FORUM_PAGE', 'pan-pm-outbox');
	require FORUM_ROOT.'header.php';
	
	// START SUBST - <!-- forum_main -->
	ob_start();
?>
	
	<div id="page-pm-outbox" class="main-content main-frm mail-list">

		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['pan_pm_outbox']) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['pan_pm_outbox'])) ?>" />
			</div>

			<table>
				<thead>
					<tr>
						<th class="tc0"><input type="checkbox" name="all" value="all" onclick="checkAll(this)" title="<?php echo $lang_pan_pm['mark_for_delete_all'] ?>" /></th>
						<th class="tc1"><img src="<?php echo $ext_info['url'].'/img/star-1.png' ?>" title="<?php echo $lang_pan_pm['msg_sticky_i'] ?>"></th>
						<th class="tc2"><img src="<?php echo $ext_info['url'].'/style/icons/sent.png' ?>" title="<?php echo $lang_pan_pm['msg_status'] ?>"></th>
						<th class="tc3" style="width:20%;"><?php echo $lang_pan_pm['reciver'] ?></th>
						<th class="tc4" style="width:50%;">
							<?php echo $lang_pan_pm['list_topics'] ?>
							<?php echo ($num_outbox > $forum_config['o_pan_pm_max_outbox']) ? '<span class="msg-full">('.$num_outbox.')</span>' : '<span>('.$num_outbox.')</span>' ?>
						</th>
						<th class="tc5" style="width:20%;"><?php echo $lang_pan_pm['posted'] ?></th>
					</tr>
				</thead>
				
				<tbody>
<?php
	if ($num_pm > 0)
	{
		$sort_by_date = ($date == 'DESC') ? 'posted DESC' : '';
		$pm_info = array();
		
		$query = array(
			'SELECT'	=> 'id, topic_id, sender, sender_id, receiver, receiver_id, subject, message, posted, status, sticky_for_sender, sticky_for_receiver',
			'FROM'		=> 'pan_pm',
			'WHERE'		=> 'id IN ('.implode(',', $pm).')',
			'ORDER BY'	=> ''.$sort_by_date,
			'LIMIT'		=> $forum_page['start_from'].', '.$forum_user['disp_topics']
		);
		($hook = get_hook('pan_pm_mi_new_action_get_outbox_info')) ? eval($hook) : null;
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		while ($rows = $forum_db->fetch_assoc($result))
			$pm_info[] = $rows;
		
		$i = 2;
		foreach ($pm_info as $key => $pminfo)
		{
			$sticky = ($pminfo['sender_id'] == $forum_user['id']) ? $pminfo['sticky_for_sender'] : $pminfo['sticky_for_receiver'];
			
			if ($sticky == 1)
			{
				$sticky_img = '<img src="'.$ext_info['url'].'/img/star-1.png" onclick="panPmMarkSticky('.$pminfo['id'].')" title="'.$lang_pan_pm['msg_mark_unsticky'].'">';
			} else {
				$sticky_img = '<img src="'.$ext_info['url'].'/img/star-0.png" onclick="panPmMarkSticky('.$pminfo['id'].')" title="'.$lang_pan_pm['msg_mark_sticky'].'">';
			}
			
			if ($pminfo['status'] == 'read')
			{
				$status_img = '<img src="'.$ext_info['url'].'/style/icons/read.png" title="'.$lang_pan_pm['msg_read'].'">';
			} else {
				$status_img = '<img src="'.$ext_info['url'].'/style/icons/sent.png" title="'.$lang_pan_pm['msg_unred'].'">';
			}
			
?>

					<tr id="pms<?php echo $i ?>">
						<td class="tc0"><input type="checkbox" name="pms[<?php echo $pminfo['id'] ?>]" value="<?php echo $pminfo['id'] ?>" title="<?php echo $lang_pan_pm['mark_for_delete_one'] ?>" onclick="checkOne(this);"></td>
						<td id="sticky<?php echo $pminfo['id'] ?>" class="tc1"><?php echo $sticky_img ?></td>
						<td class="tc2 status"><?php echo $status_img ?></td>
						<td class="tc3"><a href="<?php echo forum_link($forum_url['user'], $pminfo['receiver_id']) ?>" target="_blank"><?php echo $pminfo['receiver'] ?></a></td>
						<td class="tc4"><a href="<?php echo ($pminfo['status'] == 'read') ? (forum_link($forum_url['pan_pm_read'], $pminfo['id']).'#msg'.$pminfo['id']) : (forum_link($forum_url['pan_pm_edit'], $pminfo['id']).'#msg'.$pminfo['id']) ?>" title="<?php echo ($pminfo['status'] == 'read') ? $lang_pan_pm['go_to_read'] : $lang_pan_pm['go_to_edit'] ?>"><?php echo forum_htmlencode($pminfo['subject']) ?></a></td>
						<td class="tc5"><?php echo format_time($pminfo['posted'], 0) ?></td>
					</tr>
	
<?php
			$i++;
		}
	}
?>
				</tbody>
			</table>
<?php
	if ($num_pm > 0)
	{
?>
			<div class="frm-buttons">
				<span class="submit primary"><input type="submit" name="delete" value="<?php echo $lang_pan_pm['delete'] ?>" /></span>
			</div>
<?php
	}
?>
		</form>
<?php
	if ($num_pm > 0)
	{
?>
		<div id="brd-pagepost-top" class="main-pagepost gen-content">
			<?php if (isset($forum_page['paging'])) echo $forum_page['paging'] ?>
		</div>
<?php
	} else {
?>
		<div class="ct-box info-box">
			<p><?php echo $lang_pan_pm['no_msg_outbox'] ?></p>
		</div>
<?php
	}
?>
	</div>

<?php
	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->
	
	require FORUM_ROOT.'footer.php';
}
else if ($section == 'read' && $pid > 0)
{
	$pms_outbox = array();
	$query = array(
		'SELECT'	=> 'id',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'sender_id='.$forum_user['id'].' AND deleted_by_sender=0'
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	while ($rows = $forum_db->fetch_assoc($result))
		$pms_outbox[] = $rows['id'];
	
	$num_outbox = count($pms_outbox);
	
	if ($num_outbox > $forum_config['o_pan_pm_max_outbox'])
		$errors[] = $lang_pan_pm['outbox_full'];
	
	if (!defined('FORUM_PARSER_LOADED'))
		require FORUM_ROOT.'include/parser.php';
	
	$query = array(
		'SELECT'	=> 'id, topic_id, sender, sender_id, receiver, receiver_id, subject, message, posted, status',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'id='.$pid,
	);
	($hook = get_hook('pan_pm_mi_new_action_get_read_one')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$msg_info = $forum_db->fetch_assoc($result);
	
	$tid = $msg_info['topic_id'];
	
	$interlocutor = ($msg_info['receiver'] != $forum_user['username']) ? $msg_info['receiver'] : $msg_info['sender'];
	$interlocutor_id = ($msg_info['receiver_id'] != $forum_user['id']) ? $msg_info['receiver_id'] : $msg_info['sender_id'];
/*
	$arr_msgs = array();
	foreach ($msg_info as $msgs)
		$arr_msgs[] = $msgs;
	
	$num_msgs = count($msg_info);
	if($forum_user['g_id'] == FORUM_ADMIN)
		print_r($num_msgs);
		
	//Page Navigation
	$forum_page['num_pages'] = ceil($num_msgs / $forum_user['disp_topics']);
	$forum_page['page'] = (!isset($_GET['p']) || !is_numeric($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $forum_page['num_pages']) ? 1 : $_GET['p'];
	$forum_page['start_from'] = $forum_user['disp_topics'] * ($forum_page['page'] - 1);
	$forum_page['finish_at'] = min(($forum_page['start_from'] + $forum_user['disp_topics']), ($num_msgs));
	$forum_page['items_info'] = generate_items_info($lang_common['Pages'], ($forum_page['start_from'] + 1), $num_msgs);

	// Generate paging/posting links
	if ($forum_page['num_pages'] > 1)
		$forum_page['paging'] = '<p class="paging"><span class="pages">'.$lang_common['Pages'].'</span> '.paginate($forum_page['num_pages'], $forum_page['page'], $forum_url['pan_pm_read'], $lang_common['Paging separator'], $pid, 1).'</p>';
*/
	// Setup breadcrumbs
	$forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		array($lang_pan_pm['private_message'], forum_link($forum_url['pan_pm_inbox'])),
		sprintf($lang_pan_pm['crumb_read'], $interlocutor)
	);
	
	$query = array(
		'UPDATE'	=> 'pan_pm',
		'SET'		=> 'status=\'read\'',
		'WHERE'		=> 'topic_id='.$tid.' AND receiver_id='.$forum_user['id']
	);
	($hook = get_hook('pan_pm_mi_new_action_update_read')) ? eval($hook) : null;
	$forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	//
	$query = array(
		'SELECT'	=> 'id, topic_id, sender, sender_id, receiver, receiver_id, subject, message, posted, status',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'id='.$pid,
		'ORDER BY'	=> 'posted',
	);
	($hook = get_hook('pan_pm_mi_new_action_get_read_info')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	$pm_info = array();
	$forum_page['msg_count'] = 0;
	
	while ($pminfo = $forum_db->fetch_assoc($result)) {
		$pm_info[] = $pminfo;
		$forum_page['msg_count']++;
	}
	
//	define('FORUM_PAGE', 'pan-pm-read');
	define('FORUM_PAGE', 'post');
	require FORUM_ROOT.'header.php';
	
	// START SUBST - <!-- forum_main -->
	ob_start();
?>
	
	<div class="main-subhead">
		<h2 class="hn"><?php echo $lang_pan_pm['view_msg'] ?></h2>
	</div>

<style>
/*.main-topic {margin-top: 1em;}*/
</style>

	<div class="main-content main-topic">

<?php

	foreach ($pm_info as $key => $pminfo)
	{
		++$forum_page['item_count'];
		
		$recipient = ($pminfo['sender'] == $forum_user['username']) ? $pminfo['sender'] : $pminfo['receiver'];
		$recipient_id = ($pminfo['sender_id'] == $forum_user['id']) ? $pminfo['sender_id'] : $pminfo['receiver_id'];
		
		$forum_page['post_ident']['num'] = '<span id="msg'.$pminfo['id'].'" class="post-num"><a href="'.forum_link($forum_url['pan_pm_read'], $pminfo['id']).'#msg'.$pminfo['id'].'">#'.forum_number_format($pminfo['id']).'</a></span>';
		$forum_page['post_ident']['link'] = '<strong>'.forum_htmlencode($pminfo['sender']).'</strong>: <span class="post-link">'.format_time($pminfo['posted']).'</span>';
		
		if ($recipient_id == $forum_user['id'])
		{
			$forum_page['post_ident']['subject'] = '(<span class="post-edit">'.sprintf($lang_pan_pm['msg_subj'], forum_htmlencode($pminfo['subject'])).'</span>)';
			$forum_page['item_status']['topicpost'] = 'topicpost';
		}
		else
		{
			$forum_page['post_ident']['reply'] = '(<span class="post-edit">'.sprintf($lang_pan_pm['msg_reply'], forum_htmlencode($pminfo['subject'])).'</span>)';
			$forum_page['item_status']['replypost'] = 'replypost';
		}
		
		$forum_page['subject'] = ($forum_page['item_count'] == 1) ? '  (<strong>'.forum_htmlencode($pminfo['subject']).'</strong>)' : '';
		
		$forum_page['message'] = parse_message($pminfo['message'], 0);
?>
		<div class="<?php echo implode(' ', $forum_page['item_status']) ?>">
			
			<div class="posthead" style="margin-left:0;">
				<h3 class="hn post-ident"><?php echo implode(' ', $forum_page['post_ident']) ?></h3>
			</div>
			
			<div class="postbody" style="margin-left:0;">

				<div class="post-entry">
					<div class="entry-content">
						<?php echo $forum_page['message'] ?>
					</div>
				</div>
			</div>

		</div>
<?php
	}
	
?>
	</div>
	
	
	<div class="main-subhead">
		<h2 class="hn"><span><?php echo $lang_pan_pm['form_answer'] ?></span></h2>
	</div>
	
	<div class="main-content main-frm">
<?php
	// If there were any errors, show them
	if (!empty($errors))
	{
		$forum_page['errors'] = array();
		foreach ($errors as $cur_error)
			$forum_page['errors'][] = '<li class="warn"><span>'.$cur_error.'</span></li>';

//		($hook = get_hook('po_pre_post_errors')) ? eval($hook) : null;
?>
		<div class="ct-box error-box">
			<h2 class="warn hn"><?php echo $lang_post['Post errors'] ?></h2>
			<ul class="error-list">
				<?php echo implode("\n\t\t\t\t", $forum_page['errors'])."\n" ?>
			</ul>
		</div>
<?php
	}
?>
		<form id="afocus" class="frm-form frm-ctrl-submit" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['pan_pm_read'], $pid) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['pan_pm_read'], $pid)) ?>" />
				<input type="hidden" name="req_tid" value="<?php echo $tid ?>" />
				<input type="hidden" name="req_uid" value="<?php echo $interlocutor_id ?>" />
				<input type="hidden" name="req_subject" value="<?php echo isset($_POST['req_subject']) ? forum_htmlencode($_POST['req_subject']) : forum_htmlencode($msg_info['subject']) ?>" />
			</div>

			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">

<?php ($hook = get_hook('po_pre_post_contents')) ? eval($hook) : null; ?>

				<div class="txt-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="txt-box textarea required">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_pan_pm['message'] ?></span></label>
						<div class="txt-input"><span class="fld-input"><textarea id="fld<?php echo $forum_page['fld_count'] ?>" name="req_message" rows="15" cols="95" required spellcheck="true"><?php echo (isset($_POST['req_message']) ? forum_htmlencode($_POST['req_message']) : '') ?></textarea></span></div>
					</div>
				</div>
				
			

<?php

	$forum_page['checkboxes']['sticky'] = '<div class="mf-item"><span class="fld-input">
		<input type="hidden" name="req_sticky" value="0" />
		<input type="checkbox" id="fld'.(++$forum_page['fld_count']).'" name="req_sticky" value="1"'.((isset($_POST['req_sticky']) && $_POST['req_sticky'] == 1) ? ' checked="checked"' : '').' /></span> 
		<label for="fld'.$forum_page['fld_count'].'"><span>'.$lang_pan_pm['new_msg_sticky_i'].'</label></div>';

	//($hook = get_hook('po_pre_optional_fieldset')) ? eval($hook) : null;

	if (!empty($forum_page['checkboxes']))
	{
?>
				<fieldset class="mf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="mf-box checkbox">
						<?php echo implode("\n\t\t\t\t\t", $forum_page['checkboxes'])."\n" ?>
					</div>
				</fieldset>
<?php
	}
?>
			</fieldset>
			
			<div class="frm-buttons">
				<span class="submit primary"><input type="submit" name="pan_pm_sent" value="<?php echo $lang_pan_pm['send_msg'] ?>" <?php echo ($num_outbox > $forum_config['o_pan_pm_max_outbox']) ? 'disabled' : '' ?> /></span>
			</div>
		</form>

	</div>
	
<?php

	// Get the amount of posts in the topic
	$query = array(
		'SELECT'	=> 'count(p.id)',
		'FROM'		=> 'pan_pm AS p',
		'WHERE'		=> 'topic_id='.$tid
	);

//	($hook = get_hook('po_topic_review_qr_get_post_count')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_page['total_post_count'] = $forum_db->result($result, 0);

	// Get posts to display in topic review
	$query = array(
		'SELECT'	=> 'id, topic_id, sender, sender_id, receiver, receiver_id, subject, message, posted, status',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'topic_id='.$tid, 	//.' AND id!='.$pid,
		'ORDER BY'	=> 'id DESC',
//		'LIMIT'		=> $forum_page['start_from'].', '.$forum_user['disp_topics']
	);

//	($hook = get_hook('po_topic_review_qr_get_topic_review_posts')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	$posts = array();
	while ($cur_post = $forum_db->fetch_assoc($result))
	{
		$posts[] = $cur_post;
	}

?>
	<div class="main-subhead">
		<h2 class="hn"><span><?php echo $lang_post['Topic review'] ?></span></h2>
	</div>
	
	<div class="main-content main-topic">
<?php

	$forum_page['item_count'] = 0;
	$forum_page['item_total'] = count($posts);

	foreach ($posts as $cur_post)
	{
		++$forum_page['item_count'];

		$forum_page['message'] = parse_message($cur_post['message'], 0);

		// Generate the post heading
		$forum_page['post_ident'] = array();
		$forum_page['post_ident']['num'] = '<span class="post-num">'.forum_number_format($forum_page['total_post_count'] - $forum_page['item_count'] + 1).'</span>';
		$forum_page['post_ident']['byline'] = '<span class="post-byline">'.sprintf($lang_post['Post byline'], '<strong>'.forum_htmlencode($cur_post['sender']).'</strong>').'</span>';
		$forum_page['post_ident']['link'] = '<span class="post-link"><a class="permalink" rel="bookmark" title="'.$lang_post['Permalink post'].'" href="'.forum_link($forum_url['pan_pm_read'], $cur_post['id']).'#msg'.$cur_post['id'].'">'.format_time($cur_post['posted']).'</a></span>';

		($hook = get_hook('po_topic_review_row_pre_display')) ? eval($hook) : null;

?>
		<div class="post<?php if ($forum_page['item_count'] == 1) echo ' firstpost'; ?><?php if ($forum_page['item_total'] == $forum_page['item_count']) echo ' lastpost'; ?>">
			<div class="posthead">
				<h3 class="hn post-ident"><?php echo implode(' ', $forum_page['post_ident']) ?></h3>
<?php ($hook = get_hook('po_topic_review_new_post_head_option')) ? eval($hook) : null; ?>
			</div>
			<div class="postbody">
				<div class="post-entry">
					<div class="entry-content">
						<?php echo $forum_page['message']."\n" ?>
<?php ($hook = get_hook('po_topic_review_new_post_entry_data')) ? eval($hook) : null; ?>
					</div>
				</div>
			</div>
		</div>
<?php

	}

?>
	</div>
	
		<div id="brd-pagepost-top" class="main-pagepost gen-content">
			<?php if (isset($forum_page['paging'])) echo $forum_page['paging'] ?>
		</div>
	
<?php

	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->
	
	require FORUM_ROOT.'footer.php';
}
else if ($section == 'edit' && $pid > 0)
{
	if (!defined('FORUM_PARSER_LOADED'))
		require FORUM_ROOT.'include/parser.php';
	
	$query = array(
		'SELECT'	=> 'id, topic_id, sender, sender_id, receiver, receiver_id, subject, message, posted, status, sticky_for_sender',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'id='.$pid,
	);
	($hook = get_hook('pan_pm_mi_new_action_get_edit_one')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$msg_info = $forum_db->fetch_assoc($result);
	
	$tid = $msg_info['topic_id'];
	
	$interlocutor = ($msg_info['receiver'] != $forum_user['username']) ? $msg_info['receiver'] : $msg_info['sender'];
	$interlocutor_id = ($msg_info['receiver_id'] != $forum_user['id']) ? $msg_info['receiver_id'] : $msg_info['sender_id'];
	
	// Setup breadcrumbs
	$forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		array($lang_pan_pm['private_message'], forum_link($forum_url['pan_pm_inbox'])),
		$lang_pan_pm['edit_msg']
	);
	
	$query = array(
		'SELECT'	=> 'id, topic_id, sender, sender_id, receiver, receiver_id, subject, message, posted, status',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'id='.$pid,
		'ORDER BY'	=> 'posted',
	);
	($hook = get_hook('pan_pm_mi_new_action_get_edit_info')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	
	$pm_info = array();
	$forum_page['msg_count'] = 0;
	
	while ($pminfo = $forum_db->fetch_assoc($result)) {
		$pm_info[] = $pminfo;
		$forum_page['msg_count']++;
	}
	
//	define('FORUM_PAGE', 'pan-pm-read');
	define('FORUM_PAGE', 'post');
	require FORUM_ROOT.'header.php';
	
	// START SUBST - <!-- forum_main -->
	ob_start();
?>
	
	<div class="main-subhead">
		<h2 class="hn"><?php echo $lang_pan_pm['edit_msg'] ?></h2>
	</div>

<style>
/*.main-topic {margin-top: 1em;}*/
</style>

	<div class="main-content main-topic">

<?php

	foreach ($pm_info as $key => $pminfo)
	{
		++$forum_page['item_count'];
		
		$recipient = ($pminfo['sender'] == $forum_user['username']) ? $pminfo['sender'] : $pminfo['receiver'];
		$recipient_id = ($pminfo['sender_id'] == $forum_user['id']) ? $pminfo['sender_id'] : $pminfo['receiver_id'];
		
		$forum_page['post_ident']['num'] = '<span id="msg'.$pminfo['id'].'" class="post-num"><a href="'.forum_link($forum_url['pan_pm_read'], array($forum_user['id'], $tid)).'#msg'.$pminfo['id'].'">#'.forum_number_format($forum_page['item_count']).'</a></span>';
		$forum_page['post_ident']['link'] = '<strong>'.forum_htmlencode($pminfo['sender']).'</strong>: <span class="post-link">'.format_time($pminfo['posted']).'</span>';
		
		if ($recipient_id == $forum_user['id'])
		{
			$forum_page['post_ident']['subject'] = '(<span class="post-edit">'.sprintf($lang_pan_pm['msg_subj'], forum_htmlencode($pminfo['subject'])).'</span>)';
			$forum_page['item_status']['topicpost'] = 'topicpost';
		}
		else
		{
			$forum_page['post_ident']['reply'] = '(<span class="post-edit">'.sprintf($lang_pan_pm['msg_reply'], forum_htmlencode($pminfo['subject'])).'</span>)';
			$forum_page['item_status']['replypost'] = 'replypost';
		}
		
		$forum_page['subject'] = ($forum_page['item_count'] == 1) ? '  (<strong>'.forum_htmlencode($pminfo['subject']).'</strong>)' : '';
		
		$forum_page['message'] = parse_message($pminfo['message'], 0);
?>
		<div class="<?php echo implode(' ', $forum_page['item_status']) ?>">
			
			<div class="posthead" style="margin-left:0;">
				<h3 class="hn post-ident"><?php echo implode(' ', $forum_page['post_ident']) ?></h3>
			</div>
			
			<div class="postbody" style="margin-left:0;">

				<div class="post-entry">
					<div class="entry-content">
						<?php echo $forum_page['message'] ?>
					</div>
				</div>
			</div>

		</div>
<?php
	}
	
?>
	</div>
	
	<div class="main-content main-frm">
<?php
	// If there were any errors, show them
	if (!empty($errors))
	{
		$forum_page['errors'] = array();
		foreach ($errors as $cur_error)
			$forum_page['errors'][] = '<li class="warn"><span>'.$cur_error.'</span></li>';

//		($hook = get_hook('po_pre_post_errors')) ? eval($hook) : null;
?>
		<div class="ct-box error-box">
			<h2 class="warn hn"><?php echo $lang_post['Post errors'] ?></h2>
			<ul class="error-list">
				<?php echo implode("\n\t\t\t\t", $forum_page['errors'])."\n" ?>
			</ul>
		</div>
<?php
	}
?>
		<form id="afocus" class="frm-form frm-ctrl-submit" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['pan_pm_edit'], $pid) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['pan_pm_edit'], $pid)) ?>" />
				<input type="hidden" name="req_pid" value="<?php echo $pid ?>" />
			</div>


			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">

<?php ($hook = get_hook('po_pre_post_contents')) ? eval($hook) : null; ?>

				<div class="txt-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="txt-box textarea required">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_pan_pm['message'] ?></span></label>
						<div class="txt-input"><span class="fld-input"><textarea id="fld<?php echo $forum_page['fld_count'] ?>" name="req_message" rows="15" cols="95" required spellcheck="true"><?php echo (isset($_POST['req_message']) ? forum_htmlencode($_POST['req_message']) : $msg_info['message']) ?></textarea></span></div>
					</div>
				</div>
				
			

<?php

	$forum_page['checkboxes']['sticky'] = '<div class="mf-item"><span class="fld-input"><input type="hidden" name="req_sticky" value="0" /><input type="checkbox" id="fld'.(++$forum_page['fld_count']).'" name="req_sticky" value="1"'.((isset($_POST['req_sticky']) && $_POST['req_sticky'] == 1) ? ' checked="checked"' : ($msg_info['sticky_for_sender'] == 1 ? ' checked="checked"' : '')).' /></span> <label for="fld'.$forum_page['fld_count'].'"><span>'.$lang_pan_pm['new_msg_sticky_i'].'</label></div>';

	//($hook = get_hook('po_pre_optional_fieldset')) ? eval($hook) : null;

	if (!empty($forum_page['checkboxes']))
	{
?>
				<fieldset class="mf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="mf-box checkbox">
						<?php echo implode("\n\t\t\t\t\t", $forum_page['checkboxes'])."\n" ?>
					</div>
				</fieldset>
<?php
	}
?>
			</fieldset>
			
			<div class="frm-buttons">
				<span class="submit primary"><input type="submit" name="save_edited" value="<?php echo $lang_pan_pm['send_msg'] ?>" /></span>
			</div>
		</form>

	</div>
	
<?php

	// Get the amount of posts in the topic
	$query = array(
		'SELECT'	=> 'count(p.id)',
		'FROM'		=> 'pan_pm AS p',
		'WHERE'		=> 'topic_id='.$tid
	);

//	($hook = get_hook('po_topic_review_qr_get_post_count')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_page['total_post_count'] = $forum_db->result($result, 0);

	// Get posts to display in topic review
	$query = array(
		'SELECT'	=> 'id, topic_id, sender, sender_id, receiver, receiver_id, subject, message, posted, status',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'topic_id='.$tid,	//.' AND id!='.$pid,
		'ORDER BY'	=> 'id DESC',
	);

//	($hook = get_hook('po_topic_review_qr_get_topic_review_posts')) ? eval($hook) : null;
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	$posts = array();
	while ($cur_post = $forum_db->fetch_assoc($result))
	{
		$posts[] = $cur_post;
	}

?>
	<div class="main-subhead">
		<h2 class="hn"><span><?php echo $lang_post['Topic review'] ?></span></h2>
	</div>
	
	<div class="main-content main-topic">
<?php

	$forum_page['item_count'] = 0;
	$forum_page['item_total'] = count($posts);

	foreach ($posts as $cur_post)
	{
		++$forum_page['item_count'];

		$forum_page['message'] = parse_message($cur_post['message'], 0);

		// Generate the post heading
		$forum_page['post_ident'] = array();
		$forum_page['post_ident']['num'] = '<span class="post-num">'.forum_number_format($forum_page['total_post_count'] - $forum_page['item_count'] + 1).'</span>';
		$forum_page['post_ident']['byline'] = '<span class="post-byline">'.sprintf($lang_post['Post byline'], '<strong>'.forum_htmlencode($cur_post['sender']).'</strong>').'</span>';
		$forum_page['post_ident']['link'] = '<span class="post-link"><a class="permalink" rel="bookmark" title="'.$lang_post['Permalink post'].'" href="'.forum_link($forum_url['post'], $cur_post['id']).'">'.format_time($cur_post['posted']).'</a></span>';

		($hook = get_hook('po_topic_review_row_pre_display')) ? eval($hook) : null;

?>
		<div class="post<?php if ($forum_page['item_count'] == 1) echo ' firstpost'; ?><?php if ($forum_page['item_total'] == $forum_page['item_count']) echo ' lastpost'; ?>">
			<div class="posthead">
				<h3 class="hn post-ident"><?php echo implode(' ', $forum_page['post_ident']) ?></h3>
<?php ($hook = get_hook('po_topic_review_new_post_head_option')) ? eval($hook) : null; ?>
			</div>
			<div class="postbody">
				<div class="post-entry">
					<div class="entry-content">
						<?php echo $forum_page['message']."\n" ?>
<?php ($hook = get_hook('po_topic_review_new_post_entry_data')) ? eval($hook) : null; ?>
					</div>
				</div>
			</div>
		</div>
<?php

	}

?>
	</div>
<?php

	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->
	
	require FORUM_ROOT.'footer.php';
}
else if ($section == 'new')
{
	$pms_outbox = array();
	$query = array(
		'SELECT'	=> 'id',
		'FROM'		=> 'pan_pm',
		'WHERE'		=> 'sender_id='.$forum_user['id'].' AND deleted_by_sender=0'
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

	while ($rows = $forum_db->fetch_assoc($result))
		$pms_outbox[] = $rows['id'];
	
	$num_outbox = count($pms_outbox);
	
	if ($num_outbox > $forum_config['o_pan_pm_max_outbox'])
		$errors[] = $lang_pan_pm['outbox_full'];
	
	// Setup breadcrumbs
	$forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		array($lang_pan_pm['private_message'], forum_link($forum_url['pan_pm_inbox'])),
		$lang_pan_pm['item_new']
	);
	
//	define('FORUM_PAGE', 'pan-pm-new');
	define('FORUM_PAGE', 'post');
	require FORUM_ROOT.'header.php';
	
	// START SUBST - <!-- forum_main -->
	ob_start();
?>
	
	<div class="main-content main-frm">
<?php
	// If there were any errors, show them
	if (!empty($errors))
	{
		$forum_page['errors'] = array();
		foreach ($errors as $cur_error)
			$forum_page['errors'][] = '<li class="warn"><span>'.$cur_error.'</span></li>';

		($hook = get_hook('po_pre_post_errors')) ? eval($hook) : null;
?>
		<div id="errors" class="ct-box error-box">
			<h2 class="warn hn"><?php echo $lang_post['Post errors'] ?></h2>
			<ul class="error-list">
				<?php echo implode("\n\t\t\t\t", $forum_page['errors'])."\n" ?>
			</ul>
		</div>
<?php
	}
	
	if (isset($uid) && $uid > 0)
	{
		$forum_page['pan_pm']['form_action'] = forum_link($forum_url['pan_pm_new_uid'], $uid);
		$forum_page['pan_pm']['csrf_token'] = generate_form_token(forum_link($forum_url['pan_pm_new_uid'], $uid));
	} else {
		$forum_page['pan_pm']['form_action'] = forum_link($forum_url['pan_pm_new']);
		$forum_page['pan_pm']['csrf_token'] = generate_form_token(forum_link($forum_url['pan_pm_new']));
	}
	
?>
		<form id="afocus" class="frm-form frm-ctrl-submit" method="post" accept-charset="utf-8" action="<?php echo $forum_page['pan_pm']['form_action'] ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo $forum_page['pan_pm']['csrf_token'] ?>" />
			</div>

<?php

	$o_pan_pm_active_contacts = $forum_config['o_pan_pm_active_contacts'] > 0 ? ' AND last_visit>'.(time()-($forum_config['o_pan_pm_active_contacts']*86400)).'' : '';

	$query = array(
		'SELECT'	=> 'g.g_id, g.g_title, g.g_user_title, g.g_id AS group_id, u.id, u.username, u.num_posts, u.registered',
		'FROM'		=> 'groups AS g',
		'JOINS'		=> array(
	 		array(
				'INNER JOIN'	=> 'users AS u',
				'ON'			=> 'g.g_id=u.group_id'
			)
		),
		'WHERE'		=> 'g.g_id!=2 AND u.id!='.$forum_user['id'] . $o_pan_pm_active_contacts,
		'ORDER BY'	=> 'g_title ASC, username ASC',
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

?>
			<fieldset class="mf-set mf-extra set">
				
				<legend><span><?php echo $lang_pan_pm['users'] ?></span></legend>
				<div class="mf-box">
					<div class="mf-field mf-field1">
						<label for="fld"><span></span></label><br />
						<span class="fld-input">
							<select id="fld" name="req_uid">
<?php
	while ($forum_poster = $forum_db->fetch_assoc($result))
		$users_list[] = $forum_poster;

	$cur_group = 0;
	foreach ($users_list as $forum_poster)
	{
		if ($forum_poster['g_id'] != $cur_group)
		{
			if ($cur_group)
				echo "\t\t\t".'</optgroup>'."\n";

			echo "\t\t\t".'<optgroup label="'.forum_htmlencode($forum_poster['g_title']).'">'."\n";
			$cur_group = $forum_poster['g_id'];
		}

		if($uid == $forum_poster['id'])
			echo "\t\t\t\t".'<option value="'.$forum_poster['id'].'" selected="selected">'.forum_htmlencode($forum_poster['username']).'</option>'."\n";
		else
			echo "\t\t\t\t".'<option value="'.$forum_poster['id'].'">'.forum_htmlencode($forum_poster['username']).'</option>'."\n";
	}
?>
							</select>
						</span>
					</div>
					
				</div>
			</fieldset>


			<fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
				<legend class="group-legend"><strong></strong></legend>
				
				<div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="sf-box text required longtext">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_pan_pm['subject'] ?></span></label><br />
						<span class="fld-input"><input id="fld<?php echo $forum_page['fld_count'] ?>" type="text" name="req_subject" value="<?php echo isset($_POST['req_subject']) ? forum_htmlencode($_POST['req_subject']) : '' ?>" size="<?php echo FORUM_SUBJECT_MAXIMUM_LENGTH ?>" placeholder="<?php echo $lang_pan_pm['new_topic'] ?>" /></span>
					</div>
				</div>
				
<?php ($hook = get_hook('po_pre_post_contents')) ? eval($hook) : null; ?>

				<div class="txt-set set<?php echo ++$forum_page['item_count'] ?>">
					<div class="txt-box textarea required">
						<label for="fld<?php echo ++$forum_page['fld_count'] ?>"><span><?php echo $lang_pan_pm['message'] ?></span></label>
						<div class="txt-input"><span class="fld-input"><textarea id="fld<?php echo $forum_page['fld_count'] ?>" name="req_message" rows="15" cols="95" required spellcheck="true"><?php echo (isset($_POST['req_message']) ? forum_htmlencode($message) : '') ?></textarea></span></div>
					</div>
				</div>
			</fieldset>

			<div class="sf-set set">
				<div class="sf-box checkbox">
					<input type="hidden" name="req_sticky" value="0" />
						<span class="fld-input"><input type="checkbox" id="fld" name="req_sticky" value="1" <?php echo (isset($_POST['req_sticky']) && (intval($_POST['req_sticky']) == 1)) ? 'checked="checked"' : '' ?> /></span>
						<label for="fld "><span><?php echo $lang_pan_pm['new_msg_sticky'] ?></span><?php echo $lang_pan_pm['new_msg_sticky_i'] ?></label>
				</div>
			</div>

			<div class="frm-buttons">
				<span class="submit primary"><input type="submit" name="pan_pm_sent" value="<?php echo $lang_pan_pm['send_msg'] ?>" <?php echo ($num_outbox > $forum_config['o_pan_pm_max_outbox']) ? 'disabled' : '' ?> /></span>
			</div>
		</form>

	</div>
	
<?php

	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->
	
	require FORUM_ROOT.'footer.php';
}


