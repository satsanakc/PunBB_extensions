<?php if (!defined('FORUM')) die();

function setbbpanel() {
	global $sat_bbcodes;
	$tabindex = 0;
	$curgroup = '01textdecor';
	echo '<span class="butgroup '.$curgroup.'">';
	foreach ($sat_bbcodes as $key => $val) {
		if (isset($val['title'])) {
			$val['group'] = isset($val['group']) ? $val['group'] : '05default';
			echo ($val['group'] == $curgroup ? '' : '</span><span class="butgroup '.$val['group'].'">')."<input type=\"button\" title=\"{$val['title']}\" class=\"image\" data-tag=\"{$key}\" id=\"pun_bbcode_button_{$key}\" value=\"".(isset($val['value']) ? $val['value'] : ' ')."\" name=\"{$key}\" onclick=\"".(isset($val['onclick']) ? $val['onclick'] : "$('#bbcode_{$key}').toggle()")."\" tabindex=\"".++$tabindex."\">";
			$curgroup = $val['group'];
		}
	}
	echo '</span>';
}

function setbbadds() {
	global $sat_bbcodes;
        echo "<div id=\"bbconts\">";
	foreach ($sat_bbcodes as $key => $val) {
		if (isset($val['box']) && isset($val['title']))	echo "<div id=\"bbcode_{$key}\" style=\"display:none\"><div class=\"bbpanel-subhead\"><h2 class=\"hn\"><span>{$val['title']}</span></h2></div><div class=\"bbcont\">{$val['box']}</div></div>";
	}
        echo "</div>";
}

ob_start();
?>

<div id="pun_bbcode_bar">
	<div id="pun_bbcode_wrapper" class="graphical">
		<div id="pun_bbcode_buttons">
			<?php setbbpanel() ?>
		</div>
	</div>
</div>

<?php setbbadds() ?>

<?php
$sat_bbcode_panel = ob_get_contents();
ob_end_clean();
echo $sat_bbcode_panel;