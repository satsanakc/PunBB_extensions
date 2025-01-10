<?php if (!defined('FORUM')) die();

global $forum_user;

if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
	require $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
else
	require $ext_info['path'].'/lang/English.php';
$GLOBALS['lang_sat_bbcode'] = $lang_sat_bbcode;

require $ext_info['path'].'/include/color.php';

function fill_colors($colors) {
	$r = '';
	for($i=0; $i<count($colors); $i++) {
		$r .= "<span class=\"colbut\" style=\"background-color:{$colors[$i]}\" onclick=\"PUNBB.pun_bbcode.insert_text('[color={$colors[$i]}]', '[/color]');$('#bbcode_color').toggle()\"></span>";
	}
	return $r;
}

function fill_mark_colors($colors) {
	$r = '';
	for($i=0; $i<count($colors); $i++) {
		$r .= "<span class=\"colbut\" style=\"background-color:{$colors[$i]}\" onclick=\"PUNBB.pun_bbcode.insert_text('[mark={$colors[$i]}]', '[/mark]');$('#bbcode_mark').toggle()\"></span>";
	}
	return $r;
}

function sizelist() {
	$sizearr = array(8, 10, 12, 14, 16, 18, 20, 24, 36, 48);
	$res = '<div class="sizelist">';
	for($i=0; $i<count($sizearr); $i++) {
		$res .= '<p style="font-size:'.$sizearr[$i].'px" onclick="PUNBB.pun_bbcode.insert_text(\'[size='.$sizearr[$i].']\', \'[/size]\');$(\'#bbcode_size\').toggle()">'.$sizearr[$i].'px</p>';
	}
	return $res.'</div>';
}

function handle_size_tag($arg, $text) {
	$arg = is_numeric($arg) ? $arg : 12;
	$arg = $arg < 8 ? '8px' : ($arg > 72 ? '72px' : $arg.'px');
	return "<span style=\"font-size:".$arg.";line-height:".$arg."\">$text</span>";
}

require $ext_info['path'].'/include/font.php';
$GLOBALS['sat_fonts_arr'] = $sat_fonts_arr;

function fontlist() {
	global $sat_fonts_arr;
	$res = '<div class="fontlist">';
	foreach ($sat_fonts_arr as $key => $val) {
		$res .= '<span style="font-family:'.$val.'" onclick="PUNBB.pun_bbcode.insert_text(\'[font='.$key.']\', \'[/font]\');$(\'#bbcode_font\').toggle()">'.$key.'</span>';
	}
	return $res.'</div>';
}

function handle_font_tag($arg, $text) {
	global $sat_fonts_arr;
	return "<span style=\"font-family:".(isset($sat_fonts_arr[$arg]) ? $sat_fonts_arr[$arg] : $arg)."\">$text</span>";
}

function handle_spoiler_tag($sc, $st) {
	global $lang_sat_bbcode;
 	$sc = empty($sc) ? $lang_sat_bbcode['spoilcapt'] : $sc;
	return '</p><div class="quotebox spoilerbox"><div onclick="$(this).toggleClass(\'visible\'); $(this).next().toggleClass(\'visible\');" class="spoiler-caption">'.$sc.'</div><blockquote class=""><p>'.$st;
}

function handle_hide_tag($num, $txt) {
	global $lang_sat_bbcode, $forum_user, $forum_page, $cur_post;
	$num = empty($num) || $num < 1 ? 0 : $num;
	$box = '<div class="quotebox hidebox '.(($forum_user['is_guest'] != 1 && ($forum_user['num_posts'] >= $num || $forum_page['is_admmod'] == 1 || $cur_post['poster_id'] == $forum_user['id'])) ? 'unlock' : 'lock').'" data-hide="'.$num.'">';
	$cite = '<cite>'.sprintf($lang_sat_bbcode['hidden'], ($num == 0 ? '' : sprintf($lang_sat_bbcode['userhide'], $num))).'</cite>';
	$block = $forum_user['is_guest'] == 1 ? $lang_sat_bbcode['ghidemsg'] :
		(($forum_user['num_posts'] >= $num || $forum_page['is_admmod'] == 1 || $cur_post['poster_id'] == $forum_user['id']) ? $txt :
		sprintf($lang_sat_bbcode['uhidemsg'], $num - $forum_user['num_posts']));
	$block = '<blockquote><p>'.$block.'</p></blockquote>';
	return $box.$cite.$block.'</div>';
}

function handle_you_tag() {
	global $forum_user;
	return '<span class="you">'.$forum_user['username'].'</span>';
}

require $ext_info['path'].'/include/smile.php';
$GLOBALS['sat_smile_arr'] = $sat_smile_arr;

function smilelist() {
	global $sat_smile_arr;
	if (count($sat_smile_arr) > 1) {
		$res = '<ul class="tabs">';
			foreach ($sat_smile_arr as $key => $val) {
				$res .= "<li class=\"t-{$key}".($key == 0 ? " active" : "")."\"><a href=\"javascript:void(0)\" onclick=\"smiletabs($(this).parent())\">{$val['name']}</a></li>";
			}
		$res .= '</ul>';
	} else {
		$res = '';
	}
	foreach ($sat_smile_arr as $key => $val) {
		$test = '';
		$res .= "<div class=\"t-{$key}".($key == 0 ? " active" : "")."\">";
		foreach ($val['list'] as $key1 => $val1) {
			if ($test != $val1) {
				$res .= "<img src=\"{$val1}\" alt=\"{$key1}\" title=\"{$key1}\" onclick=\"PUNBB.pun_bbcode.insert_text(' {$key1} ', '');$('#bbcode_smile').toggle()\"> ";
				$test = $val1;
			}
		}
		$res .= "</div>";
	}
	return $res;
}

($hook = get_hook('sat_bbcode_add_func')) ? eval($hook) : null;


// Parameter list (you can not indicate irrelevant or indicate false):
// title - tag name in panel, do not set if the button is not needed;
// group - tag group in panel;
// weight - tag position in group;
// onclick - response to a button on the panel, do not set if there is a box parameter;
// box - container with additional tag attributes;

// endtag - adds check for tags_opened/tags_closed, true by default;
// nesting - nesting depth for nested tags, indicate the number;
// ignore - ignore tags inside (for example [code]);
// block - the result is displayed in a block, cannot be displayed inside inline elements;
// inline - support automatic tag transfer to a new paragraph;
// fix - check for incorrect nesting for inline tags;
// del_quotes - removing quotes from an argument;
// use_in_link - the tag can be used in link headers and in list items;

// pattern, replace - replacement template and page code.


$sat_bbcodes = array(
	'b'		=> array(
		'title'		=>	$lang_sat_bbcode['b'],
		'value'		=>	'&#xf032',
		'group'		=>	'01textdecor',
		'weight'	=>	2,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[b]\', \'[/b]\')'
	),
	'i'		=> array(
		'title'		=>	$lang_sat_bbcode['i'],
		'value'		=>	'&#xf033',
		'group'		=>	'01textdecor',
		'weight'	=>	4,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[i]\', \'[/i]\')'
	),
	'u'		=> array(
		'title'		=>	$lang_sat_bbcode['u'],
		'value'		=>	'&#xf0cd',
		'group'		=>	'01textdecor',
		'weight'	=>	6,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[u]\', \'[/u]\')'
	),
	's'		=> array(
		'title'		=>	$lang_sat_bbcode['s'],
		'value'		=>	'&#xf0cc',
		'group'		=>	'01textdecor',
		'weight'	=>	8,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[s]\', \'[/s]\')',

		'inline'	=>	true,
		'fix'		=>	true,
		'use_in_link'	=>	true,

		'pattern'	=>	array('#\[s\](.*?)\[/s\]#ms'),
		'replace'	=>	array('<del>$matches[1]</del>')
	),
	'color'		=> array(
		'title'		=>	$lang_sat_bbcode['color'],
		'value'		=>	'&#xf53f',
		'group'		=>	'02colorsAndFonts',
		'weight'	=>	10,
		'box'		=>	'<div class="palette">'.fill_colors($sat_colors).'</div>',
		'nesting'	=>	10
	),
	'mark'		=> array(
		'title'		=>	$lang_sat_bbcode['mark'],
		'value'		=>	'&#xf591',
		'group'		=>	'02colorsAndFonts',
		'weight'	=>	14,
		'box'		=>	'<div class="palette">'.fill_mark_colors($sat_colors).'</div>',

		'inline'	=>	true,
		'fix'		=>	true,
		'use_in_link'	=>	true,
		'nesting'	=>	10,

		'pattern'	=>	array(
'#\[mark=([a-zA-Z]{3,20}|\#[0-9a-fA-F]{6}|\#[0-9a-fA-F]{3})\](.*?)\[/mark\]#ms',
'#\[mark\](.*?)\[/mark\]#ms'
					),
		'replace'	=>	array(
'<span style=\"background-color:$matches[1]\">$matches[2]</span>',
'<span style=\"background-color:yellow;color:black\">$matches[1]</span>'
					)
	),
	'sup'		=> array(
		'title'		=>	$lang_sat_bbcode['sup'],
		'value'		=>	'&#xf12b',
		'group'		=>	'01textdecor',
		'weight'	=>	10,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[sup]\', \'[/sup]\')',

		'inline'	=>	true,
		'fix'		=>	true,
		'use_in_link'	=>	true,

		'pattern'	=>	array('#\[sup\](.*?)\[/sup\]#ms'),
		'replace'	=>	array('<sup>$matches[1]</sup>')
	),
	'sub'		=> array(
		'title'		=>	$lang_sat_bbcode['sub'],
		'value'		=>	'&#xf12c',
		'group'		=>	'01textdecor',
		'weight'	=>	12,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[sub]\', \'[/sub]\')',

		'inline'	=>	true,
		'fix'		=>	true,
		'use_in_link'	=>	true,

		'pattern'	=>	array('#\[sub\](.*?)\[/sub\]#ms'),
		'replace'	=>	array('<sub>$matches[1]</sub>')
	),
	'h'		=> array(
		'title'		=>	$lang_sat_bbcode['h'],
		'value'		=>	'&#xf1dc',
		'group'		=>	'02colorsAndFonts',
		'weight'	=>	2,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[h]\', \'[/h]\n\')'
	),
	'align'		=> array(
		'block'		=>	true,
		'inline'	=>	true,

		'pattern'	=>	array('#\[align=(left|right|center|justify)\](.*?)\[/align\]#ms'),
		'replace'	=>	array('</p><p style=\"text-align:$matches[1]\">$matches[2]</p><p>')
	),
	'left'		=> array(
		'title'		=>	$lang_sat_bbcode['left'],
		'value'		=>	'&#xf036',
		'group'		=>	'03markup',
		'weight'	=>	4,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[align=left]\', \'[/align]\')',

		'block'		=>	true,
		'inline'	=>	true,

		'pattern'	=>	array('#\[left\](.*?)\[/left\]#ms'),
		'replace'	=>	array('</p><p style=\"text-align:left\">$matches[1]</p><p>')
	),
	'right'		=> array(
		'title'		=>	$lang_sat_bbcode['right'],
		'value'		=>	'&#xf038',
		'group'		=>	'03markup',
		'weight'	=>	8,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[align=right]\', \'[/align]\')',

		'block'		=>	true,
		'inline'	=>	true,

		'pattern'	=>	array('#\[right\](.*?)\[/right\]#ms'),
		'replace'	=>	array('</p><p style=\"text-align:right\">$matches[1]</p><p>')
	),
	'center'	=> array(
		'title'		=>	$lang_sat_bbcode['center'],
		'value'		=>	'&#xf037',
		'group'		=>	'03markup',
		'weight'	=>	6,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[align=center]\', \'[/align]\')',

		'block'		=>	true,
		'inline'	=>	true,

		'pattern'	=>	array('#\[center\](.*?)\[/center\]#ms'),
		'replace'	=>	array('</p><p style=\"text-align:center\">$matches[1]</p><p>')
	),
	'justify'	=> array(
		'title'		=>	$lang_sat_bbcode['justify'],
		'value'		=>	'&#xf039',
		'group'		=>	'03markup',
		'weight'	=>	10,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[align=justify]\', \'[/align]\')',

		'block'		=>	true,
		'inline'	=>	true,

		'pattern'	=>	array('#\[justify\](.*?)\[/justify\]#ms'),
		'replace'	=>	array('</p><p style=\"text-align:justify\">$matches[1]</p><p>')
	),
	'indent'	=> array(
		'title'		=>	$lang_sat_bbcode['indent'],
		'value'		=>	'&#xf03c',
		'group'		=>	'03markup',
		'weight'	=>	12,
//		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[indent]\', \'\')',
		'box'		=>	'<p><label><span>'.$lang_sat_bbcode['indentwidth'].'</span></label><input type="button" name="lim" value="&#xf068" onmousedown="changenum(\'indentwidth\',-0.1,\'positive\');showindent();changenumTimer=setInterval(()=>{changenum(\'indentwidth\',-0.1,\'positive\');showindent()},150)" onmouseup="clearInterval(changenumTimer)" onmouseleave="clearInterval(changenumTimer)">&nbsp;<input type="text" name="indentwidth" size="3" value="1.5" oninput="valnum($(this), \'positive\');showindent()">&nbsp;<input type="button" name="lip" value="&#xf067" onmousedown="changenum(\'indentwidth\',0.1);showindent();changenumTimer=setInterval(()=>{changenum(\'indentwidth\',0.1);showindent()},150)" onmouseup="clearInterval(changenumTimer)" onmouseleave="clearInterval(changenumTimer)"></p>'
.'<p><label><span>'.$lang_sat_bbcode['indentheight'].'</span></label><input type="button" name="tim" value="&#xf068" onmousedown="changenum(\'indentheight\',-0.1,\'positive\');showindent();changenumTimer=setInterval(()=>{changenum(\'indentheight\',-0.1,\'positive\');showindent()},150)" onmouseup="clearInterval(changenumTimer)" onmouseleave="clearInterval(changenumTimer)">&nbsp;<input type="text" name="indentheight" size="3" value="1.2" oninput="valnum($(this), \'positive\');showindent()">&nbsp;<input type="button" name="tip" value="&#xf067" onmousedown="changenum(\'indentheight\',0.1);showindent();changenumTimer=setInterval(()=>{changenum(\'indentheight\',0.1);showindent()},150)" onmouseup="clearInterval(changenumTimer)" onmouseleave="clearInterval(changenumTimer)"><p>'
.'<div class="entry-content"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br /><span class="testindent" style="display:inline-block;width:1em"></span><span class="firstletter">L</span>orem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>'
.'<div class="frm-buttons"><span class="submit primary"><input type="button" name="addindent" value="'.$lang_sat_bbcode['addindent'].'" onclick="setindent()"></span></div>',

		'endtag'	=>	false,

		'pattern'	=>	array(
						'#\[indent\]#ms',
						'#\[indent=(\d*\.?\d+)\]#ms',
						'#\[indent=(\d*\.?\d+), ?(\d*\.?\d+)\]#ms'
					),
		'replace'	=>	array(
						'<span style=\"display:inline-block;width:1.5em;height:1.2em\"></span>',
						'<span style=\"display:inline-block;width:$matches[1]em\"></span>',
						'<span style=\"display:inline-block;width:$matches[1]em;height:$matches[2]em\"></span>'
					)
	),
	'hr'	=> array(
		'title'		=>	$lang_sat_bbcode['hr'],
		'value'		=>	'&#xf547',
		'group'		=>	'03markup',
		'weight'	=>	16,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[hr]\n\', \'\')',

		'endtag'	=>	false,
		'block'		=>	true,

		'pattern'	=>	array('#\[hr\]#m'),
//		'replace'	=>	array('</p><hr><p>')
		'replace'	=>	array('<span class=\"hr\"></span>')
	),
	'size'		=> array(
		'title'		=>	$lang_sat_bbcode['size'],
		'value'		=>	'&#xf034',
		'group'		=>	'02colorsAndFonts',
		'weight'	=>	6,
		'box'		=>	sizelist(),

		'inline'	=>	true,
		'fix'		=>	true,
		'use_in_link'	=>	true,

		'pattern'	=>	array('#\[size=(.*?)\](.*?)\[/size\]#ms'),
		'replace'	=>	array('".handle_size_tag($matches[1], $matches[2])."')
	),
	'font'		=> array(
		'title'		=>	$lang_sat_bbcode['font'],
		'value'		=>	'&#xf031',
		'group'		=>	'02colorsAndFonts',
		'weight'	=>	4,
		'box'		=>	fontlist(),

		'inline'	=>	true,
		'fix'		=>	true,
		'use_in_link'	=>	true,

		'pattern'	=>	array('#\[font=(.*?)\](.*?)\[/font\]#ms'),
		'replace'	=>	array('".handle_font_tag($matches[1], $matches[2])."')
	),
	'url'		=> array(
		'title'		=>	$lang_sat_bbcode['url'],
		'value'		=>	'&#xf0c1',
		'weight'	=>	2,
		'onclick'	=>	"insertUrl('{$lang_sat_bbcode['urllink']}','{$lang_sat_bbcode['urlname']}')"
	),
	'email'		=> array(
//		'title'		=>	$lang_sat_bbcode['email'],
		'value'		=>	'&#xf0e0',
		'weight'	=>	4,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[email]\', \'[/email]\')'
	),
	'img'		=> array(
		'title'		=>	$lang_sat_bbcode['img'],
		'value'		=>	'&#xf03e',
		'weight'	=>	8,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[img]\', \'[/img]\')'
	),
	'quote'		=> array(
		'title'		=>	$lang_sat_bbcode['quote'],
		'value'		=>	'&#xf27a',
		'weight'	=>	12,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[quote]\', \'[/quote]\n\')'
	),
	'code'		=> array(
		'title'		=>	$lang_sat_bbcode['code'],
		'value'		=>	'&#xf121',
		'weight'	=>	14,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[code]\', \'[/code]\n\')'
	),
	'spoiler'		=> array(
		'title'		=>	$lang_sat_bbcode['spoiler'],
		'value'		=>	'&#xf02d',
		'weight'	=>	16,
		'onclick'	=>	"insertSpoiler('{$lang_sat_bbcode['spoilmsg']}')",

		'block'		=>	true,
		'nesting'	=>	3,
		'pattern'	=>	array('#\[spoiler=?(.*?)\](.*?)#s', '#\[/spoiler\]#s'),
		'replace'	=>	array('".handle_spoiler_tag($matches[1], $matches[2])."', '</p></blockquote></div><p>'),
	),
	'hide'		=> array(
		'title'		=>	$lang_sat_bbcode['hide'],
		'value'		=>	'&#xf023',
		'weight'	=>	18,
		'onclick'	=>	"insertHide('{$lang_sat_bbcode['hidemsg']}')",

		'block'		=>	true,

		'pattern'	=>	array('#\[hide=?(\d*?)\](.*?)\[/hide\]#s'),
		'replace'	=>	array('</p>".handle_hide_tag($matches[1], $matches[2])."<p>')
	),
	'you'		=> array(
		'title'		=>	$lang_sat_bbcode['you'],
		'value'		=>	'&#xf1ae',
		'group'		=>	'09adds',
		'weight'	=>	2,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[you]\', \'\')',

		'endtag'	=>	false,
		'use_in_link'	=>	true,

		'pattern'	=>	array('#\[you\]#ms'),
		'replace'	=>	array('".handle_you_tag()."')
	),
	'abbr'		=> array(
		'title'		=>	$lang_sat_bbcode['abbr'],
		'value'		=>	'&#xf0eb',
		'group'		=>	'09adds',
		'weight'	=>	4,
		'onclick'	=>	"insertAbbr('{$lang_sat_bbcode['abbrmsg']}')",

		'inline'	=>	true,
		'use_in_link'	=>	true,
		'del_quotes'	=>	true,

		'pattern'	=>	array('#\[abbr=(.+?)\](.+?)\[/abbr\]#s'),
		'replace'	=>	array('<abbr title=\"$matches[1]\">$matches[2]</abbr>')
	),
	'list'		=> array(
//		'title'		=>	$lang_sat_bbcode['list'],
		'value'		=>	'&#xf03a',
		'group'		=>	'03markup',
		'weight'	=>	18,

		'box'		=>	'<p><label><span>'.$lang_sat_bbcode['listview'].'</span></label><input type="radio" name="listview" value="*" checked>&nbsp;<input type="button" value="&#xf141" onclick="changeradio(\'listview\', \'*\')">&nbsp;<input type="radio" name="listview" value="1">&nbsp;<input type="button" value="123" onclick="changeradio(\'listview\', \'1\')">&nbsp;<input type="radio" name="listview" value="a">&nbsp;<input type="button" value="abc" onclick="changeradio(\'listview\', \'a\')"></p>'
.'<p><label><span>'.$lang_sat_bbcode['numitems'].'</span></label><input type="button" name="nim" value="&#xf068" onmousedown="changenum(\'numitems\',-1,\'positive\');changenumTimer=setInterval(()=>{changenum(\'numitems\',-1,\'positive\')},150)" onmouseup="clearInterval(changenumTimer)" onmouseleave="clearInterval(changenumTimer)">&nbsp;<input type="text" name="numitems" size="3" value="1" oninput="valnum($(this), \'positiveint\')">&nbsp;<input type="button" name="nip" value="&#xf067" onmousedown="changenum(\'numitems\',1);changenumTimer=setInterval(()=>{changenum(\'numitems\',1)},150)" onmouseup="clearInterval(changenumTimer)" onmouseleave="clearInterval(changenumTimer)"></p>'
.'<div class="frm-buttons"><span class="submit primary"><input type="button" name="addlist" value="'.$lang_sat_bbcode['addlist'].'" onclick="setlist()"></span></div>'
	),
	'*'		=> array(
//		'title'		=>	$lang_sat_bbcode['*'],
		'value'		=>	'[&#xf069]',
		'group'		=>	'03markup',
		'weight'	=>	20,
		'onclick'	=>	'PUNBB.pun_bbcode.insert_text(\'[*]\', \'[/*]\')'
	),
	'smile'		=> array(
		'title'		=>	$lang_sat_bbcode['smile'],
		'value'		=>	'&#xf118',
		'weight'	=>	6,
		'box'		=>	smilelist(),
	)
);

($hook = get_hook('sat_bbcode_add_tags')) ? eval($hook) : null;


uasort($sat_bbcodes, function($a,$b) {
	if(!isset($a['group'])) $a['group'] = '05default';
	if(!isset($b['group'])) $b['group'] = '05default';
	$r = strcmp($a['group'], $b['group']);
	if ($r != 0) return $r>0 ? 1 : -1;
	if(!isset($a['weight'])) $a['weight'] = 100;
	if(!isset($b['weight'])) $b['weight'] = 100;
	if($a['weight']==$b['weight']) return 0;
	else return $a['weight']>$b['weight'] ? 1 : -1;
});

$GLOBALS['sat_bbcodes'] = $sat_bbcodes;

define('SAT_BBCODE_OPT_LOADED', '1');