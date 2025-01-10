<?php

function handle_video_tag($link) {
    if (strpos($link, 'youtube')) {
	preg_match('/https:\/\/www\.youtube\.com\/watch\?v=([^&]+)(?:.*?=(\d+)s)?/', $link, $lparse);
	$link = 'https://www.youtube.com/embed/'.$lparse[1].(isset($lparse[2]) ? '?start='.$lparse[2] : '');
    } elseif (strpos($link, 'rutube')) {
	preg_match('/https:\/\/rutube\.ru\/video\/([^\/]+)(?:.*?=(\d+))?/', $link, $lparse);
	$link = 'https://rutube.ru/play/embed/'.$lparse[1].(isset($lparse[2]) ? '/?t='.$lparse[2] : '/');
    } else {
    	$return = ($hook = get_hook('sat_youtube_bbcode_add_tags_end')) ? eval($hook) : null;
	if ($return !== null)
		return $return;
	return '[video]???[/video]';
    }
    return '</p><div style="padding: 5px 0 0; overflow: hidden; position: relative; max-width: 640px;"><div style="overflow: hidden; position: relative; height: 0; padding-bottom: 56.25%;"><iframe src="'.$link.'" frameborder="0" scrolling="no" allowfullscreen="allowfullscreen" loading="lazy" style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"></iframe></div></div><p>';
}

$sat_bbcodes['video'] = array(
	'title'		=>	$lang_sat_youtube['video'],
	'value'		=>	'&#xf04b',
	'group'		=>	'04media',
	'weight'	=>	4,
	'block'		=>	true,
	'del_quotes'	=>	true,

	'box'		=>	'<fieldset class="frm-group">'.$lang_sat_youtube['note'].'<br><input type="text" name="videolink"></fieldset><div class="frm-buttons"><span class="submit primary"><input type="button" name="insertvideo" value="'.$lang_sat_youtube['button'].'" onclick="insertVideo()"></span></div>',
	'pattern'	=>	array('#\[video\](.*?)\[/video\]#ms'),
	'replace'	=>	array('".handle_video_tag($matches[1])."')
);