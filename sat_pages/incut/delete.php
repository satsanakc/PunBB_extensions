<?php 
if(!defined('SAT_PAGES_CDELETE') && !defined('SAT_PAGES_PDELETE')) die();

$id = defined('SAT_PAGES_CDELETE') ? $_GET['cid'] : $_GET['pid'];

		$forum_page['crumbs'] = array(
			array($forum_config['o_board_title'], forum_link($forum_url['index'])),
			array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
			array($lang_sat_page['info_pages'], forum_link($forum_url['sat_pages'])),
			(defined('SAT_PAGES_CDELETE') ? $lang_sat_page['delcat'] : $lang_sat_page['delpage'])
		);

($hook = get_hook('sat_pages_delpage_start')) ? eval($hook) : null;

require FORUM_ROOT.'header.php';

// START SUBST - <!-- forum_main -->
ob_start();
?>
	<div class="main-subhead">
		<h2 class="hn"><span><?php print (defined('SAT_PAGES_CDELETE') ? $lang_sat_page['delcat'] : $lang_sat_page['delpage']) ?></span></h2>
	</div>
	<div class="main-content main-frm">
		<form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['sat_pages']) ?>">
			<div class="hidden">
				<input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['sat_pages'])) ?>" />
			</div>
			<div class="hidden">
				<input type="hidden" name="prev_url" value="<?php echo forum_link($forum_url['sat_pages']) ?>" />
			</div>
			<div class="hidden">
				<input type="hidden" name="<?php print (defined('SAT_PAGES_CDELETE') ? 'cid': 'pid') ?>" value="<?php echo $id ?>" />
			</div>
			<div class="ct-box">
				<p><?php
$table = defined('SAT_PAGES_CDELETE') ? 'sat_category' : 'sat_pages';
$query = array(
	'SELECT'	=> 'name',
	'FROM'		=> $table,
	'WHERE'		=> 'id='.$id
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
$res = $forum_db->fetch_assoc($result);
if (!$res) message($lang_common['Bad request']);
$reslink = '<a href="'.forum_link('misc.php?action='.(defined('SAT_PAGES_CDELETE') ? 'category&cid=' : 'page&pid=').$id).'" target="_blank">'.$res['name'].'</a>';
$delmess = defined('SAT_PAGES_CDELETE') ? $lang_sat_page['delcatmess'] : $lang_sat_page['delpagemess'];
$delmess = str_replace('%s', $reslink, $delmess);
echo $delmess;
				?></p>
			</div>
				<div class="frm-buttons">
<span class="submit primary"><input type="submit" name="<?php print (defined('SAT_PAGES_CDELETE') ? 'conf_del_cat': 'conf_del_page') ?>" value="<?php echo $lang_sat_page['del'] ?>"></span>
<span class="cancel"><input type="submit" name="confirm_cancel" value="<?php echo $lang_common['Cancel'] ?>" /></span>
				</div>
		</form>
	</div>