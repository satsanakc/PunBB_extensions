<?php if (!defined('FORUM')) die();

function add_quote_tag($m) {
	global $forum_config, $base_url, $avatars;
	$ava = '';
	switch ($avatars[$m[4]]) {
		case FORUM_AVATAR_GIF:
			$ava = $base_url.'/'.$forum_config['o_avatars_dir'].'/'.$m[4].'.gif';
			break;
		case FORUM_AVATAR_JPG:
			$ava = $base_url.'/'.$forum_config['o_avatars_dir'].'/'.$m[4].'.jpg';
			break;
		case FORUM_AVATAR_PNG:
			$ava = $base_url.'/'.$forum_config['o_avatars_dir'].'/'.$m[4].'.png';
			break;
		default:
			$ava = empty($forum_config['o_defava']) ? '' : $forum_config['o_defava'];
			break;
	}

	return '<cite>'.(!empty($ava) ? '<img class="citeava" src="'.$ava.'" alt="'.$m[1].'"> ' : '').'<a class="citelink" href="/viewtopic.php?pid='.$m[2].'#p'.$m[2].'">'.format_time($m[3]).'</a> '.$m[1].$m[5].'</cite>';
}

$sat_bbcodes['quote']['pattern'] = array(
	'#<cite>([^<]*?) post_id=(\d+) time=(\d+) user_id=(\d+)(.*?)</cite>#ms'
);
$sat_bbcodes['quote']['replace'] = array('".add_quote_tag($matches)."');