<?php 
if (!defined('SAT_SHOP_PAGE')) die();

    $gid = isset($_GET['gid']) ? intval($_GET['gid']) : 0;
    $query = array(
	'SELECT'	=> '*',
	'FROM'		=> 'sat_goods',
	'WHERE'		=> 'id='.$gid
    );
    $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
    $sat_goods = $forum_db->fetch_assoc($result);

    $email = $forum_user['email'];

    if ($sat_goods && isset($sat_goods['cid'])) {
      	    $query = array(
		'SELECT'	=> '*',
		'FROM'		=> 'sat_category',
		'WHERE'		=> 'id='.$sat_goods['cid']
     	    );
      	    $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
            $sat_cat = $forum_db->fetch_assoc($result);

            $sat_goods['catname'] = $sat_cat['name'];
            $sat_goods['catden'] = isset($sat_cat['denyaccess']) ? in_array($forum_user['id'], explode (',', $sat_cat['denyaccess'])) : FALSE;
	    $sat_goods['catlink'] = FORUM_ROOT.
		'misc.php/?action=goodscat&cid='.$sat_goods['cid'];
    }

    if ($sat_goods && $sat_goods['exist'] == 1 && !$sat_goods['catden']) {
	define('FORUM_PAGE', 'sat-order');
	// Setup breadcrumbs
        $forum_page['crumbs'] = array(
		array($forum_config['o_board_title'], forum_link($forum_url['index'])),
		arrey($sat_goods['name'], FORUM_ROOT.'misc.php?action=goods&gid='.$gid),
		'Оформление заказа'
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
	<h2 class="hn"><span>Шаг 1 из 3: Заполнение контактной информации</span></h2>
</div>
<div class="main-content">
    <form action='/misc.php/?action=shpage&p=payment' method=POST>
	<input type=hidden name=gid value=<?php echo $sat_goods['id'] ?>>



	<input type=submit value='Оплатить'>
    </form>
</div>

<?php
	$tpl_temp = forum_trim(ob_get_contents());
	$tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
	ob_end_clean();
	// END SUBST - <!-- forum_main -->
	
	require FORUM_ROOT.'footer.php';
    } else {
	message($lang_common['Bad request']);
    }