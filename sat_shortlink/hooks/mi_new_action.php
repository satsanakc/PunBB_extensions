<?php
if (!defined('FORUM')) die();

if ($action == 'createlink') {
	$linkchars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$link = substr(str_shuffle($linkchars), 0, 6);
	$str = time().', '.$forum_user['id'].", '".$forum_db->escape($_POST['url'])."', '".$link."'";
	$query = array(
		'INSERT'	=> 'generated, user_id, url, link',
		'INTO'		=> 'sat_shortlinks',
		'VALUES'	=> $str
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_db->end_transaction();
	$forum_db->close();
	echo $link;
	exit;
} else if ($action == 'mylinks') {
	if(!empty($_GET['uid']) && ($_GET['uid'] == $forum_user['id'] || $forum_user['is_admmod'])) {
		$query = array(
			'SELECT'	=> '*',
			'FROM'		=> 'sat_shortlinks',
			'WHERE'		=> 'user_id='.$forum_user['id'],
			'ORDER BY'	=> 'id'
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$links = array();
		while ($cur_link = $forum_db->fetch_assoc($result)) {
			$links[] = $cur_link;
		}

		$forum_page['crumbs'] = array(
			array($forum_config['o_board_title'], forum_link($forum_url['index'])), $lang_sat_shortlink['mylinks']
		);
		$forum_page['main_head'] = end($forum_page['crumbs']);
		define('FORUM_PAGE', 'shortlinks');
		require FORUM_ROOT.'header.php';
		
		// START SUBST - <!-- forum_main -->
		ob_start();
			
		if (!empty($links)) {

			$forum_page['table_header'] = array();
			$forum_page['table_header']['url'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['url'].'</th>';
			$forum_page['table_header']['link'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['link'].'</th>';
			$forum_page['table_header']['generated'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['generated'].'</th>';
			$forum_page['table_header']['buttons'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['buttons'].'</th>';
			
?>
		<div class="main-content">
			<div class="ct-group">
				<table id="sat_shortlinks">
					<caption><?php echo $lang_sat_shortlink['linktable'] ?></caption>
					<thead>
						<tr>
							<?php echo implode("\n\t\t\t\t\t\t", $forum_page['table_header'])."\n" ?>
						</tr>
					</thead>
					<tbody>
<?php
			$forum_page['item_count'] = 0;
			foreach ($links as $link) {
				$forum_page['table_row'] = array();
				$forum_page['table_row']['url'] = '<td class="tc'.count($forum_page['table_row']).'"><a href="'.$link['url'].'">'.$link['url'].'</a></td>';
				$forum_page['table_row']['link'] = '<td class="tc'.count($forum_page['table_row']).'"><a href="'.forum_link('?link='.$link['link']).'">'.forum_link('?link='.$link['link']).'</a></td>';
				$forum_page['table_row']['generated'] = '<td class="tc'.count($forum_page['table_row']).'">'.format_time($link['generated']).'</td>';
				$forum_page['table_row']['buttons'] = '<td class="tc'.count($forum_page['table_row']).'"></td>';
				++$forum_page['item_count'];
?>
						<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?>">
							<?php echo implode("\n\t\t\t\t\t\t", $forum_page['table_row'])."\n" ?>
						</tr>
<?php
			}
?>
					</tbody>
				</table>
			</div>
		</div>
<?php
		} else {
?>
		<div class="main-content">
			<div class="ct-box">
				<p><strong><?php echo $lang_sat_shortlink['nolinks'] ?></strong></p>
			</div>
		</div>
<?php
		}
		
		$tpl_temp = forum_trim(ob_get_contents());
		$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
		ob_end_clean();
		// END SUBST - <!-- forum_main -->

		require FORUM_ROOT.'footer.php';
	}
} else if ($action == 'gentoken') {
	send_json(array(
		'link' => forum_link('misc.php?action='.$_GET['act']),
		'token' => generate_form_token(forum_link('misc.php?action='.$_GET['act']))
	));
	exit;
}
