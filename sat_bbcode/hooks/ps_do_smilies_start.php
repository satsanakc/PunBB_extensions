<?php if (!defined('FORUM')) die();

require $ext_info['path'].'/include/smile.php';
$smilies = array();

foreach ($sat_smile_arr as $key => $val) {
	$smilies = array_merge($smilies, $val['list']);
}

$text = ' '.$text.' ';

foreach ($smilies as $smiley_text => $smiley_img) {
		if (strpos($text, $smiley_text) !== false)
			$matches = array();
			preg_match('#.*/(.+)\.#', $smiley_img, $matches);
			$text = preg_replace("#(?<=[>\s])".preg_quote($smiley_text, '#')."(?=\W)#m", '<img src="'.$smiley_img.'" alt="'.$matches[1].'" />', $text);
//			$text = preg_replace("#(?<=[>\s])".preg_quote($smiley_text, '#')."(?=\W)#m", '<img src="'.$smiley_img.'" alt="'.substr($smiley_img, 0, strrpos($smiley_img, '.')).'" />', $text);
}

$return = ($hook = get_hook('ps_do_smilies_end')) ? eval($hook) : null;
