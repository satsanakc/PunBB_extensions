<?php if (!defined('FORUM')) die();

global $forum_config, $cur_topic;
if (isset($forum_config['o_pan_seo_img_alt']))
	$forum_config['o_pan_seo_img_alt'] == '0';

if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' && preg_match('#^[fh]ttps://#', $url) == 0) {
	$img_tag =  '&lt;--&gt;';
	return;
}

$layout = array();
$alt = preg_replace_callback('#^(?:float:(left|right))?(?:(\d+)(pr)?)?[xX]?(\d+)?($| +)#', function ($m) use (&$layout) {
	if (!empty($m[1]))
		$layout["float"] = $m[1];
	if (!empty($m[2]))
		$layout["width"] = empty($m[3]) ? 'width: '.$m[2].'px' : ($m[2]<100 ? 'max-width: '.$m[2].'%' : NULL);
	if (!empty($m[4]))
		$layout["height"] = $m[4].'px';
	return '';
}, $alt);

if ($alt == null || $alt == '') {
	if(isset($cur_topic['subject']))
		$alt = $cur_topic['subject'];
	else
		$alt = $url;
}
$nosign = $alt == $cur_topic['subject'] || $alt == $url;
$alt = forum_htmlencode($alt);

$img_tag = '<a href="'.$url.'" target="_blank">&lt;'.$lang_common['Image link'].'&gt;</a>';
//$size = getimagesize($url);
//if ($size[0] > 200 || $size[1] > 200 || !empty($layout["width"]) || !empty($layout["height"])) $bigimg = 1;
if ($is_signature && $forum_user['show_img_sig'] != '0')
	$img_tag = '<span class="sigimage'.(isset($layout["float"]) ? ' '.$layout["float"] : '').($nosign != 0 ? '' : ' withsign').'"'.(isset($layout["width"]) ? ' style="'.$layout["width"].';"' : '').'><img loading="lazy" src="'.$url.'" alt="'.forum_htmlencode($alt).'"'.(isset($layout["height"]) ? ' style="max-height: '.$layout["height"].';"' : '').($nosign != 0 ? '' : 'title="'.$alt.'"').' />'.($nosign != 0 ? '' : '<em>'.$alt.'</em>').'</span>';
else if (!$is_signature && $forum_user['show_img'] != '0')
	$img_tag = '<span class="postimg'.(isset($layout["float"]) ? ' '.$layout["float"] : '').($nosign != 0 ? '' : ' withsign').(isset($bigimg) ? ' bigimg' : '').'"'.(isset($layout["width"]) ? ' style="'.$layout["width"].';"' : '').'><img loading="lazy" src="'.$url.'" alt="'.forum_htmlencode($alt).'"'.(isset($layout["height"]) ? ' style="max-height: '.$layout["height"].';"' : '').($nosign != 0 ? '' : 'title="'.$alt.'"').' />'.($nosign != 0 ? '' : '<em>'.$alt.'</em>').'</span>';