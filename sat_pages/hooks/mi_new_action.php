<?php

if (!defined('FORUM')) die();

if ($action == 'page' || isset($sat_pid)) {
    if (!isset($sat_pid)) $sat_pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
    if (!isset($sat_page)) {
      $query = array(
	'SELECT'	=> '*',
	'FROM'		=> 'sat_pages',
	'WHERE'		=> 'id='.$sat_pid
      );
      $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
      $sat_page = $forum_db->fetch_assoc($result);
    }

    if ($sat_page) {
      if ($sat_page['category_id']) {
      	    $query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'sat_category',
		'WHERE'		=> 'id='.$sat_page['category_id']
     	    );
      	    $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
            $sat_cat = $forum_db->fetch_assoc($result);
            $sat_page['catname'] = $sat_cat['name'];
            $sat_page['catden'] = $sat_cat['denyaccess'];
	    $sat_page['catlink'] = FORUM_ROOT.
		'misc.php/?action=category&cid='.$sat_page['category_id'];
      }

      if (!isset($sat_page['catden'])) $sat_page['catden'] = '0';
      $sat_page['catden'] = explode (',', $sat_page['catden']);
      if (!isset($sat_page['denyaccess'])) $sat_page['denyaccess'] = '0';
      $sat_page['denyaccess'] = explode (',', $sat_page['denyaccess']);

      if (in_array($forum_user['group_id'], $sat_page['catden']) || in_array($forum_user['group_id'], $sat_page['denyaccess'])) {
	message($lang_common['No permission']);
      } else {
	if ($sat_page['name'] == 'Index') define('FORUM_PAGE', 'home');
	else define('FORUM_PAGE', 'sat-page');

	// Setup breadcrumbs
	if (FORUM_PAGE != 'home') {
	    $forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		$sat_page['name']
	    );
	    if ($sat_page['category_id']) {
	    	array_insert ($forum_page['crumbs'], 1, array(
		    $sat_page['catname'], $sat_page['catlink']));
	    }
	}

	require FORUM_ROOT.'header.php';
	
	// START SUBST - <!-- forum_main -->
	ob_start();
	if (FORUM_PAGE != 'home') {
?>
	<div class="main-head">
		<h2 class="hn"><span><?php echo $sat_page['name'];?></span></h2>
	</div>
<?php } ?>
	<div class="main-content main-message">
		<?php 
			$sat_page['txt'] = '?>'.forum_trim($sat_page['txt']).'<?php ';
			eval ($sat_page['txt']);
		?>
	</div>
<?php
	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->
	
	require FORUM_ROOT.'footer.php';
      }
    } else {
	message($lang_common['Bad request']);
    }
} elseif ($action == 'category') {
    $sat_cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
    $query = array(
	'SELECT'	=> 'sat_pages.id, sat_pages.name AS name, sat_category.name AS catname, sat_category.announce, sat_category.showcont, sat_category.denyaccess AS catden, sat_pages.denyaccess AS pageden, sat_pages.txt',
	'FROM'		=> 'sat_pages, sat_category',
	'WHERE'		=> 'sat_pages.category_id=sat_category.id AND sat_pages.category_id='.$sat_cid
      );
    $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
    $sat_cat = array();
    while ($row = $forum_db->fetch_assoc($result)) {
	$sat_cat[] = $row;
    }
    foreach ($sat_cat as $key => $value) {
      if (!isset($value['catden'])) $value['catden'] = '0';
      $sat_cat[$key]['catden'] = explode (',', $value['catden']);
      if (!($value['pageden'])) $value['pageden'] = '0';
      $sat_cat[$key]['pageden'] = explode (',', $value['pageden']);
    }

    if ($sat_cat) {
      if (in_array($forum_user['group_id'], $sat_cat[0]['catden'])) {
	message($lang_common['No permission']);
      } else {
	define('FORUM_PAGE', 'sat-category');

	// Setup breadcrumbs
	$forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		$sat_cat[0]['catname']
	);

	require FORUM_ROOT.'header.php';
	
	// START SUBST - <!-- forum_main -->
	ob_start();
?>
	<div class="main-head">
		<h2 class="hn"><span><?php echo $sat_cat[0]['catname'];?></span></h2>
	</div>
	<div id="category<?php echo $sat_cid;?>" class="main-content sat-pages-category">
<?php
	$count = 0;
        foreach ($sat_cat as $page) {
	  if (!in_array($forum_user['group_id'], $page['pageden'])) {
	  $count++;
?>
	    <div id="p<?php echo $page['id']?>" class="page-item">
<?php if ($page['showcont'] == 1) { ?>
		<div class="page-head">
		    <h3 class="hn"><span>
			<a href="<?php echo FORUM_ROOT.'misc.php?action=page&pid='.$page['id']?>">
			  <?php echo $page['name'];?></a>
		    </span></h3>
		</div>
		<div class="page-entry">
		    <div class="page-content">
			<?php echo $page['txt'];?>
		    </div>
		</div>
<?php } else { ?>
		<div class="page-entry">
		    <a href="<?php echo FORUM_ROOT.'misc.php?action=page&pid='.$page['id']?>">
			  <?php echo $page['name'];?></a>
		</div>
<?php } ?>
	    </div>
<?php
	  }
	}
	if (!$count) {
	  echo '<div class="page-entry">'.$lang_common['No view'].'</div>';
	}
	echo '</div>';

	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->
	
	require FORUM_ROOT.'footer.php';
      }
    } else {
	message($lang_common['Bad request']);
    }
}