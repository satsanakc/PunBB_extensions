<?php 
if(!defined('SAT_PAGES_PLIST')) die();

$forum_loader->add_js('js/optfunc.js', array('type' => 'url', 'group' => FORUM_JS_GROUP_COUNTER));

($hook = get_hook('sat_pages_plist_start')) ? eval($hook) : null;

require FORUM_ROOT.'header.php';

// START SUBST - <!-- forum_main -->
ob_start();

$query = array(
	'SELECT'	=> 'sat_pages.id, sat_pages.name, sat_pages.category_id AS cid, sat_category.name AS cname, sat_category.announce AS cann, sat_category.denyaccess AS cden',
	'FROM'		=> 'sat_pages LEFT JOIN sat_category ON sat_pages.category_id=sat_category.id',
	'ORDER BY'	=> 'sat_pages.category_id'
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
$page_list = array();
while ($row = $forum_db->fetch_assoc($result)) {
	$page_list[] = $row;
}
$query = array(
	'SELECT'	=> 'sat_pages.id, sat_pages.name, sat_category.id AS cid, sat_category.name AS cname, sat_category.announce AS cann, sat_category.denyaccess AS cden',
	'FROM'		=> 'sat_pages RIGHT JOIN sat_category ON sat_pages.category_id=sat_category.id',
	'WHERE'		=> 'sat_pages.id IS NULL',
	'ORDER BY'	=> 'sat_category.id'
);
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
while ($row = $forum_db->fetch_assoc($result)) {
	$page_list[] = $row;
}

$curcat = (isset($page_list) && isset($page_list[0])) ? $page_list[0]['cid'] : 0;
?>

	<div class="main-subhead">
		<h2 class="hn"><span><?php echo $lang_sat_page['pagelist'] ?></span></h2>
	</div>
	<div class="main-content main-frm" style="position:relative">
		<div class="content-head">
			<h2 class="hn"><span>
				<strong class="category"><?php echo $lang_sat_page['category'] ?></strong>
				<strong class="pages"><?php echo $lang_sat_page['pages'] ?></strong>
			</span></h2>
		</div>
		<div id="category<?php echo $curcat ?>" class="sat-pages-category">
			<div class="category">
<?php
if (!$curcat) 			echo "<p><span>{$lang_sat_page['nocat']}</span></p>";
else {$value = $page_list[0]; ?>
				<p>
<strong><a href="<?php echo $base_url ?>/misc.php?action=category&cid=<?php echo $curcat ?>"><?php echo $value['cname'] ?></a></strong>
<span class="buttons">
	<a class="edit" href="?action=cedit&cid=<?php echo $curcat ?>"><?php echo $lang_sat_page['edit'] ?></a>
	<a class="delete" href="options.php?action=cdelete&cid=<?php echo $value['cid'] ?>"><?php echo $lang_sat_page['del'] ?></a>
</span>
				</p>
<?php
}
			echo '</div>';
			echo '<div class="pages">';

foreach ($page_list as $key => $value) {
  if ($curcat != $value['cid']) {
    $curcat = $value['cid'];
?>
			</div>
		</div>
		<div id="category<?php echo $curcat ?>" class="sat-pages-category">
			<div class="category">
				<p>
<strong><a href="<?php echo $base_url ?>/misc.php?action=category&cid=<?php echo $curcat ?>"><?php echo $value['cname'] ?></a></strong>
<span class="buttons">
	<a class="edit" href="?action=cedit&cid=<?php echo $curcat ?>"><?php echo $lang_sat_page['edit'] ?></a>
	<a class="delete" href="options.php?action=cdelete&cid=<?php echo $value['cid'] ?>"><?php echo $lang_sat_page['del'] ?></a>
</span>
				</p>
			</div>
			<div class="pages">
<?php }
				echo '<p class="pages-item">';
if ($value['id']) {
?>
<span><a href="<?php echo $base_url ?>/misc.php?action=page&pid=<?php echo $value['id'] ?>"><?php echo $value['name'] ?></a></span>
<span class="buttons">
	<a class="edit" href="options.php?action=pedit&pid=<?php echo $value['id'] ?>"><?php echo $lang_sat_page['edit'] ?></a>
	<a class="delete" href="options.php?action=pdelete&pid=<?php echo $value['id'] ?>"><?php echo $lang_sat_page['del'] ?></a>
</span>
<?php }
else 					echo '&mdash;';
				echo '</p>';
} ?>
			</div>
		</div>
		<div id="sat-pages-temp" style="overflow:hidden;z-index:1">
			<button type="button" class="close" style="position:absolute;top:0;right:0;z-index:1">&#10006;</button>
			<div class="sat-content"></div>
		</div>
	</div>