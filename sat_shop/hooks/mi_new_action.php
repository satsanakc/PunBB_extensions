<?php

if (!defined('FORUM')) die();

if ($action == 'goods') {
    $gid = isset($_GET['gid']) ? intval($_GET['gid']) : 0;
    $query = array(
	'SELECT'	=> '*',
	'FROM'		=> 'sat_shop_goods',
	'WHERE'		=> 'id='.$gid
    );
    $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
    $sat_goods = $forum_db->fetch_assoc($result);

    if ($sat_goods) {
      if ($sat_goods['cid']) {
      	    $query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'sat_category',
		'WHERE'		=> 'id='.$sat_goods['cid']
     	    );
      	    $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
            $sat_cat = $forum_db->fetch_assoc($result);
            $sat_goods['catname'] = $sat_cat['name'];
            $sat_goods['catden'] = $sat_cat['denyaccess'];
	    $sat_goods['catlink'] = FORUM_ROOT.
		'misc.php/?action=goodscat&cid='.$sat_goods['cid'];
      }

      if (!isset($sat_goods['catden'])) $sat_goods['catden'] = '0';
      $sat_goods['catden'] = explode (',', $sat_goods['catden']);

      if (in_array($forum_user['group_id'], $sat_goods['catden'])) {
	message($lang_common['No permission']);
      } else {
	define('FORUM_PAGE', 'sat-goods');
	// Setup breadcrumbs
        $forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		$sat_goods['name']
	);
	if ($sat_goods['cid']) {
	    	array_insert ($forum_page['crumbs'], 1, array(
		    $sat_goods['catname'], $sat_goods['catlink']));
	}

	require FORUM_ROOT.'header.php';
	
	// START SUBST - <!-- forum_main -->
	ob_start();
?>
	<div class="main-head">
		<h2 class="hn"><span><?php echo $sat_goods['name'];?></span></h2>
	</div>
	<div class="main-content main-message">
		<div class="txt"><?php echo $sat_goods['txt'] ?></div>
		<div class="buy">
			<div class="price"><?php
				if ($sat_goods['price'] != 0 && $sat_goods['exist'] == 1)
					echo '<p>'.$sat_goods['price'].' руб.</p><a class="button" href="'.FORUM_ROOT.'misc.php/?action=shpage&p=order&gid='.$gid.'">Купить</a>';
				else echo 'Нет в наличии';
			?></div>
			<div class="discuss"><a href="<?php echo $sat_goods['discuss'] ?>" target="_blank">Обсудить</a></div>
		</div>
		<div class="desc"><?php echo $sat_goods['desc'] ?></div>
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
} elseif ($action == 'goodscat') {
    $sat_cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
    $query = array(
	'SELECT'	=> 'sat_shop_goods.id, sat_shop_goods.name AS name, sat_category.name AS catname, sat_category.announce, sat_category.denyaccess AS catden, sat_shop_goods.exist, sat_shop_goods.price, sat_shop_goods.txt, sat_shop_goods.discuss',
	'FROM'		=> 'sat_shop_goods, sat_category',
	'WHERE'		=> 'sat_shop_goods.cat=sat_category.id AND sat_shop_goods.cat='.$sat_cid
    );
    $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
    $sat_cat = array();
    while ($row = $forum_db->fetch_assoc($result)) {
	$sat_cat[] = $row;
    }
    foreach ($sat_cat as $key => $value) {
      if (!isset($value['catden'])) $value['catden'] = '0';
      $sat_cat[$key]['catden'] = explode (',', $value['catden']);
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
	  if ($page['price'] != 0 && $page['exist'] != 0)) {
	  $count++;
?>
	    <div id="p<?php echo $page['id']?>" class="page-item">
		<div class="page-head">
		    <h3 class="hn"><span>
			<a href="<?php echo FORUM_ROOT.'misc.php?action=goods&gid='.$page['id']?>">
			  <?php echo $page['name'];?></a>
		    </span></h3>
		</div>
		<div class="page-entry">
		    <div class="page-content">
			<?php echo $page['txt'];?>
		    </div>
			<div class="buy">
				<div class="price"><?php
					echo '<p>'.$page['price'].' руб.</p><a class="button" href="'.FORUM_ROOT.'misc.php/?action=shpage&p=order&gid='.$page['id'].'">Купить</a>';
				?></div>
				<div class="discuss"><a href="<?php echo $page['discuss'] ?>" target="_blank">Обсудить</a></div>
			</div>
		</div>

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
} elseif ($action == 'shpage') {
  define('SAT_SHOP_PAGE', 1);
  require FORUM_ROOT.'pages/'.$_GET['p'].'.php';
}