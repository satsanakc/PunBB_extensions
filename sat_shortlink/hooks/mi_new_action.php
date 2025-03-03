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
			'WHERE'		=> 'user_id='.$_GET['uid'],
			'ORDER BY'	=> 'id DESC'
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$links = array();
		while ($cur_link = $forum_db->fetch_assoc($result)) {
			$links[] = $cur_link;
		}

		$forum_page['crumbs'] = array(
			array($forum_config['o_board_title'], forum_link($forum_url['index'])),
			$lang_sat_shortlink['mylinks']
		);
		$forum_page['main_head'] = end($forum_page['crumbs']);
		define('FORUM_PAGE', 'shortlinks');
		require FORUM_ROOT.'header.php';
		
		// START SUBST - <!-- forum_main -->
		ob_start();
?>
		<div class="main-head">
			<p class="options"><span><a class="sub-option" href="javascript://" onclick="createShortUrl('<?php echo $lang_sat_shortlink['address'] ?>', '<?php echo $lang_sat_shortlink['error'] ?>')" title="<?php echo $lang_sat_shortlink['new'] ?>"><?php echo $lang_sat_shortlink['new'] ?></a></span></p>
		</div>
<?php	
		if (!empty($links)) {

			$forum_page['table_header'] = array();
			$forum_page['table_header']['url'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['url'].'</th>';
			$forum_page['table_header']['link'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['link'].'</th>';
			$forum_page['table_header']['generated'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['generated'].'</th>';
			$forum_page['table_header']['visits'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['visits'].'</th>';
			$forum_page['table_header']['buttons'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['buttons'].'</th>';
			
?>
		<div class="main-content">
			<div class="ct-group">
				<table id="sat_shortlinks">
					<thead>
						<tr>
							<?php echo implode("\n\t\t\t\t\t\t", $forum_page['table_header'])."\n" ?>
						</tr>
					</thead>
					<tbody>
<?php
			$forum_page['item_count'] = 0;
			foreach ($links as $link) {
				$query = array(
					'SELECT'	=> 'COUNT(link_id)',
					'FROM'		=> 'sat_linksvisit',
					'WHERE'		=> 'link_id='.$link['id']
				);
				$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
				$res = $forum_db->result($result);
				
				$forum_page['table_row'] = array();
				$forum_page['table_row']['url'] = '<td class="tc'.count($forum_page['table_row']).'"><a href="'.$link['url'].'">'.$link['url'].'</a></td>';
				$forum_page['table_row']['link'] = '<td class="tc'.count($forum_page['table_row']).'"><a href="'.forum_link('?link='.$link['link']).'">'.forum_link('?link='.$link['link']).'</a></td>';
				$forum_page['table_row']['generated'] = '<td class="tc'.count($forum_page['table_row']).'">'.format_time($link['generated']).'</td>';
				if ($res > 0)
					$forum_page['table_row']['visits'] = '<td class="tc'.count($forum_page['table_row']).'"><a href="'.forum_link('misc.php?action=linkvisits&lid='.$link['id']).'">'.$res.'</a></td>';
				else
					$forum_page['table_row']['visits'] = '<td class="tc'.count($forum_page['table_row']).'">0</td>';
				$forum_page['table_row']['buttons'] = '<td class="tc'.count($forum_page['table_row']).'"><input type="button" title="'.$lang_sat_shortlink['copy'].'" value="&#xf0c5" name="copy" onclick="copyShortUrl(this, \''.$lang_sat_shortlink['copied'].'\')"><input type="button" title="'.$lang_sat_shortlink['delete'].'" value="&#xf2ed" name="delete" data-id="'.$link['id'].'" onclick="delShortUrl(this, \''.$lang_sat_shortlink['warning'].'\')"></td>';
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
} else if ($action == 'linkvisits') {
	if(!empty($_GET['lid'])) {
		$keys = array('REMOTE_PORT', 'REMOTE_ADDR', 'HTTP_REFERER', 'HTTP_USER_AGENT', 'HTTP_SEC_CH_UA_PLATFORM', 'HTTP_SEC_CH_UA_MOBILE', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP');
		$query = array(
			'SELECT'	=> 'v.id, v.serv, l.user_id, l.url, l.user_id',
			'FROM'		=> 'sat_linksvisit AS v',
			'JOINS'		=> array(
				array(
					'LEFT JOIN'		=> 'sat_shortlinks AS l',
					'ON'			=> 'l.id = v.link_id'
				)
			),
			'WHERE'		=> 'v.link_id='.$_GET['lid'],
			'ORDER BY'	=> 'id DESC'
		);
		$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
		$visits = array();
		while ($cur_visit = $forum_db->fetch_assoc($result)) {
			$visits[] = $cur_visit;
		}
		
		if (!empty($visits) && ($visits[0]['user_id'] == $forum_user['id'] || $forum_user['is_admmod'])) {
			$forum_page['crumbs'] = array(
				array($forum_config['o_board_title'], forum_link($forum_url['index'])),
				array($lang_sat_shortlink['mylinks'], forum_link('misc.php?action=mylinks&uid='.$forum_user['id'])),
				$visits[0]['url']
			);

			$forum_page['main_head'] = end($forum_page['crumbs']);
			define('FORUM_PAGE', 'linkvisits');
			require FORUM_ROOT.'header.php';
		
			// START SUBST - <!-- forum_main -->
			ob_start();
?>
		<div class="main-content">
			<div class="ct-group">
<?php
			foreach ($visits as $visit) {
				$visit['serv'] = json_decode($visit['serv']);
				$forum_page['table_header'] = array();
				$forum_page['table_header']['key'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['key'].'</th>';
				$forum_page['table_header']['val'] = '<th class="tc'.count($forum_page['table_header']).'" scope="col">'.$lang_sat_shortlink['val'].'</th>';
?>
				<table class="sat_linkvisits">
					<caption><?php echo format_time($visit['serv']->{'REQUEST_TIME'}) ?></caption>
					<thead>
						<tr>
							<?php echo implode("\n\t\t\t\t\t\t", $forum_page['table_header'])."\n" ?>
						</tr>
					</thead>
					<tbody>
<?php
				$forum_page['item_count'] = 0;
				foreach ($visit['serv'] as $key => $val) { if (in_array($key, $keys)) {
					$forum_page['table_row'] = array();
					$forum_page['table_row']['key'] = '<td class="tc'.count($forum_page['table_row']).'">'.$key.'</td>';
					$forum_page['table_row']['val'] = '<td class="tc'.count($forum_page['table_row']).'">'.$val.'</td>';
					++$forum_page['item_count'];
?>
						<tr class="<?php echo ($forum_page['item_count'] % 2 != 0) ? 'odd' : 'even' ?>">
							<?php echo implode("\n\t\t\t\t\t\t", $forum_page['table_row'])."\n" ?>
						</tr>
<?php
				}}
?>
					</tbody>
				</table>
<?php
			}
?>
			</div>
		</div>
<?php
			$tpl_temp = forum_trim(ob_get_contents());
			$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
			ob_end_clean();
			// END SUBST - <!-- forum_main -->

			require FORUM_ROOT.'footer.php';
		}
	}
} else if ($action == 'deletelink') {
	$query = array(
		'DELETE'	=> 'sat_shortlinks',
		'WHERE'		=> 'id='.$_POST['id']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$query = array(
		'DELETE'	=> 'sat_linksvisit',
		'WHERE'		=> 'link_id='.$_POST['id']
	);
	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$forum_db->end_transaction();
	$forum_db->close();
	echo $result;
	exit;
} else if ($action == 'gentoken') {
	send_json(array(
		'link' => forum_link('misc.php?action='.$_GET['act']),
		'token' => generate_form_token(forum_link('misc.php?action='.$_GET['act']))
	));
	exit;
}
